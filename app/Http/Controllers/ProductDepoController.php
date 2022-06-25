<?php

namespace App\Http\Controllers;

use App\Models\CategoryProduct;
use App\Models\Product;
use App\Models\ProductDepo;
use App\Models\Depo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProductDepoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $user = Auth::user();

        $products = Product::orderBy('name', 'asc')
                ->where('status', '=', "Aktif")
                ->select('id as product_id', 'name as product_name')
                ->get();

        if ($user->role == 'ho') {
            $depos = Depo::leftJoin('users', 'users.id', '=','depos.user_id')
                    ->select('depos.id as depo_id', 'users.name as depo_name')
                    ->get();
        } else {
            $depos = Depo::leftJoin('users', 'users.id', '=','depos.user_id')
                    ->where('user_id', '=', $user->id)
                    ->select('depos.id as depo_id', 'users.name as depo_name')
                    ->get();
        }

        $depoType = Depo::leftJoin('users', 'user_id', '=', 'users.id')
                ->where('user_id', '=', $user->id)
                ->first();

        $categories = CategoryProduct::orderBy('category.category_name', 'asc')
                    ->where('category.status', '=', "Aktif")
                    ->select('category.id as category_id', 'category.category_name')
                    ->get();

        return view('pages.product.data-product-depo.index', compact('products'), compact('depos'))
                ->with(['categories' => $categories])
                ->with(['depoType' => $depoType]);
    }

    public function listData($categoryId, $depoId, $status) {
        $user = Auth::user();
        if ($status != 0 && $categoryId != 0 && $depoId == 0) {
            if ($user->role == 'ho') {
            $products = ProductDepo::leftJoin('products', 'product_id', '=', 'products.id')
                    ->leftJoin('depos', 'depo_id', '=', 'depos.id')
                    ->leftJoin('users', 'user_id', '=', 'users.id')
                    ->leftJoin('category', 'category.id', '=', 'products.category_id')
                    ->select('category.category_name', 'products_depo.id as product_id', 'users.name as depo_name', 'products.name' , 'products.retail_price', 'products.sub_whole_price', 'products.wholesales_price', 'products_depo.depo_price' , 'products_depo.stock', 'products_depo.status')
                    ->where('products.category_id', '=', $categoryId)
                    ->where('products_depo.status', '=', $status)
                    ->where('depos.user_id', '!=', $user->id)
                    ->get();
            } else {
                $products = ProductDepo::leftJoin('products', 'product_id', '=', 'products.id')
                    ->leftJoin('depos', 'depo_id', '=', 'depos.id')
                    ->leftJoin('users', 'user_id', '=', 'users.id')
                    ->leftJoin('category', 'category.id', '=', 'products.category_id')
                    ->select('category.category_name', 'products_depo.id as product_id', 'users.name as depo_name', 'products.name' , 'products.retail_price', 'products.sub_whole_price', 'products.wholesales_price', 'products_depo.depo_price' , 'products_depo.stock', 'products_depo.status')
                    ->where('products.category_id', '=', $categoryId)
                    ->where('products_depo.status', '=', $status)
                    ->where('depos.user_id', '=', $user->id)
                    ->get();
            }
        } else if ($status != 0 && $categoryId == 0 && $depoId != 0) { 
            if ($user->role == 'ho') {
                $products = ProductDepo::leftJoin('products', 'product_id', '=', 'products.id')
                    ->leftJoin('depos', 'depo_id', '=', 'depos.id')
                    ->leftJoin('users', 'user_id', '=', 'users.id')
                    ->leftJoin('category', 'category.id', '=', 'products.category_id')
                    ->select('category.category_name', 'products_depo.id as product_id', 'users.name as depo_name', 'products.name' , 'products.retail_price', 'products.sub_whole_price', 'products.wholesales_price', 'products_depo.depo_price' , 'products_depo.stock')
                    ->where('products_depo.status', '=', $status)
                    ->where('depo_id', '=', $depoId)
                    ->where('depos.user_id', '!=', $user->id)
                    ->get(); 
            } else {
                $products = ProductDepo::leftJoin('products', 'product_id', '=', 'products.id')
                    ->leftJoin('depos', 'depo_id', '=', 'depos.id')
                    ->leftJoin('users', 'user_id', '=', 'users.id')
                    ->leftJoin('category', 'category.id', '=', 'products.category_id')
                    ->select('category.category_name', 'products_depo.id as product_id', 'users.name as depo_name', 'products.name' , 'products.retail_price', 'products.sub_whole_price', 'products.wholesales_price', 'products_depo.depo_price' , 'products_depo.stock')
                    ->where('products_depo.status', '=', $status)
                    ->where('depo_id', '=', $depoId)
                    ->where('depos.user_id', '=', $user->id)
                    ->get(); 
            }
        } else if ($status != 0 && $categoryId != 0 && $depoId != 0) {
            if ($user->role == 'ho') {
                $products = ProductDepo::leftJoin('products', 'product_id', '=', 'products.id')
                    ->leftJoin('depos', 'depo_id', '=', 'depos.id')
                    ->leftJoin('users', 'user_id', '=', 'users.id')
                    ->leftJoin('category', 'category.id', '=', 'products.category_id')
                    ->select('category.category_name', 'products_depo.id as product_id', 'users.name as depo_name', 'products.name' , 'products.retail_price', 'products.sub_whole_price', 'products.wholesales_price', 'products_depo.depo_price' , 'products_depo.stock')
                    ->where('products.category_id', '=', $categoryId)
                    ->where('products_depo.status', '=', $status)
                    ->where('depo_id', '=', $depoId)
                    ->where('depos.user_id', '!=', $user->id)
                    ->get(); 
            } else {
                $products = ProductDepo::leftJoin('products', 'product_id', '=', 'products.id')
                    ->leftJoin('depos', 'depo_id', '=', 'depos.id')
                    ->leftJoin('users', 'user_id', '=', 'users.id')
                    ->leftJoin('category', 'category.id', '=', 'products.category_id')
                    ->select('category.category_name', 'products_depo.id as product_id', 'users.name as depo_name', 'products.name' , 'products.retail_price', 'products.sub_whole_price', 'products.wholesales_price', 'products_depo.depo_price' , 'products_depo.stock')
                    ->where('products.category_id', '=', $categoryId)
                    ->where('products_depo.status', '=', $status)
                    ->where('depo_id', '=', $depoId)
                    ->where('depos.user_id', '=', $user->id)
                    ->get(); 
            }
        } else if ($status == 0 && $categoryId != 0 && $depoId == 0) {
            if ($user->role == 'ho') {
                $products = ProductDepo::leftJoin('products', 'product_id', '=', 'products.id')
                    ->leftJoin('depos', 'depo_id', '=', 'depos.id')
                    ->leftJoin('users', 'user_id', '=', 'users.id')
                    ->leftJoin('category', 'category.id', '=', 'products.category_id')
                    ->select('category.category_name', 'products_depo.id as product_id', 'users.name as depo_name', 'products.name' , 'products.retail_price', 'products.sub_whole_price', 'products.wholesales_price', 'products_depo.depo_price' , 'products_depo.stock')
                    ->where('products.category_id', '=', $categoryId)
                    ->where('depos.user_id', '!=', $user->id)
                    ->get(); 
            } else {
                $products = ProductDepo::leftJoin('products', 'product_id', '=', 'products.id')
                    ->leftJoin('depos', 'depo_id', '=', 'depos.id')
                    ->leftJoin('users', 'user_id', '=', 'users.id')
                    ->leftJoin('category', 'category.id', '=', 'products.category_id')
                    ->select('category.category_name', 'products_depo.id as product_id', 'users.name as depo_name', 'products.name' , 'products.retail_price', 'products.sub_whole_price', 'products.wholesales_price', 'products_depo.depo_price' , 'products_depo.stock')
                    ->where('products.category_id', '=', $categoryId)
                    ->where('depos.user_id', '=', $user->id)
                    ->get(); 
            }
        } else if ($status == 0 && $categoryId == 0 && $depoId != 0) { 
            if ($user->role == 'ho') {
                $products = ProductDepo::leftJoin('products', 'product_id', '=', 'products.id')
                    ->leftJoin('depos', 'depo_id', '=', 'depos.id')
                    ->leftJoin('users', 'user_id', '=', 'users.id')
                    ->leftJoin('category', 'category.id', '=', 'products.category_id')
                    ->select('category.category_name', 'products_depo.id as product_id', 'users.name as depo_name', 'products.name' , 'products.retail_price', 'products.sub_whole_price', 'products.wholesales_price', 'products_depo.depo_price' , 'products_depo.stock')
                    ->where('depo_id', '=', $depoId)
                    ->where('depos.user_id', '!=', $user->id)
                    ->get(); 
            } else {
                $products = ProductDepo::leftJoin('products', 'product_id', '=', 'products.id')
                    ->leftJoin('depos', 'depo_id', '=', 'depos.id')
                    ->leftJoin('users', 'user_id', '=', 'users.id')
                    ->leftJoin('category', 'category.id', '=', 'products.category_id')
                    ->select('category.category_name', 'products_depo.id as product_id', 'users.name as depo_name', 'products.name' , 'products.retail_price', 'products.sub_whole_price', 'products.wholesales_price', 'products_depo.depo_price' , 'products_depo.stock')
                    ->where('depo_id', '=', $depoId)
                    ->where('depos.user_id', '=', $user->id)
                    ->get(); 
            }
        } else if ($status != 0 && $categoryId == 0 && $depoId == 0) { 
            if ($user->role == 'ho') {
                $products = ProductDepo::leftJoin('products', 'product_id', '=', 'products.id')
                    ->leftJoin('depos', 'depo_id', '=', 'depos.id')
                    ->leftJoin('users', 'user_id', '=', 'users.id')
                    ->leftJoin('category', 'category.id', '=', 'products.category_id')
                    ->select('category.category_name', 'products_depo.id as product_id', 'users.name as depo_name', 'products.name' , 'products.retail_price', 'products.sub_whole_price', 'products.wholesales_price', 'products_depo.depo_price' , 'products_depo.stock')
                    ->where('products_depo.status', '=', $status)
                    ->where('depos.user_id', '!=', $user->id)
                    ->get(); 
            } else {
                $products = ProductDepo::leftJoin('products', 'product_id', '=', 'products.id')
                    ->leftJoin('depos', 'depo_id', '=', 'depos.id')
                    ->leftJoin('users', 'user_id', '=', 'users.id')
                    ->leftJoin('category', 'category.id', '=', 'products.category_id')
                    ->select('category.category_name', 'products_depo.id as product_id', 'users.name as depo_name', 'products.name' , 'products.retail_price', 'products.sub_whole_price', 'products.wholesales_price', 'products_depo.depo_price' , 'products_depo.stock')
                    ->where('products_depo.status', '=', $status)
                    ->where('depos.user_id', '=', $user->id)
                    ->get(); 
            }
                
        } else if ($status == 0 && $categoryId != 0 && $depoId != 0) { 
            if ($user->role == 'ho') {
                $products = ProductDepo::leftJoin('products', 'product_id', '=', 'products.id')
                        ->leftJoin('depos', 'depo_id', '=', 'depos.id')
                        ->leftJoin('users', 'user_id', '=', 'users.id')
                        ->leftJoin('category', 'category.id', '=', 'products.category_id')
                        ->select('category.category_name', 'products_depo.id as product_id', 'users.name as depo_name', 'products.name' , 'products.retail_price', 'products.sub_whole_price', 'products.wholesales_price', 'products_depo.depo_price' , 'products_depo.stock')
                        ->where('products.category_id', '=', $categoryId)
                        ->where('depo_id', '=', $depoId)
                        ->where('depos.user_id', '!=', $user->id)
                        ->get();
            } else {
                $products = ProductDepo::leftJoin('products', 'product_id', '=', 'products.id')
                        ->leftJoin('depos', 'depo_id', '=', 'depos.id')
                        ->leftJoin('users', 'user_id', '=', 'users.id')
                        ->leftJoin('category', 'category.id', '=', 'products.category_id')
                        ->select('category.category_name', 'products_depo.id as product_id', 'users.name as depo_name', 'products.name' , 'products.retail_price', 'products.sub_whole_price', 'products.wholesales_price', 'products_depo.depo_price' , 'products_depo.stock')
                        ->where('products.category_id', '=', $categoryId)
                        ->where('depo_id', '=', $depoId)
                        ->where('depos.user_id', '=', $user->id)
                        ->get(); 
            }
        } else {
            if ($user->role == 'ho') {
                $products = ProductDepo::leftJoin('products', 'product_id', '=', 'products.id')
                    ->leftJoin('depos', 'depo_id', '=', 'depos.id')
                    ->leftJoin('users', 'depo_id', '=', 'users.id')
                    ->leftJoin('category', 'category.id', '=', 'products.category_id')
                    ->select('category.category_name', 'products_depo.id as product_id', 'users.name as depo_name', 'products.name' , 'products.retail_price', 'products.sub_whole_price', 'products.wholesales_price', 'products_depo.depo_price' , 'products_depo.stock')
                    ->where('depos.user_id', '!=', $user->id)
                    ->get(); 
            } else {
                $products = ProductDepo::leftJoin('products', 'product_id', '=', 'products.id')
                    ->leftJoin('depos', 'depo_id', '=', 'depos.id')
                    ->leftJoin('users', 'depo_id', '=', 'users.id')
                    ->leftJoin('category', 'category.id', '=', 'products.category_id')
                    ->select('category.category_name', 'products_depo.id as product_id', 'users.name as depo_name', 'products.name' , 'products.retail_price', 'products.sub_whole_price', 'products.wholesales_price', 'products_depo.depo_price' , 'products_depo.stock')
                    ->where('depos.user_id', '=', $user->id)
                    ->get(); 
            }
                
        }

        $no = 0;
        $status = "";
        $data = array();
        foreach ($products as $products) {
            $no++;
            $row = array();
            $row[] = $products->category_name;
            $row[] = $products->name;
            $row[] = $products->depo_name;
            $row[] = rupiah($products->depo_price, TRUE);
            $row[] = $products->stock;
            $edit = '<a href="#" onclick="editForm(' . $products->product_id . ')" class="btn btn-warning btn-sm" data-toggle="modal"><i class="far fa-edit"></i></a>';
            $view = '<a href="#" onclick="detailsView(' . $products->product_id . ')" class="btn btn-primary btn-sm" data-toggle="modal"><i class="far fa-eye"></i></a>';
            if ($user->type == 'ho') {
                $row[] = $edit . $view;
            } else {
                $row[] = $view;
            }
            $row[] = 
            $data[] = $row;
        }

        $output = array("data" => $data);
        return response()->json($output);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
        $productId = $request['inProduct'];
        $depoId = $request['inDepo'];

        $productCheck = ProductDepo::where('product_id', '=', $productId)
                ->where('depo_id', '=', $depoId)
                ->first();

        if ($productCheck != null) {
            return redirect()->route('data-product-depo.index')
            ->with('failed_message', 'Product sudah ada.');
        }

        $products = new ProductDepo;
        $products->product_id = $request['inProduct'];
        $products->depo_id = $request['inDepo'];
        $products->depo_price = $request['inDepoPrice'];
        $products->status = 'Aktif';

        if (!$products->save()) {
            return redirect()->route('data-product-depo.index')
            ->with('failed_message', 'Product gagal ditambahkan.');
        }

        return redirect()->route('data-product-depo.index')
            ->with('success_message', 'Product berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $product = ProductDepo::leftJoin('products', 'product_id', '=', 'products.id')
                ->leftJoin('category', 'category.id', '=', 'products.category_id')
                ->select('category.category_name', 'products_depo.id as product_id', 'products.name', 'products.consument_price', 'products.retail_price', 'products.sub_whole_price', 'products.wholesales_price', 'products.image', 'products_depo.status', 'products_depo.depo_price', 'products_depo.stock')
                ->where('products_depo.id', '=', $id)
                ->first();

                $image = $product->image;
                if ($image == "") {
                    $image = "https://via.placeholder.com/600x350";
                } else {
                    $image = "images/" . $product->image;
                }

                $productData = array(
                    'product_name' => $product->name,
                    'product_category' => $product->category_name,
                    'product_price_consument' => rupiah($product->consument_price, TRUE),
                    'product_price_retail' => rupiah($product->retail_price, TRUE),
                    'product_price_sub_whole' => rupiah($product->sub_whole_price, TRUE),
                    'product_price_whole' => rupiah($product->wholesales_price, TRUE),
                    'product_price_depo' => rupiah($product->depo_price, TRUE),
                    'product_stock' => $product->stock,
                    'product_status' => $product->status,
                    'product_image' => $image,
                );
        
        echo json_encode($productData);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
        $product = ProductDepo::leftJoin('products', 'product_id', '=', 'products.id')
                ->leftJoin('category', 'category.id', '=', 'products.category_id')
                ->select('category.category_name', 'products.name' , 'products.retail_price', 'products.sub_whole_price', 'products.wholesales_price', 'products.image', 'products_depo.status', 'products_depo.depo_price', 'products_depo.stock')
                ->where('products_depo.id', '=', $id)
                ->first();

                $productData = array(
                    'product_name' => $product->name,
                    'depo_price' => $product->depo_price,
                    'product_status' => $product->status
                );
        echo json_encode($productData);
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
        //
        $products = ProductDepo::find($id);
        $products->depo_price = $request['upDepoPrice'];
        $products->status = $request['upStatus'];
        $products->update();

        if (!$products->update()) {
            return redirect()->route('data-product-depo.index')
                ->with('failed_message', 'Data Product gagal diperbarui.');
        }

        return redirect()->route('data-product-depo.index')
                ->with('success_message', 'Data Product berhasil diperbarui.');
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
}
