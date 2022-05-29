<?php

namespace App\Http\Controllers;

use App\Models\CategoryProduct;
use App\Models\Product;
use App\Models\ProductDepo;
use App\Models\Depo;
use App\Models\Stock;
use App\Models\StockFlow;
use App\Models\CashFlow;
use App\Models\Settings;
use App\Models\Income;
use Illuminate\Support\Facades\Auth;
use Mike42\Escpos\Printer;
use Mike42\Escpos\PrintConnectors\WindowsPrintConnector;
use Mike42\Escpos\EscposImage;
use Mike42\Escpos\PrintConnectors\FilePrintConnector;
use App\Models\Item;    
use Exception;
use DB;

use Illuminate\Http\Request;

class StockFlowController extends Controller
{
    public function index() {
        $categories = CategoryProduct::orderBy('category.category_name', 'asc')->get();
        $depos = Depo::leftJoin('users', 'user_id', '=', 'users.id')->get();

        return view('pages.stock.index', [
            "categories" => $categories,
            "depos"  => $depos,   
        ]);
    }

    public function getAllData() {
        $user = Auth::user();

        $stocks =  StockFlow::leftJoin('depos', 'depo_id', '=', 'depos.id')
                ->leftJoin('users', 'depos.user_id', '=', 'users.id')
                ->select('stock_flow.id as stock_id', 'stock_flow.input_date', 'name', 'stock_type', 'stockin_category', 'stockout_category', DB::raw('sum(qty) as qty'))
                ->groupBy('input_date')
                ->where('depos.user_id', '=', $user->id)
                ->orderBy('stock_flow.input_date', 'desc')
                ->get(); 
       
        $no = 0;
        $status = "";
        $data = array();
        foreach ($stocks as $stock) {
            $no++;
            $row = array();
            $row[] = $no;
            $row[] = $stock->input_date;
            $row[] = $stock->name;
            $row[] = strtoupper($stock->stock_type);
            if ($stock->stockin_category != '') {
                $row[] = ucfirst($stock->stockin_category);
            } else {
                $row[] = ucfirst($stock->stockout_category);
            }
            $row[] = $stock->qty;
            $row[] = '<a href="'. url("/") .'/stock/edit/' . $stock->input_date . '" onclick="editForm(' . $stock->stock_id . ')" class="btn btn-warning btn-sm"><i class="far fa-edit"></i></a>
            <a href="#" onclick="detailsView(' . dateToNumber($stock->input_date) . ')" class="btn btn-primary btn-sm" data-toggle="modal"><i class="far fa-eye"></i></a>';
            
            array_push($data, $row);
        }

        $output = array("data" => $data);
        return response()->json($output);
    }

    public function getById($id) {
        $stockData =  StockFlow::with('depo', 'products')->find($id);

        $stock = array(
            'stock_id' => $stockData->id,
            'input_date' => $stockData->input_date,
            'depo' => $stockData->depo->user->name,
            'type' => $stockData->stock_type,
            'products' => $stockData->products,
        );
        return response()->json($stock);
    }

    public function getByDate($date) {
        $stockDatas =  StockFlow::with('depo', 'product')
                    ->where('input_date', '=', numberToDate($date))
                    ->get();

        $products = array();
        foreach($stockDatas as $stockData) { 
            $products[] = array(
                'product_name' => $stockData->product->name,
                'qty' => $stockData->qty,
                'remaining_stock' => $stockData->remaining_stock
            ); 
        }

        $desc = "";
        if ($stockDatas[0]->stockin_category != '') {
            $desc = $stockDatas[0]->stockin_category;
        } else {
            $desc = $stockDatas[0]->stockout_category;
        }

        $stock = array(
            'input_date' => $stockDatas[0]->input_date,
            'depo' => $stockDatas[0]->depo->user->name,
            'type' => strtoupper($stockDatas[0]->stock_type),
            'desc' => ucfirst($desc),
            'products' => $products,
        );
        
        return response()->json($stock);
    }
    
    
    
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $user = Auth::user();

