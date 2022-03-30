<?php

namespace App\Http\Controllers;

use App\Models\CategoryBarang;
use App\Models\Barang;
use App\Models\Supplier;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Customer;
use App\Models\Settings;
use App\Models\Income;
use App\Models\Supply;
use Illuminate\Support\Facades\Auth;
use Mike42\Escpos\Printer;
use Mike42\Escpos\PrintConnectors\WindowsPrintConnector;
use Mike42\Escpos\EscposImage;
use Mike42\Escpos\PrintConnectors\FilePrintConnector;
use App\Models\Item;    

use Illuminate\Http\Request;

class SupplyController extends Controller
{
    public function index() {
        $categories = CategoryBarang::orderBy('category_barang.category_name', 'asc')->get();
        $suppliers = Supplier::orderBy('suppliers.supplier_name', 'asc')->get();
        $barangs = Barang::with(['supplier'])->orderBy('barangs.barang_id', 'asc')->get();

        // return $barangs;
        return view('pages.supply.index', [
            "categories" => $categories,
            "suppliers"  => $suppliers,   
            "barangs"  => $barangs,   
        ]);
    }

    public function getAllData() {
        $supplies =  Supply::leftJoin('barangs', 'barangs.barang_id', '=', 'supply.barang_id')
                    ->leftJoin('suppliers', 'suppliers.supplier_id', '=', 'supply.supplier_id')
                    ->select(['supply.id as supply_id', 'supply.supply_date', 'barangs.name as barang_name', 'suppliers.supplier_name', 'supply.qty', 'supply.total'])
                    ->orderBy('supply.supply_date', 'desc')
                    ->get(); 
        $data = array();
        foreach ($supplies as $sup) {
            $row = array($sup->supply_date, $sup->supplier_name, $sup->barang_name, $sup->qty, rupiah($sup->total, true),
            // '<a href="/supply/edit/' . $sup->supply_id . '" onclick="editForm(' . $sup->supply_id . ')" class="btn btn-success btn-sm btn-block"><i class="far fa-edit"></i> Edit</a>
            '<a href="#" onclick="detailsView(' . $sup->supply_id . ')" class="btn btn-primary btn-sm btn-block" data-toggle="modal"  data-target="#modal-details"><i class="far fa-eye"></i> Details</a>');
            
            array_push($data, $row);
        }

        $output = array("data" => $data);
        return response()->json($output);
    }

    public function getById($id) {
        $supply = Supply::leftJoin('barangs', 'barangs.barang_id', '=', 'supply.barang_id')
                    ->leftJoin('suppliers', 'suppliers.supplier_id', '=', 'supply.supplier_id')
                    ->select(['supply.id as supply_id', 'supply.supply_date', 'barangs.name as barang_name','barangs.buying_price', 'suppliers.supplier_name', 'supply.qty', 'supply.total'])
                    ->orderBy('supply.supply_date', 'desc')
                    ->where('supply.id', $id)
                    ->first(); 
        return response()->json($supply);
    }
    
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $barangs = Barang::orderBy('barang_id', 'asc')->get();
        $customers = Customer::orderBy('id', 'desc')->get();
        
