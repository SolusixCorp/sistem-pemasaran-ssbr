<?php

namespace App\Http\Controllers;

use App\Models\CategoryProduct;
use App\Models\Product;
use App\Models\Depo;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Employee;
use App\Models\Settings;
use App\Models\Income;
use Illuminate\Support\Facades\Auth;
use Mike42\Escpos\Printer;
use Mike42\Escpos\PrintConnectors\WindowsPrintConnector;
use Mike42\Escpos\EscposImage;
use Mike42\Escpos\PrintConnectors\FilePrintConnector;
use App\Models\Item;    
use Exception;

use Illuminate\Http\Request;

class StockFlowController extends Controller
{
    public function index() {
        $categories = CategoryProduct::orderBy('category_barang.category_name', 'asc')->get();
        $suppliers = Depo::orderBy('suppliers.supplier_name', 'asc')->get();

        return view('pages.stock.index', [
            "categories" => $categories,
            "suppliers"  => $suppliers,   
        ]);
    }

    public function getAllData() {
        $orders =  Order::leftJoin('customer', 'customer.id', '=', 'orders.customer_id')
                    ->leftJoin('users', 'users.id', '=', 'orders.kasir_id')
                    ->select(['orders.id as order_id', 'orders.order_date', 'customer.customer_name', 'orders.total_with_discount'])
                    ->orderBy('orders.order_date', 'desc')
                    ->get(); 
        $no = 0;
        $status = "";
        $data = array();
        foreach ($orders as $order) {
            $no++;
            $row = array();
            $row[] = $no;
            $row[] = $order->order_date;
            $row[] = 'Depo Malang';
            $row[] = 'IN';
            $row[] = 'Dropping';
            $row[] = '<a href="'. url("/") .'/stock/edit/' . $order->order_id . '" onclick="editForm(' . $order->order_id . ')" class="btn btn-warning btn-sm"><i class="far fa-edit"></i></a>
            <a href="#" onclick="detailsView(' . $order->order_id . ')" class="btn btn-primary btn-sm" data-toggle="modal"  data-target="#modal-details"><i class="far fa-eye"></i></a>
            <a href="'. url("/") .'/stock/print-invoice/' . $order->order_id . '" onclick="editForm(' . $order->order_id . ')" class="btn btn-dark btn-sm"><i class="far fa-file"></i></a>';
            
            array_push($data, $row);
        }

        $output = array("data" => $data);
        return response()->json($output);
    }

    public function getById($id) {
        $order =  Order::with(['customer', 'kasir', 'order_items', 'order_items.barang'])
                    ->where('orders.id', $id)
                    ->first(); 
        return response()->json($order);
    }
    
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $barangs = Product::orderBy('barang_id', 'asc')->get();
        $customers = Employee::orderBy('id', 'desc')->get();
        
