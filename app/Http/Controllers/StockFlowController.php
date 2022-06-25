<?php

namespace App\Http\Controllers;

use App\Models\CategoryProduct;
use App\Models\Product;
use App\Models\ProductDepo;
use App\Models\Depo;
use App\Models\Stock;
use App\Models\StockFlow;
use App\Models\CashFlow;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Exception;
use DB;

use Illuminate\Http\Request;

class StockFlowController extends Controller
{
    public function index() {
        $user = Auth::user();

        $categories = CategoryProduct::orderBy('category.category_name', 'asc')->get();
        $depos = Depo::leftJoin('users', 'user_id', '=', 'users.id')->get();
        
        $endDate = Carbon::now()->format('Y-m-d');
        $startDate = Carbon::now()->format('Y-m-d');

        $data = array(
            'startDate' => $startDate,
            'endDate' => $endDate,
        );

        return view('pages.stock.index', [
            "categories" => $categories,
            "depos"  => $depos,
            "data"  => $data    
        ]);
    }

    public function getAllData($start, $end) {
        $startDate = date('Y-m-d', strtotime($start));
        $endDate = date('Y-m-d', strtotime($end . "+1 days"));

        $user = Auth::user();
        $depo = Depo::where('user_id', '=', $user->id)->first();

        if ($user->role == 'ho') {
            $stocks =  StockFlow::leftJoin('depos', 'depo_id', '=', 'depos.id')
                    ->leftJoin('users', 'depos.user_id', '=', 'users.id')
                    ->select('stock_flow.id as stock_id', 'stock_flow.input_date', 'depo_id', 'is_delivered', 'name', 'stock_type', 'stockin_category', 'stockout_category', DB::raw('sum(qty) as qty'))
                    ->whereBetween('input_date', [$startDate . '%', $endDate . '%'])
                    ->groupBy('input_date')
                    ->groupBy('depo_id')
                    ->orderBy('stock_flow.input_date', 'desc')
                    ->get(); 
        } else {
            $stocks =  StockFlow::leftJoin('depos', 'depo_id', '=', 'depos.id')
                    ->leftJoin('users', 'depos.user_id', '=', 'users.id')
                    ->select('stock_flow.id as stock_id', 'stock_flow.input_date', 'depo_id', 'is_delivered', 'name', 'stock_type', 'stockin_category', 'stockout_category', DB::raw('sum(qty) as qty'))
                    ->whereBetween('input_date', [$startDate . '%', $endDate . '%'])
                    ->groupBy('input_date')
                    ->where('depos.user_id', '=', $user->id)
                    ->orderBy('stock_flow.input_date', 'desc')
                    ->get(); 
        }
       
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

            $btnConfirm = 'btn-secondary';
            $onClickConfirm = 'confirmView(' . dateToNumber($stock->input_date) . ', '. $stock->depo_id .')';
            if ($stock->depo_id == $depo->id) {
                if ($stock->stock_type == 'in') {
                    $btnConfirm = 'btn-outline-success';
                } else {
                    $btnConfirm = 'btn-success';
                    $onClickConfirm = '';
                }
            } else {
                $btnConfirm = 'btn-secondary';
                $onClickConfirm = '';
            } 

            if ($stock->is_delivered == 1) {
                $btnConfirm = 'btn-success';
                $onClickConfirm = '';
            }

            $confirm = '<a href="#" onclick="' . $onClickConfirm .'" class="btn ' . $btnConfirm . ' btn-sm" data-toggle="modal"><i class="fas fa-check"></i></a>'; 
            $edit = '<a href="'. url("/") .'/stock/edit/' . dateToNumber($stock->input_date) . '/'. $stock->depo_id. '" onclick="editForm(' . $stock->stock_id . ')" class="btn btn-warning btn-sm"><i class="far fa-edit"></i></a>';
            $details = '<a href="#" onclick="detailsView(' . dateToNumber($stock->input_date) . ', '. $stock->depo_id .')" class="btn btn-primary btn-sm" data-toggle="modal"><i class="far fa-eye"></i></a>'; 
            if ($depo->id == $stock->depo_id) {
                $row[] = $confirm . ' ' .$details . ' ' . $edit;
            } else {
                $row[] = $confirm . ' ' . $details;
            }
            
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

    public function getByDate($date, $depoId) {
        $stockDatas =  StockFlow::with('depo')
                    ->where('input_date', '=', numberToDate($date))
                    ->where('depo_id', '=', $depoId)
                    ->get();

        $depo = Depo::with('user')->where('id', '=', $depoId)->first();

        $products = array();
        foreach($stockDatas as $stockData) { 
            if ($depo->user->role == 'ho') {
                $product = Product::find($stockData->product_id);
                $productName = $product->name;
            } else {
                $product = ProductDepo::with('product')->find($stockData->product_id);
                $productName = $product->product->name;
            }
            $products[] = array(
                'product_name' => $productName,
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
    
    public function confirm($date)
    {
        $user = Auth::user();
        $depo = Depo::with('user')->where('id', '=', $user->id)->first();
        $stockDatas =  StockFlow::with('depo')
                ->where('input_date', '=', numberToDate($date))
                ->where('depo_id', '=', $depo->id)
                ->get();

        foreach ($stockDatas as $stock) {
            $stock->is_delivered = 1;
            if (!$stock->update()) {
                return redirect()->route('stock.index')
                    ->with('failed_message', 'Konfirmasi gagal.');
            }   
        }

        return redirect()->route('stock.index')
            ->with('success_message', 'Konfirmasi berhasil.');
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
            $depos = Depo::leftJoin('users', 'user_id', '=', 'users.id')
                    ->where('user_id', '!=', $user->id)
                    ->get();
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

        $depo = Depo::where('user_id', '=', $user->id)->first();
        
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

                $productDepo = ProductDepo::leftJoin('products', 'product_id', '=', 'products.id')
                    ->select('products_depo.id', 'products_depo.product_id', 'products.name', 'products_depo.stock', 'products_depo.depo_price')
                    ->where('product_id', '=', (int) $item)
                    ->first();
                    
                $stock = new StockFlow;
                $stock->depo_id = (int) $depo->id;
                $stock->product_id = (int) $product->id;
                if ($stock->stock == null) {
                    $stock->stock = (int) $qty_items[$index];
                } else {
                    $stock->stock += (int) $qty_items[$index];
                }
                if ($in_date != null && $in_time != null) {
                    $stock->input_date = $in_date . " " . $in_time . date(":s", time());
                }
                $stock->stock_type = $in_stock_type;
                $stock->qty = (int) $qty_items[$index];
        
                $position = strpos($price_items[$index], ' (');
                $price = substr($price_items[$index], 0 , $position);
                $stock->price_type = priceType($price_items[$index]);
                $stock->price = (float) rupiahNumber($price_items[$index]);
                
                if ($in_stock_type == 'in') {
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

                    $stockDepo = new StockFlow;
                    $stockDepo->depo_id = (int) $in_depo;
                    $stockDepo->product_id = (int) $productDepo->id;
                    if ($stockDepo->stock == null) {
                        $stockDepo->stock = (int) $qty_items[$index];
                    } else {
                        $stockDepo->stock += (int) $qty_items[$index];
                    }
                    if ($in_date != null && $in_time != null) {
                        $stockDepo->input_date = $in_date . " " . $in_time . date(":s", time());
                    }
                    $stockDepo->stock_type = 'in';
                    $stockDepo->qty = (int) $qty_items[$index];
            
                    $position = strpos($price_items[$index], ' (');
                    $price = substr($price_items[$index], 0 , $position);
                    $stockDepo->price_type = priceType($price_items[$index]);
                    $stockDepo->price = (float) rupiahNumber($price_items[$index]);
                    $stockDepo->stockout_category = '';
                    $stockDepo->stockin_category = 'dropping';
                    
                    $productDepo->stock += (int) $qty_items[$index];

                    $stockDepo->remaining_stock = (int) $productDepo->stock;

                    if (!$stockDepo->save()) {
                        return redirect()->route('stock.create')
                            ->with('failed_message', 'Data stock flow gagal disimpan.');
                    }

                    if (!$productDepo->update()) {
                        return redirect()->route('stock.create')
                            ->with('failed_message', 'Data stock flow gagal disimpan.');
                    }
                }

                $stock->remaining_stock = (int) $product->stock;

                $total_amount += (float) rupiahNumber($price_items[$index]) * (int) $qty_items[$index];

                if (!$stock->save()) {
                    return redirect()->route('stock.create')
                        ->with('failed_message', 'Data stock flow gagal disimpan.');
                }

                if (!$product->update()) {
                    return redirect()->route('stock.create')
                        ->with('failed_message', 'Data stock flow gagal disimpan.');
                }
                    
            } else {
                $product = ProductDepo::leftJoin('products', 'product_id', '=', 'products.id')
                    ->select('products_depo.id', 'products_depo.product_id', 'products.name', 'products_depo.stock', 'products_depo.depo_price')
                    ->where('products_depo.id', '=', (int) $item)
                    ->first(); 

                if ($product->stock < $qty_items[$index]) {
                    return redirect()->route('stock.create')
                    ->with('failed_message', 'Stock '. $product->name .' tidak cukup');
                }

                $stock = new StockFlow;
                $stock->depo_id = (int) $depo->id;
                $stock->product_id = (int) $product->id;
                if ($stock->stock == null) {
                    $stock->stock = (int) $qty_items[$index];
                } else {
                    $stock->stock += (int) $qty_items[$index];
                }
                if ($in_date != null && $in_time != null) {
                    $stock->input_date = $in_date . " " . $in_time . date(":s", time());
                }
                $stock->stock_type = $in_stock_type;
                $stock->qty = (int) $qty_items[$index];
        
                $position = strpos($price_items[$index], ' (');
                $price = substr($price_items[$index], 0 , $position);
                $stock->price_type = priceType($price_items[$index]);
                $stock->price = (float) rupiahNumber($price_items[$index]);

                $product->stock -= (int) $qty_items[$index];
                
                $stock->remaining_stock = (int) $product->stock;

                if (!$stock->save()) {
                    return redirect()->route('stock.create')
                        ->with('failed_message', 'Data stock flow gagal disimpan.');
                }

                if (!$product->update()) {
                    return redirect()->route('stock.create')
                        ->with('failed_message', 'Data prouct HO flow gagal disimpan.');
                }

                if ($in_stock_type == 'out') {
                    $cash = new CashFlow;
                    $cash->depo_id = $in_depo;
                    if ($in_date != null && $in_time != null) {
                        $cash->input_date = $in_date . " " . $in_time . date(":s", time());
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
    public function edit($id, $depoId)
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
                    ->where('depo_id', '=', $depoId)
                    ->get();

        $products = array();
        foreach($stocks as $stock) { 
            if ($depo->user->role == 'ho') {
                $product = Product::find($stock->product_id);
                $productName = $product->name;
            } else {
                $product = ProductDepo::with('product')->find($stock->product_id);
                $productName = $product->product->name;
            }
            $products[] = array(
                'product_id' => $stock->product_id,
                'product_name' => $productName,
                'qty' => $stock->qty,
                'remaining_stock' => $product->stock,
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

        $depo = Depo::where('user_id', '=', $user->id)->first();
        
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

                $productDepo = ProductDepo::leftJoin('products', 'product_id', '=', 'products.id')
                    ->select('products_depo.id', 'products_depo.product_id', 'products.name', 'products_depo.stock', 'products_depo.depo_price')
                    ->where('product_id', '=', (int) $item)
                    ->first();
                    
                $stock = StockFlow::where('input_date', '=', $date)
                    ->where('depo_id', '=', $depo->id)->first();

                $stock->depo_id = (int) $depo->id;
                $stock->product_id = (int) $product->id;
                if ($stock->stock == null) {
                    $stock->stock = (int) $qty_items[$index];
                } else {
                    $stock->stock += (int) $qty_items[$index];
                }
                if ($in_date != null && $in_time != null) {
                    $stock->input_date = $in_date . " " . $in_time . date(":s", time());
                }
                $stock->stock_type = $in_stock_type;
                $stock->qty = (int) $qty_items[$index];
        
                $position = strpos($price_items[$index], ' (');
                $price = substr($price_items[$index], 0 , $position);
                $stock->price_type = priceType($price_items[$index]);
                $stock->price = (float) rupiahNumber($price_items[$index]);
                
                if ($in_stock_type == 'in') {
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

                    $diff = 0;
                    if ($stock->qty > (int) $qty_items[$index]) {
                        $diff = $stock->qty - (int) $qty_items[$index];
                        $product->stock -= $diff;
                    } else if ($stock->qty < (int) $qty_items[$index]) { 
                        $diff = (int) $qty_items[$index] - $stock->qty;
                        $product->stock += $diff;
                    }

                    $stockDepo = StockFlow::where('input_date', '=', $date)
                        ->where('depo_id', '!=', $depo->id)->first();

                    $stockDepo->depo_id = (int) $stockDepo->depo_id;
                    $stockDepo->product_id = (int) $productDepo->id;
                    if ($stockDepo->stock == null) {
                        $stockDepo->stock = (int) $qty_items[$index];
                    } else {
                        $stockDepo->stock += (int) $qty_items[$index];
                    }
                    if ($in_date != null && $in_time != null) {
                        $stockDepo->input_date = $in_date . " " . $in_time . date(":s", time());;
                    }
                    $stockDepo->stock_type = 'in';
                    $stockDepo->qty = (int) $qty_items[$index];
            
                    $position = strpos($price_items[$index], ' (');
                    $price = substr($price_items[$index], 0 , $position);
                    $stockDepo->price_type = priceType($price_items[$index]);
                    $stockDepo->price = (float) rupiahNumber($price_items[$index]);
                    $stockDepo->stockout_category = '';
                    $stockDepo->stockin_category = 'dropping';
                    
                    $diff = 0;
                    if ($stockDepo->qty > (int) $qty_items[$index]) {
                        $diff = $stockDepo->qty - (int) $qty_items[$index];
                        $productDepo->stock -= $diff;
                    } else if ($stockDepo->qty < (int) $qty_items[$index]) { 
                        $diff = (int) $qty_items[$index] - $stockDepo->qty;
                        $productDepo->stock += $diff;
                    }

                    $stockDepo->remaining_stock = (int) $productDepo->stock;

                    if (!$stockDepo->update()) {
                        return redirect()->route('stock.create')
                            ->with('failed_message', 'Data stock flow gagal disimpan.');
                    }

                    if (!$productDepo->update()) {
                        return redirect()->route('stock.create')
                            ->with('failed_message', 'Data stock flow gagal disimpan.');
                    }
                }

                $stock->remaining_stock = (int) $product->stock;

                $total_amount += (float) rupiahNumber($price_items[$index]) * (int) $qty_items[$index];

                if (!$stock->update()) {
                    return redirect()->route('stock.create')
                        ->with('failed_message', 'Data stock flow gagal disimpan.');
                }

                if (!$product->update()) {
                    return redirect()->route('stock.create')
                        ->with('failed_message', 'Data stock flow gagal disimpan.');
                }
                    
            } else {
                $product = ProductDepo::leftJoin('products', 'product_id', '=', 'products.id')
                    ->select('products_depo.id', 'products_depo.product_id', 'products.name', 'products_depo.stock', 'products_depo.depo_price')
                    ->where('products_depo.id', '=', (int) $item)
                    ->first(); 

                if ($product->stock < $qty_items[$index]) {
                    return redirect()->route('stock.create')
                    ->with('failed_message', 'Stock '. $product->name .' tidak cukup');
                }

                $stock = StockFlow::where('input_date', '=', $date)
                    ->where('depo_id', '=', $depo->id)->first();
                $stock->depo_id = (int) $depo->id;
                $stock->product_id = (int) $product->id;
                if ($stock->stock == null) {
                    $stock->stock = (int) $qty_items[$index];
                } else {
                    $stock->stock += (int) $qty_items[$index];
                }
                if ($in_date != null && $in_time != null) {
                    $stock->input_date = $in_date . " " . $in_time . date(":s", time());;
                }
                $stock->stock_type = $in_stock_type;
                $stock->qty = (int) $qty_items[$index];
        
                $position = strpos($price_items[$index], ' (');
                $price = substr($price_items[$index], 0 , $position);
                $stock->price_type = priceType($price_items[$index]);
                $stock->price = (float) rupiahNumber($price_items[$index]);

                $diff = 0;
                if ($stock->qty > (int) $qty_items[$index]) {
                    $diff = $stock->qty - (int) $qty_items[$index];
                    $product->stock -= $diff;
                } else if ($stock->qty < (int) $qty_items[$index]) { 
                    $diff = (int) $qty_items[$index] - $stock->qty;
                    $product->stock += $diff;
                }
                
                $stock->remaining_stock = (int) $product->stock;

                if (!$stock->update()) {
                    return redirect()->route('stock.create')
                        ->with('failed_message', 'Data stock flow gagal disimpan.');
                }

                if (!$product->update()) {
                    return redirect()->route('stock.create')
                        ->with('failed_message', 'Data prouct HO flow gagal disimpan.');
                }

                if ($in_stock_type == 'out') {
                    $cash = CashFlow::where('input_date', '=', $date)->first();
                    if ($cash != null) {
                        $cash->depo_id = $in_depo;
                        if ($in_date != null && $in_time != null) {
                            $cash->input_date = $in_date . " " . $in_time . date(":s", time());
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
                }
            }
        }
        return redirect()->route('stock.index')
        ->with('success_message', 'Data berhasil diperbarui.');
    
    }

}