        return view('pages.supply.add', [
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

        $barang_id = (int)$request['inputBarang'];
        $qty_item = $request['qty'];
        $notes_item = $request['notes'];
        // return response()->json($request);

        $barang = Barang::with(['supplier'])->where('barangs.barang_id', $barang_id)->first();
        
        $supply = new Supply;
        $supply->barang_id = $barang_id;
        $supply->supplier_id = $barang->supplier->supplier_id;
        $supply->notes = $notes_item;
        $supply->qty = $qty_item;
        $supply->total = $qty_item * $barang->buying_price;

        if (!$supply->save()) {
            return redirect()->route('supply.index')
                ->with('failed_message', 'Data supply gagal disimpan.');
        }

        $barang->stock += $qty_item;
        if (!$barang->update()) {
            return redirect()->route('supply.index')
                ->with('failed_message', 'Data stok barang gagal diupdate.');
        }

        return redirect()->route('supply.index')
            ->with('success_message', 'Data supply berhasil disimpan.');
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
            $row = array($no, $order->customer_name, $order->order_date, $order->total,
            '<a href="#" onclick="editForm(' . $order->id . ')" class="btn btn-success btn-sm btn-block" data-toggle="modal"><i class="far fa-edit"></i> Edit</a>
            <a href="#" onclick="detailsView(' . $order->id . ')" class="btn btn-warning btn-sm btn-block" data-toggle="modal"><i class="far fa-eye"></i> Details</a>');
            
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
        $barangs = Barang::orderBy('barang_id', 'asc')->get();
        $customers = Customer::orderBy('id', 'desc')->get();

        $order = Order::with(['customer', 'kasir', 'order_items'])
                    ->orderBy('id', 'asc')->where('id', '=', $id)->first();
        
        // return response()->json($order);
        return view('pages.supply.edit', [
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
        // return response()->json($request);
        
        $order = Order::with(['customer', 'kasir', 'order_items'])->find($id);
        $order->kasir_id = $user_id;
        $order->customer_id = (int) $request['customer_name'];
        $order->notes = $request['notes'];
        $order->total = 0;

        $item_expense = 0;
        foreach($barang_items as $index => $item) {
            $barang = Barang::where('barangs.barang_id', '=', (int) $item)->first(); 

            if ($barang->stock < $qty_items[$index]) {
                return redirect()->route('sales.create')
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
            return redirect()->route('sales.create')
                ->with('failed_message', 'Uang bayar tidak cukup.');
        }

        $order->bayar = $request['bayar'];
        $order->kembalian = (float) $request['bayar'] - $order->total_with_discount;

        if (!$order->update()) {
            return redirect()->route('sales.index')
                ->with('failed_message', 'Data order gagal diedit.');
        }

        $income = Income::where("order_id", $order->id)->first();
        $income->item_expense = $item_expense;
        $income->total = $order->total_with_discount;
        $income->income = $income->total - $income->item_expense;
        if (!$income->update()) {
            return redirect()->route('sales.create')
                ->with('failed_message', 'Data income gagal diedit.');
        }

        foreach($order->order_items as $index => $item) {
            $items_delete = OrderItem::find($item->id)->delete();
            if(!$items_delete){
                return redirect()->route('sales.index')
                    ->with('failed_message', 'Data order times gagal diedit.');
            }
        }

        foreach($barang_items as $index => $item) {
            $barang = Barang::where('barangs.barang_id', '=', (int) $item)->first(); 

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
                return redirect()->route('sales.index')
                    ->with('failed_message', 'Data order item gagal diedit.');
            }
        }


        return redirect()->route('sales.index')
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

    public function printReceipt($order_id) {
        try {
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
            // return response()->json($order);

            // Enter the share name for your USB printer here
            $connector = new WindowsPrintConnector("Receipt Printer");
            $printer = new Printer($connector);
        
            /* Print a "Hello world" receipt" */
            // $printer -> text("Hello World!\n");
            // $printer -> cut();
            
            /* Close printer */
            // $printer -> close();

            // $items = array(
            //     new item("Example item #1", "4.00"),
            //     new item("Another thing", "3.50"),
            //     new item("Something else", "1.00"),
            //     new item("A final item", "4.45"),
            // );
            $items = array();
            $notes = array();

            foreach($order->order_items as $it) {
                array_push($items, new item($it->barang->name .  " | " . $it->qty ." x ". rupiah($it->barang->selling_price, FALSE), rupiah($it->sub_total, FALSE)));
                array_push($notes, new item($it->notes, ""));
            }
            
        
            $total = new item('Grand Total ', rupiah($order->total_with_discount, false), false);
            /* Date is kept the same for testing */
            // $date = date('l jS \of F Y h:i:s A');
            $date = $order->order_date;
            
            /* Start the printer */
            $logo = EscposImage::load("images/no-logo-available.png", false);
            $printer = new Printer($connector);
            
            /* Print top logo */
            $printer -> setJustification(Printer::JUSTIFY_CENTER);
            // $printer -> graphics($logo);
            $printer -> bitImage($logo);
            
            /* Name of shop */
            $printer -> selectPrintMode(Printer::MODE_DOUBLE_WIDTH);
            $printer -> text($settings->company_name . "\n");
            $printer -> selectPrintMode();
            $printer -> text($settings->company_address . "\n");
            $printer -> feed();
            
            /* Title of receipt */
            $printer -> setEmphasis(true);
            $printer -> text("INVOICE : ". $settings->invoice_prefix . $order->id ."\n");
            $printer -> setEmphasis(true);
            
            /* Items */
            $printer -> setJustification(Printer::JUSTIFY_LEFT);
            $printer -> text(new item('NAMA PEMBELI : '. $order->customer->customer_name, ''));
            $printer -> setEmphasis(false);
            $printer -> text(new item('KASIR : '. $order->kasir->name, ''));
            $printer -> setEmphasis(true);

            $printer -> text(new item('CATATAN : '. $order->notes, ''));
            $printer -> setEmphasis(true);
            $printer -> text(new item('ITEMS', 'SUB TOTAL'));
            $printer -> setEmphasis(false);
            
            foreach ($items as $key => $item) {
                $printer -> text($item);
                $printer -> text($notes[$key]);
            }
            $printer -> setEmphasis(false);
            $printer -> feed();

            $printer -> text(new item('TOTAL : ', rupiah($order->total, true)));
            $printer -> setEmphasis(false);
            $printer -> text(new item('DISKON (%) : ', rupiah($order->discount_percentage, true)));
            $printer -> setEmphasis(false);
            $printer -> text(new item('DISKON (RP) :', rupiah($order->discount_rp, true)));
            $printer -> setEmphasis(false);
            
            /* Tax and total */
            $printer -> selectPrintMode(Printer::MODE_DOUBLE_WIDTH);
            $printer -> text($total);
            $printer -> selectPrintMode();
            
            /* Footer */
            $printer -> feed(2);
            $printer -> setJustification(Printer::JUSTIFY_CENTER);
            $printer -> text("Terima kasih telah berbelanja di ". $settings->company_name ."\n");
            $printer -> text("Silahkan email kami di ". $settings->company_email ."\n");
            $printer -> feed(2);
            $printer -> text($date . "\n");
            
            /* Cut the receipt and open the cash drawer */
            $printer -> cut();
            $printer -> pulse();
            
            $printer -> close();
            
            
        } catch(Exception $e) {
            echo "Couldn't print to this printer: " . $e -> getMessage() . "\n";
        }
    }

}