        return view('pages.stock.add', [
            "barangs_item" => $barangs,
            "customers"  => $customers,   
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $user = Auth::user();
        $user_id = Auth::id();

        $barang_items = $request['barang_item_id'];
        $qty_items = $request['qty'];
        $notes_item = $request['notes_item'];
        $order_date = $request['date'];
        $order_time = $request['time'];
        
        $order = new Order;
        if ($order_date != null && $order_time != null) {
            $order->order_date = $order_date . " " . $order_time . ":00";
        }
        $order->kasir_id = $user_id;
        $order->customer_id = (int) $request['customer_name'];
        $order->notes = $request['notes'];
        $order->total = 0;

        $item_expense = 0;
        foreach($barang_items as $index => $item) {
            $barang = Product::where('barangs.barang_id', '=', (int) $item)->first(); 

            if ($barang->stock < $qty_items[$index]) {
                return redirect()->route('stock.create')
                ->with('failed_message', 'Stock '. $barang->name .' tidak cukup');
            }

            $order->total += $barang->selling_price * (int) $qty_items[$index];
            $item_expense += $barang->buying_price * (int) $qty_items[$index];
        }

        $discount_notes = $request['discount_notes'];
        $discount_percentage = 0;
        $discount_rp = 0;

        if ($request['discountType'] == "rp") {
            $discount_rp = $request['discount'];
            $discount_percentage = round($request['discount'] / $order->total * 100 , 2);
        } else {
            $discount_rp = $request['discount'] / 100 * $order->total;
            $discount_percentage = $request['discount'];
        }

        $order->total_with_discount = $order->total - $discount_rp;

        $order->discount_notes = $discount_notes;
        $order->discount_rp = $discount_rp;
        $order->discount_percentage = $discount_percentage;
        $order->discount_type = $request['discountType'];

        if ($request['bayar'] < $order->total_with_discount) {
            return redirect()->route('stock.create')
                ->with('failed_message', 'Uang bayar tidak cukup.');
        }

        $order->bayar = $request['bayar'];
        $order->kembalian = (float) $request['bayar'] - $order->total_with_discount;

        if (!$order->save()) {
            return redirect()->route('stock.create')
                ->with('failed_message', 'Data order gagal disimpan.');
        }

        $income = new Income;
        $income->order_id = $order->id;
        $income->item_expense = $item_expense;
        $income->total = $order->total_with_discount;
        $income->income = $income->total - $income->item_expense;
        if (!$income->save()) {
            return redirect()->route('stock.create')
                ->with('failed_message', 'Data income gagal disimpan.');
        }

        foreach($barang_items as $index => $item) {
            $barang = Product::where('barangs.barang_id', '=', (int) $item)->first(); 

            $order_item = new OrderItem;
            $order_item->order_id = $order->id;
            $order_item->barang_id = $barang->barang_id;
            $order_item->qty = $qty_items[$index];
            if ($notes_item[$index] == null) {
                $notes_item[$index] = "-";
            }
            $order_item->notes = $notes_item[$index];
            $order_item->sub_total = $barang->selling_price * (int) $qty_items[$index];

            if (!$order_item->save()) {
                return redirect()->route('stock.create')
                    ->with('failed_message', 'Data order item gagal disimpan.');
            }

            $barang->stock -= $order_item->qty;
            if (!$barang->update()) {
                return redirect()->route('stock.index')
                    ->with('failed_message', 'Data stok barang gagal diupdate.');
            }

        }

        if (!$this->printReceipt($order->id)) {
            return redirect()->route('stock.index')
                    ->with('success_message', 'Data berhasil disimpan. Gagal printing, printer tidak terkoneksi');
        }

        return redirect()->route('stock.index')
            ->with('success_message', 'Data berhasl disimpan.');
        ;
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $orders = Order::where("id", $id)->orderBy('id', 'desc')->get();
        $no = 0;
        $status = "";
        $data = array();
        foreach ($orders as $order) {
            $no++;
            $row = array();
            $row[] = 'Depo Malang';
            $row[] = $order->order_date;
            $row[] = $order->total;
            $row[] = '<a href="#" onclick="editForm(' . $order->id . ')" class="btn btn-success btn-sm btn-block" data-toggle="modal"><i class="far fa-edit"></i> Edit</a>
            <a href="#" onclick="detailsView(' . $order->id . ')" class="btn btn-warning btn-sm btn-block" data-toggle="modal"><i class="far fa-eye"></i> Details</a>';
            
            array_push($data, $row);
        }

        $output = array("data" => $data);
        return response()->json($output);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $barangs = Product::orderBy('barang_id', 'asc')->get();
        $customers = Employee::orderBy('id', 'desc')->get();

        $order = Order::with(['customer', 'kasir', 'order_items'])
                    ->orderBy('id', 'asc')->where('id', '=', $id)->first();
        
        // return response()->json($order);
        return view('pages.stock.edit', [
            "order" => $order,
            "barangs_item" => $barangs,
            "customers"  => $customers,   
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $user = Auth::user();
        $user_id = Auth::id();

        $barang_items = $request['barang_item_id'];
        $qty_items = $request['qty'];
        $notes_item = $request['notes_item'];

        $order_date = $request['date'];
        $order_time = $request['time'];
        
        
        $order = Order::with(['customer', 'kasir', 'order_items'])->find($id);
        if ($order_date != null && $order_time != null) {
            $order->order_date = $order_date . " " . $order_time . ":00";
        }
        $order->kasir_id = $user_id;
        $order->customer_id = (int) $request['customer_name'];
        $order->notes = $request['notes'];
        $order->total = 0;

        $item_expense = 0;
        foreach($barang_items as $index => $item) {
            $barang = Product::where('barangs.barang_id', '=', (int) $item)->first(); 

            if ($barang->stock < $qty_items[$index]) {
                return redirect()->route('stock.create')
                ->with('failed_message', 'Stock '. $barang->name .' tidak cukup');
            }

            $order->total += $barang->selling_price * (int) $qty_items[$index];
            $item_expense += $barang->buying_price * (int) $qty_items[$index];
        }

        $discount_notes = $request['discount_notes'];
        $discount_percentage = 0;
        $discount_rp = 0;

        if ($request['discountType'] == "rp") {
            $discount_rp = $request['discount'];
            $discount_percentage = round($request['discount'] / $order->total * 100 , 2);
        } else {
            $discount_rp = $request['discount'] / 100 * $order->total;
            $discount_percentage = $request['discount'];
        }

        $order->total_with_discount = $order->total - $discount_rp;

        $order->discount_notes = $discount_notes;
        $order->discount_rp = $discount_rp;
        $order->discount_percentage = $discount_percentage;
        $order->discount_type = $request['discountType'];

        if ($request['bayar'] < $order->total_with_discount) {
            return redirect()->route('stock.create')
                ->with('failed_message', 'Uang bayar tidak cukup.');
        }

        $order->bayar = $request['bayar'];
        $order->kembalian = (float) $request['bayar'] - $order->total_with_discount;

        if (!$order->update()) {
            return redirect()->route('stock.index')
                ->with('failed_message', 'Data order gagal diedit.');
        }

        $income = Income::where("order_id", $order->id)->first();
        $income->item_expense = $item_expense;
        $income->total = $order->total_with_discount;
        $income->income = $income->total - $income->item_expense;
        if (!$income->update()) {
            return redirect()->route('stock.create')
                ->with('failed_message', 'Data income gagal diedit.');
        }

        foreach($order->order_items as $index => $item) {
            $items_delete = OrderItem::find($item->id)->delete();
            if(!$items_delete){
                return redirect()->route('stock.index')
                    ->with('failed_message', 'Data order times gagal diedit.');
            }
        }

        foreach($barang_items as $index => $item) {
            $barang = Product::where('barangs.barang_id', '=', (int) $item)->first(); 

            $order_item = new OrderItem;
            $order_item->order_id = $order->id;
            $order_item->barang_id = $barang->barang_id;
            $order_item->qty = $qty_items[$index];
            if ($notes_item[$index] == null) {
                $notes_item[$index] = "-";
            }
            $order_item->notes = $notes_item[$index];
            $order_item->sub_total = $barang->selling_price * (int) $qty_items[$index];

            if (!$order_item->save()) {
                return redirect()->route('stock.index')
                    ->with('failed_message', 'Data order item gagal diedit.');
            }

            if (!$barang->update()) {
                return redirect()->route('stock.index')
                    ->with('failed_message', 'Data stok barang gagal diupdate.');
            }
        }


        return redirect()->route('stock.index')
            ->with('success_message', 'Data order berhasil diedit.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function printReceiptHandler($order_id) {
        // dd($this->printReceipt2($order_id));
        if($this->printReceipt($order_id) == true){
            return redirect()->route('stock.index')
                    ->with('success_message', 'Data berhasil di cetak.');
        } else { 
            if($this->printReceipt2($order_id) == true){
                return redirect()->route('stock.index')
                        ->with('success_message', 'Data berhasil di cetak.');
            } else { 
                return redirect()->route('stock.index')
                        ->with('failed_message', 'Data gagal di cetak.');
            }
        }
    }

    public function printReceipt($order_id) {
        try {
            // Enter the share name for your USB printer here
            $connector3 = new WindowsPrintConnector("POS-80C");
            $printer3 = new Printer($connector3);
            $this->printStruct($printer3, $order_id);

            return true;
        } catch(Exception $e) {
            // dd($e);
            return false;
        }
    }
    public function printReceipt2($order_id) {
        try {
            $connector2 = new WindowsPrintConnector("POS-80C-USB1");
            $printer2 = new Printer($connector2);
            $this->printStruct($printer2, $order_id);

            return true;
        } catch(Exception $e) {
            // dd($e);
            return false;
        }
    }

    public function printStruct($printer, $order_id) {
        $settings =  Settings::first(); 

        if ($settings == null ){
            $settings =  new Settings;
            $settings->company_name = "Kasir App";
            $settings->company_address = "Jl. Penambangan No 100";
            $settings->company_email = "kasirapp@gmail.com";
            $settings->company_phone = "0810000000000";
            $settings->invoice_prefix = "TRXKSR000";
        }

        $order =  Order::with(['customer', 'kasir', 'order_items', 'order_items.barang'])
                ->where('orders.id', $order_id)
                ->first(); 
                
        $items = array();
        $notes = array();

        foreach($order->order_items as $it) {
            array_push($items, new item($it->barang->name .  " | " . $it->qty ." x ". rupiah($it->barang->selling_price, FALSE), rupiah($it->sub_total, FALSE)));
            if($it->notes != "" && $it->notes != "-") {
                array_push($notes, new item($it->notes, ""));
            }
        }
        
    
        $total = new item('GRAND TOTAL ', rupiah($order->total_with_discount, false), false);
        /* Date is kept the same for testing */
        // $date = date('l jS \of F Y h:i:s A');
        $date = $order->order_date;
        
        /* Start the printer */
        // $logo = EscposImage::load("images/no-logo-available.png", false);
        // $printer = new Printer($connector);
        
        /* Print top logo */
        $printer -> setJustification(Printer::JUSTIFY_CENTER);
        // $printer -> graphics($logo);
        // $printer -> bitImage($logo);
        
        /* Name of shop */
        $printer -> selectPrintMode(Printer::MODE_DOUBLE_WIDTH);
        $printer -> text($settings->company_name . "\n");
        $printer -> selectPrintMode();
        $printer -> text($settings->company_address . "\n");
        $printer -> text("No.Telp - ". $settings->company_phone . "\n");
        $printer -> feed();
        
        /* Title of receipt */
        $printer -> setEmphasis(true);
        $printer -> text("INVOICE : ". $settings->invoice_prefix . $order->id ."\n");
        $printer -> setEmphasis(true);
        $printer -> feed();
        
        /* Items */
        $printer -> setJustification(Printer::JUSTIFY_LEFT);
        $printer -> text(new item('PEMBELI : '. $order->customer->customer_name, ''));
        $printer -> setEmphasis(true);
        $printer -> text(new item('KASIR : '. $order->kasir->name, ''));
        $printer -> setEmphasis(false);
        if ($order->notes != "" && $order->notes != null && $order->notes != "-"){
            $printer -> text(new item('CATATAN : '. $order->notes, ''));
        }

        $printer -> text(new item('------------------------------------------------', ''));
        $printer -> setEmphasis(true);
        $printer -> text(new item('ITEMS', 'SUB TOTAL'));
        $printer -> setEmphasis(false);
        
        foreach ($items as $key => $item) {
            $printer -> text($item);
            if(count($notes) > 0) {
                $printer -> text($notes[$key]);
            }
        }
        $printer -> feed();

        $printer -> setEmphasis(true);
        $printer -> text(new item('TOTAL ', rupiah($order->total, false)));
        $printer -> setEmphasis(false);
        $printer -> text(new item('Diskon (%)  ', (string) $order->discount_percentage . "%" ));
        $printer -> setEmphasis(false);
        $printer -> text(new item('Diskon (RP) ', rupiah($order->discount_rp, false)));

        
        /* Tax and total */
        $printer -> setEmphasis(true);
        $printer -> text($total);

        $printer -> text(new item('------------------------------------------------', ''));
        $printer -> setEmphasis(false);
        $printer -> text(new item('BAYAR', rupiah($order->bayar, false) ));
        $printer -> setEmphasis(false);
        $printer -> text(new item('KEMBALIAN ', rupiah($order->kembalian, false)));


        $printer -> selectPrintMode();
        
        /* Footer */
        $printer -> feed(2);
        $printer -> setJustification(Printer::JUSTIFY_CENTER);
        $printer -> text("Terima kasih telah berbelanja di \n");
        $printer -> text($settings->company_name ."\n");
        $printer -> text("Silahkan email kami di ". $settings->company_email ."\n");
        $printer -> feed(2);
        $printer -> text($date . "\n");
        
        /* Cut the receipt and open the cash drawer */
        $printer -> cut();
        $printer -> pulse();
        
        if ($printer -> close()) {
            return redirect()->route('stock.index')
            ->with('success_message', 'Data berhasil dicetak.');
        }
        return redirect()->route('stock.index')
            ->with('success_message', 'Data berhasil dicetak.');
    }
}