        if ($user->role == 'ho') {
            $products = Product::orderBy('name', 'asc')->get();
            $depos = Depo::leftJoin('users', 'user_id', '=', 'users.id')->get();
        } else {
            $products = ProductDepo::leftJoin('products', 'product_id', '=', 'products.id')
                    ->select('products_depo.id', 'products.name', 'products_depo.stock', 'products_depo.depo_price')
                    ->orderBy('name', 'asc')
                    ->get();

            $depos = Depo::leftJoin('users', 'user_id', '=', 'users.id')
                    ->where('user_id', '=', $user->id)
                    ->get();
        }

        $productDatas = array();
        foreach ($products as $product) {
            $depo = Depo::where('user_id', '=', $user->id)->first();
            if ($depo->type == 'principle') {
                $prices = array(
                    rupiah($product->consument_price, TRUE) . ' (Consument)',
                    rupiah($product->retail_price, TRUE) . ' (Retail)',
                    rupiah($product->sub_whole_price, TRUE) . ' (Sub Whole)',
                    rupiah($product->wholesales_price, TRUE). ' (Whole)');
            } else {
                $prices = array(
                    rupiah($product->depo_price, TRUE) . ' (Depo)');
            }

            $productData = array(
                'product_id' => $product->id,
                'product_name' => $product->name,
                'stock_remaining' => $product->stock,
                'price' => $prices
            );
            $productDatas[] = $productData;
        }

        // return $productDatas;

