<?php

namespace App\Http\Controllers;

use App\Models\CategoryProduct;
use App\Models\Product;
use App\Models\ProductDepo;
use App\Models\Depo;
use App\Models\Stock;
use App\Models\StockFlow;
use App\Models\CashFlow;
use Illuminate\Support\Facades\Auth;
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
            $row[] = '<a href="'. url("/") .'/stock/edit/' . dateToNumber($stock->input_date) . '" onclick="editForm(' . $stock->stock_id . ')" class="btn btn-warning btn-sm"><i class="far fa-edit"></i></a>
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
                    ->leftJoin('depos', 'depo_id', '=', 'depos.id')
                    ->select('products_depo.id', 'products.name', 'products_depo.stock', 'products_depo.depo_price')
                    ->where('user_id', '=', $user->id)
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
                $product = Product::where('products.id', '=', (int) $item)
                    ->select('id', 'id as product_id', 'name', 'stock')
                    ->first(); 
            } else {
                $product = ProductDepo::leftJoin('products', 'product_id', '=', 'products.id')
                    ->select('products_depo.id', 'products_depo.product_id', 'products.name', 'products_depo.stock', 'products_depo.depo_price')
                    ->where('products_depo.id', '=', (int) $item)
                    ->first(); 

                $productHO = Product::where('products.id', '=',  $product->product_id)->first(); 
            }

            $stock = new StockFlow;
            $stock->depo_id = (int) $in_depo;
            $stock->product_id = (int) $product->id;
            if ($stock->stock == null) {
                $stock->stock = (int) $qty_items[$index];
            } else {
                $stock->stock += (int) $qty_items[$index];
            }
            if ($in_date != null && $in_time != null) {
                $stock->input_date = $in_date . " " . $in_time . ":00";
            }
            $stock->stock_type = $in_stock_type;
            $stock->qty = (int) $qty_items[$index];
            if ($stock->remaining_stock == null) {
                $stock->remaining_stock = (int) $qty_items[$index];
            } else {
                $stock->remaining_stock = (int) $product->stock;
            }
    
            $position = strpos($price_items[$index], ' (');
            $price = substr($price_items[$index], 0 , $position);
            $stock->price_type = priceType($price_items[$index]);

            $stock->price = (float) rupiahNumber($price_items[$index]);
            if ($in_stock_type == 'in') {
                if ($user->role == 'depo') {
                    $productHO;
                    if ($productHO->stock < $qty_items[$index]) {
                        return redirect()->route('stock.create')
                        ->with('failed_message', 'Stock '. $product->name .' Head Office tidak cukup');
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
            $cash->expense_type = '';
            $cash->notes = '';
            $cash->amount = $total_amount;
            $cash->is_matched = 'true';
            $cash->upload_file = '';

            if (!$cash->save()) {
                return redirect()->route('stock.create')
                    ->with('failed_message', 'Data cash flow gagal disimpan.');
            }
        }

        return redirect()->route('stock.index')
            ->with('success_message', 'Data berhasil disimpan.');
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
                    ->leftJoin('depos', 'depo_id', '=', 'depos.id')
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

        $stocks = StockFlow::with(['depo', 'product'])
                    ->where('input_date', '=', numberToDate($id))
                    ->get();

        $products = array();
        foreach($stocks as $stock) { 
            $products[] = array(
                'product_id' => $stock->product->id,
                'product_name' => $stock->product->name,
                'qty' => $stock->qty,
                'remaining_stock' => $stock->remaining_stock,
                'price' => rupiah($stock->price, TRUE) . ' (' . priceTypeLabel($stock->price_type) . ')'
            ); 
        }

        $desc = "";
        if ($stocks[0]->stockin_category != '') {
            $desc = $stocks[0]->stockin_category;
        } else {
            $desc = $stocks[0]->stockout_category;
        }

        $stock = array(
            'id' => dateToNumber($stocks[0]->input_date),
            'input_date' => substr($stocks[0]->input_date, 0, 10),
            'input_time' => substr($stocks[0]->input_date, 11, 5),
            'depo_id' => $stocks[0]->depo->id,
            'depo' => $stocks[0]->depo->user->name,
            'type' => strtoupper($stocks[0]->stock_type),
            'desc' => ucfirst($desc),
            'products' => $products,
        );
        
        // return response()->json($stock);
        return view('pages.stock.edit', [
            "stock" => $stock,
            "products_item" => $productDatas,
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
        $date = numberToDate($id);

        $user = Auth::user();
        
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
                $product = Product::where('products.id', '=', (int) $item)
                    ->select('id', 'id as product_id', 'name', 'stock')
                    ->first(); 
            } else {
                $product = ProductDepo::leftJoin('products', 'product_id', '=', 'products.id')
                    ->select('products_depo.id', 'products_depo.product_id', 'products.name', 'products_depo.stock', 'products_depo.depo_price')
                    ->where('products_depo.id', '=', (int) $item)
                    ->first(); 

                $productHO = Product::where('products.id', '=',  $product->product_id)->first(); 
            }

            $stock = StockFlow::where('input_date', '=', $date)
                    ->first();

            $stock->depo_id = (int) $in_depo;
            $stock->product_id = (int) $product->id;
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

                    $diff = 0;
                    if ($stock->qty > (int) $qty_items[$index]) {
                        $diff = $stock->qty - (int) $qty_items[$index];
                        $productHO->stock -= $diff;
                    } else if ($stock->qty < (int) $qty_items[$index]) { 
                        $diff = (int) $qty_items[$index] - $stock->qty;
                        $productHO->stock += $diff;
                    }

                    if (!$productHO->update()) {
                        return redirect()->route('stock.create')
                            ->with('failed_message', 'Data prouct HO flow gagal disimpan.');
                    }
                }

                $stock->stockin_category = $in_stock_category;
                $stock->stockout_category = '';

            } else {
                if ($product->stock < $qty_items[$index]) {
                    return redirect()->route('stock.create')
                    ->with('failed_message', 'Stock '. $product->name .' tidak cukup');
                }
                
                $stock->stockout_category = $in_stock_category;
                $stock->stockin_category = '';
                
            }

            $diff = 0;
            if ($stock->qty > (int) $qty_items[$index]) {
                $diff = $stock->qty - (int) $qty_items[$index];
                $product->stock -= $diff;
            } else if ($stock->qty < (int) $qty_items[$index]) { 
                $diff = (int) $qty_items[$index] - $stock->qty;
                $product->stock += $diff;
            }

            
            $total_amount += (float) rupiahNumber($price_items[$index]) * (int) $qty_items[$index];

            if (!$stock->update()) {
                return redirect()->route('stock.create')
                    ->with('failed_message', 'Data stock flow gagal disimpan.');
            }
    
            if (!$product->update()) {
                return redirect()->route('stock.create')
                    ->with('failed_message', 'Data stock flow gagal disimpan.');
            }

        }

        if ($in_stock_type == 'out') {
            $cash = CashFlow::where('input_date', '=', $date)->first();
            $cash->depo_id = $in_depo;
            if ($in_date != null && $in_time != null) {
                $cash->input_date = $in_date . " " . $in_time . ":00";
            }
            $cash->cash_type = 'revenue';
            $cash->revenue_type_in = 'product_sales';
            $cash->expense_type = '';
            $cash->notes = '';
            $cash->amount = $total_amount;
            $cash->is_matched = 'true';
            $cash->upload_file = '';

            if (!$cash->update()) {
                return redirect()->route('stock.create')
                    ->with('failed_message', 'Data cash flow gagal diperbarui.');
            }
        }

        return redirect()->route('stock.index')
        ->with('success_message', 'Data berhasil diperbarui.');
    
    }

}