        return view('pages.stock.add', [
            "products_item" => $productDatas,
            "depos"  => $depos,   
        ]);
    }

    public function getProductById($id) {
        $user = Auth::user();
        if ($user->role == 'ho') {
            $product = Product::find($id);
        } else {
            $product = ProductDepo::leftJoin('products', 'product_id', '=', 'products.id')
                    ->select('products_depo.id', 'products.name', 'products_depo.stock', 'products_depo.depo_price')
                    ->find($id);
        }
        $depo = Depo::where('user_id', '=', $user->id)->first();
            if ($depo->type == 'principle') {
                $prices = array(
                    rupiah($product->consument_price, TRUE) . ' (Consument)',
                    rupiah($product->retail_price, TRUE) . ' (Retail)',
                    rupiah($product->sub_whole_price, TRUE) . ' (Sub Whole)',
                    rupiah($product->wholesales_price, TRUE) . ' (Whole)');
            } else {
                $prices = array(
                    rupiah($product->depo_price, TRUE) . ' (Depo)');
            }

            $productData = array(
                'product_id' => $product->id,
                'product_name' => $product->name,
                'stock_remaining' => $product->stock,
                'price' => $prices
            );

        return response()->json($productData);
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
        
        $in_date = $request['date'];
        $in_time = $request['time'];
        $in_depo = $request['depo_name'];
        $in_stock_type = $request['stock_type'];
        $in_stock_category = $request['stock_category'];

        $product_items = $request['product_item_id'];
        $qty_items = $request['qty'];
        $price_items = $request['price'];

        $total_amount = 0;
        $stoks = array();
        $updateProducts = array();
        foreach($product_items as $index => $item) {
            $user = Auth::user();
            if ($user->role == 'ho') {
                $product = Product::where('products.id', '=', (int) $item)->first(); 
            } else {
                $productHO = Product::where('products.id', '=', (int) $item)->first(); 
                $product = ProductDepo::leftJoin('products', 'product_id', '=', 'products.id')
                        ->select('products_depo.id', 'products.name', 'products_depo.stock', 'products_depo.depo_price')
                        ->where('products.id', '=', (int) $item)
                        ->first(); 
            }

            $stock = new StockFlow;
            $stock->depo_id = (int) $in_depo;
            $stock->product_id = (int) $item;
            $stock->stock = (int) $product->stock + (int) $qty_items[$index];
            if ($in_date != null && $in_time != null) {
                $stock->input_date = $in_date . " " . $in_time . ":00";
            }
            $stock->stock_type = $in_stock_type;
            $stock->qty = (int) $qty_items[$index];
            $stock->remaining_stock = (int) $product->stock;
    
            $position = strpos($price_items[$index], ' (');
            $price = substr($price_items[$index], 0 , $position);
            $stock->price_type = priceType($price_items[$index]);

            $stock->price = (float) rupiahNumber($price_items[$index]);
            if ($in_stock_type == 'in') {
                if ($user->role == 'depo') {
                    if ($productHO->stock < $qty_items[$index]) {
                        return redirect()->route('stock.create')
                        ->with('failed_message', 'Stock '. $product->name .' tidak cukup');
                    }

                    $productHO->stock -= (int) $qty_items[$index]; 

                    if (!$productHO->update()) {
                        return redirect()->route('stock.create')
                            ->with('failed_message', 'Data prouct HO flow gagal disimpan.');
                    }
                }

                $stock->stockin_category = $in_stock_category;
                $stock->stockout_category = '';
                $product->stock += (int) $qty_items[$index];
            } else {
                if ($product->stock < $qty_items[$index]) {
                    return redirect()->route('stock.create')
                    ->with('failed_message', 'Stock '. $product->name .' tidak cukup');
                }
                
                $stock->stockout_category = $in_stock_category;
                $stock->stockin_category = '';
                $product->stock -= (int) $qty_items[$index];
            }

            
            $total_amount += (float) rupiahNumber($price_items[$index]) * (int) $qty_items[$index];

            if (!$stock->save()) {
                return redirect()->route('stock.create')
                    ->with('failed_message', 'Data stock flow gagal disimpan.');
            }
    
            if (!$product->update()) {
                return redirect()->route('stock.create')
                    ->with('failed_message', 'Data stock flow gagal disimpan.');
            }

        }


        if ($in_stock_type == 'out') {
            $cash = new CashFlow;
            $cash->depo_id = $in_depo;
            if ($in_date != null && $in_time != null) {
                $cash->input_date = $in_date . " " . $in_time . ":00";
            }
            $cash->cash_type = 'revenue';
            $cash->revenue_type_in = 'product_sales';
            $cash->expense_type = 'transfer';
            $cash->notes = 'Product Sales';
            $cash->amount = $total_amount;
            $cash->is_matched = 'true';
            $cash->upload_file = '';

            if (!$cash->save()) {
                return redirect()->route('stock.create')
                    ->with('failed_message', 'Data cash flow gagal disimpan.');
            }
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
        $stocks = stock::where("id", $id)->orderBy('id', 'desc')->get();
        $no = 0;
        $status = "";
        $data = array();
        foreach ($stocks as $stock) {
            $no++;
            $row = array();
            $row[] = 'Depo Malang';
            $row[] = $stock->stock_date;
            $row[] = $stock->total;
            $row[] = '<a href="#" onclick="editForm(' . $stock->id . ')" class="btn btn-success btn-sm btn-block" data-toggle="modal"><i class="far fa-edit"></i> Edit</a>
            <a href="#" onclick="detailsView(' . $stock->id . ')" class="btn btn-warning btn-sm btn-block" data-toggle="modal" ><i class="far fa-eye"></i> Details</a>';
            
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
        $user = Auth::user();

        if ($user->role == 'ho') {
            $products = Product::orderBy('name', 'asc')->get();
            $depos = Depo::leftJoin('users', 'user_id', '=', 'users.id')->get();
        } else {
            $products = ProductDepo::leftJoin('products', 'product_id', '=', 'products.id')
                    ->select('products_depo.id', 'products.name', 'products_depo.stock', 'products_depo.depo_price')
                    ->orderBy('name', 'asc')
                    ->get();

            $depos = Depo::leftJoin('users', 'user_id', '=', 'users.id')
                    ->where('user_id', '=', $user->id)
                    ->get();
        }

        $productDatas = array();
        foreach ($products as $product) {
            $depo = Depo::where('user_id', '=', $user->id)->first();
            if ($depo->type == 'principle') {
                $prices = array(
                    rupiah($product->consument_price, TRUE) . ' (Consument)',
                    rupiah($product->retail_price, TRUE) . ' (Retail)',
                    rupiah($product->sub_whole_price, TRUE) . ' (Sub Whole)',
                    rupiah($product->wholesales_price, TRUE). ' (Whole)');
            } else {
                $prices = array(
                    rupiah($product->depo_price, TRUE) . ' (Depo)');
            }

            $productData = array(
                'product_id' => $product->id,
                'product_name' => $product->name,
                'stock_remaining' => $product->stock,
                'price' => $prices
            );

            $productDatas[] = $productData;
        }

        $stock = StockFlow::with(['depo', 'product'])
                    ->where('input_date', '=', $id)
                    ->first();
        
        // return response()->json($stock);
        return view('pages.stock.edit', [
            "stock" => $stock,
            "products_item" => $products,
            "depos"  => $depos,   
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

        $product_items = $request['product_item_id'];
        $qty_items = $request['qty'];
        $notes_item = $request['notes_item'];

        $stock_date = $request['date'];
        $stock_time = $request['time'];
        
        
        $stock = stock::with(['customer', 'kasir', 'stock_items'])->find($id);
        if ($stock_date != null && $stock_time != null) {
            $stock->stock_date = $stock_date . " " . $stock_time . ":00";
        }
        $stock->kasir_id = $user_id;
        $stock->customer_id = (int) $request['customer_name'];
        $stock->notes = $request['notes'];
        $stock->total = 0;

        $item_expense = 0;
        foreach($product_items as $index => $item) {
            $product = Product::where('products.product_id', '=', (int) $item)->first(); 

            if ($product->stock < $qty_items[$index]) {
                return redirect()->route('stock.create')
                ->with('failed_message', 'Stock '. $product->name .' tidak cukup');
            }

            $stock->total += $product->selling_price * (int) $qty_items[$index];
            $item_expense += $product->buying_price * (int) $qty_items[$index];
        }

        $discount_notes = $request['discount_notes'];
        $discount_percentage = 0;
        $discount_rp = 0;

        if ($request['discountType'] == "rp") {
            $discount_rp = $request['discount'];
            $discount_percentage = round($request['discount'] / $stock->total * 100 , 2);
        } else {
            $discount_rp = $request['discount'] / 100 * $stock->total;
            $discount_percentage = $request['discount'];
        }

        $stock->total_with_discount = $stock->total - $discount_rp;

        $stock->discount_notes = $discount_notes;
        $stock->discount_rp = $discount_rp;
        $stock->discount_percentage = $discount_percentage;
        $stock->discount_type = $request['discountType'];

        if ($request['bayar'] < $stock->total_with_discount) {
            return redirect()->route('stock.create')
                ->with('failed_message', 'Uang bayar tidak cukup.');
        }

        $stock->bayar = $request['bayar'];
        $stock->kembalian = (float) $request['bayar'] - $stock->total_with_discount;

        if (!$stock->update()) {
            return redirect()->route('stock.index')
                ->with('failed_message', 'Data stock gagal diedit.');
        }

        $income = Income::where("stock_id", $stock->id)->first();
        $income->item_expense = $item_expense;
        $income->total = $stock->total_with_discount;
        $income->income = $income->total - $income->item_expense;
        if (!$income->update()) {
            return redirect()->route('stock.create')
                ->with('failed_message', 'Data income gagal diedit.');
        }

        foreach($stock->stock_items as $index => $item) {
            $items_delete = stockItem::find($item->id)->delete();
            if(!$items_delete){
                return redirect()->route('stock.index')
                    ->with('failed_message', 'Data stock times gagal diedit.');
            }
        }

        foreach($product_items as $index => $item) {
            $product = Product::where('products.product_id', '=', (int) $item)->first(); 

            $stock_item = new stockItem;
            $stock_item->stock_id = $stock->id;
            $stock_item->product_id = $product->product_id;
            $stock_item->qty = $qty_items[$index];
            if ($notes_item[$index] == null) {
                $notes_item[$index] = "-";
            }
            $stock_item->notes = $notes_item[$index];
            $stock_item->sub_total = $product->selling_price * (int) $qty_items[$index];

            if (!$stock_item->save()) {
                return redirect()->route('stock.index')
                    ->with('failed_message', 'Data stock item gagal diedit.');
            }

            if (!$product->update()) {
                return redirect()->route('stock.index')
                    ->with('failed_message', 'Data stok product gagal diupdate.');
            }
        }


        return redirect()->route('stock.index')
            ->with('success_message', 'Data stock berhasil diedit.');
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

    public function printReceiptHandler($stock_id) {
        // dd($this->printReceipt2($stock_id));
        if($this->printReceipt($stock_id) == true){
            return redirect()->route('stock.index')
                    ->with('success_message', 'Data berhasil di cetak.');
        } else { 
            if($this->printReceipt2($stock_id) == true){
                return redirect()->route('stock.index')
                        ->with('success_message', 'Data berhasil di cetak.');
            } else { 
                return redirect()->route('stock.index')
                        ->with('failed_message', 'Data gagal di cetak.');
            }
        }
    }

    public function printReceipt($stock_id) {
        try {
            // Enter the share name for your USB printer here
            $connector3 = new WindowsPrintConnector("POS-80C");
            $printer3 = new Printer($connector3);
            $this->printStruct($printer3, $stock_id);

            return true;
        } catch(Exception $e) {
            // dd($e);
            return false;
        }
    }
    public function printReceipt2($stock_id) {
        try {
            $connector2 = new WindowsPrintConnector("POS-80C-USB1");
            $printer2 = new Printer($connector2);
            $this->printStruct($printer2, $stock_id);

            return true;
        } catch(Exception $e) {
            // dd($e);
            return false;
        }
    }

    public function printStruct($printer, $stock_id) {
        $settings =  Settings::first(); 

        if ($settings == null ){
            $settings =  new Settings;
            $settings->company_name = "Kasir App";
            $settings->company_address = "Jl. Penambangan No 100";
            $settings->company_email = "kasirapp@gmail.com";
            $settings->company_phone = "0810000000000";
            $settings->invoice_prefix = "TRXKSR000";
        }

        $stock =  stock::with(['customer', 'kasir', 'stock_items', 'stock_items.product'])
                ->where('stocks.id', $stock_id)
                ->first(); 
                
        $items = array();
        $notes = array();

        foreach($stock->stock_items as $it) {
            array_push($items, new item($it->product->name .  " | " . $it->qty ." x ". rupiah($it->product->selling_price, FALSE), rupiah($it->sub_total, FALSE)));
            if($it->notes != "" && $it->notes != "-") {
                array_push($notes, new item($it->notes, ""));
            }
        }
        
    
        $total = new item('GRAND TOTAL ', rupiah($stock->total_with_discount, false), false);
        /* Date is kept the same for testing */
        // $date = date('l jS \of F Y h:i:s A');
        $date = $stock->stock_date;
        
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
        $printer -> text("INVOICE : ". $settings->invoice_prefix . $stock->id ."\n");
        $printer -> setEmphasis(true);
        $printer -> feed();
        
        /* Items */
        $printer -> setJustification(Printer::JUSTIFY_LEFT);
        $printer -> text(new item('PEMBELI : '. $stock->customer->customer_name, ''));
        $printer -> setEmphasis(true);
        $printer -> text(new item('KASIR : '. $stock->kasir->name, ''));
        $printer -> setEmphasis(false);
        if ($stock->notes != "" && $stock->notes != null && $stock->notes != "-"){
            $printer -> text(new item('CATATAN : '. $stock->notes, ''));
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
        $printer -> text(new item('TOTAL ', rupiah($stock->total, false)));
        $printer -> setEmphasis(false);
        $printer -> text(new item('Diskon (%)  ', (string) $stock->discount_percentage . "%" ));
        $printer -> setEmphasis(false);
        $printer -> text(new item('Diskon (RP) ', rupiah($stock->discount_rp, false)));

        
        /* Tax and total */
        $printer -> setEmphasis(true);
        $printer -> text($total);

        $printer -> text(new item('------------------------------------------------', ''));
        $printer -> setEmphasis(false);
        $printer -> text(new item('BAYAR', rupiah($stock->bayar, false) ));
        $printer -> setEmphasis(false);
        $printer -> text(new item('KEMBALIAN ', rupiah($stock->kembalian, false)));


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
