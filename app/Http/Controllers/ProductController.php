<?php

namespace App\Http\Controllers;

use App\Models\CategoryProduct;
use App\Models\Product;
use App\Models\ProductDepo;
use App\Models\Depo;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $categories = CategoryProduct::orderBy('category.category_name', 'asc')
            ->where('category.status', '=', "Aktif")
            ->select('category.id as category_id', 'category.category_name')
            ->get();

        return view('pages.product.data-product.index', compact('categories'));
    }

    public function listData() {
            $products = Product::leftJoin('category', 'category.id', '=', 'products.category_id')
                    ->select('category.category_name', 'products.id as product_id', 'products.name', 'products.consument_price' , 'products.retail_price', 'products.sub_whole_price', 'products.wholesales_price', 'products.stock')
                    ->get();

        $no = 0;
        $status = "";
        $data = array();
        foreach ($products as $products) {
            $no++;
            $row = array();
            $row[] = $products->category_name;
            $row[] = $products->name;
            $row[] = rupiah($products->consument_price, TRUE);
            $row[] = rupiah($products->retail_price, TRUE);
            $row[] = rupiah($products->sub_whole_price, TRUE);
            $row[] = rupiah($products->wholesales_price, TRUE);
            $row[] = $products->stock;
            $row[] = '<a href="#" onclick="editForm(' . $products->product_id . ')" class="btn btn-warning btn-sm" data-toggle="modal"><i class="far fa-edit"></i></a>
            <a href="#" onclick="detailsView(' . $products->product_id . ')" class="btn btn-primary btn-sm" data-toggle="modal"><i class="far fa-eye"></i></a>';
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
        $productPhoto = $request['inPhoto'];
        if ($productPhoto) {
            $photoName = time().'.' . $request->inPhoto->extension();
            $request->inPhoto->move(public_path('images'), $photoName);
        }

        $products = new Product;
        $products->name = $request['inProductName'];
        $products->category_id = $request['inCategory'];
        $products->description = $request['inDescription'];
        $products->consument_price = $request['inConsumentPrice'];
        $products->retail_price = $request['inRetailPrice'];
        $products->sub_whole_price = $request['inSubWholePrice'];
        $products->wholesales_price = $request['inWholesalesPrice'];
        $products->image = $photoName;
        $products->status = 'Aktif';

        if (!$products->save()) {
            return redirect()->route('data-product.index')
            ->with('failed_message', 'Product gagal ditambahkan.');
        }

        return redirect()->route('data-product.index')
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
        $product = Product::leftJoin('category', 'category.id', '=', 'products.category_id')
                ->select('category.category_name', 'products.name', 'products.consument_price' , 'products.retail_price', 'products.sub_whole_price', 'products.wholesales_price', 'products.stock', 'products.status', 'products.image')                    
                ->where('products.id', '=', $id)
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
        $product = Product::leftJoin('category', 'category.id', '=', 'products.category_id')
                ->select('products.category_id', 'products.name', 'products.description', 'products.consument_price' , 'products.retail_price', 'products.sub_whole_price', 'products.wholesales_price', 'products.stock', 'products.status', 'products.image')                    
                ->where('products.id', '=', $id)
                ->first();

                $image = $product->image;
                if ($image == "") {
                    $image = "Pilih Gambar...";
                }

                $productData = array(
                    'product_name' => $product->name,
                    'product_category' => $product->category_id,
                    'product_description' => $product->description,
                    'product_price_consument' => $product->consument_price,
                    'product_price_retail' => $product->retail_price,
                    'product_price_sub_whole' => $product->sub_whole_price,
                    'product_price_whole' => $product->wholesales_price,
                    'product_stock' => $product->stock,
                    'product_status' => $product->status,
                    'product_image' => $image,
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
        $products = Product::find($id);
        $products->name = $request['upProductName'];
        $products->category_id = $request['upCategory'];
        $products->description = $request['upDescription'];
        $products->consument_price = $request['upConsumentPrice'];
        $products->retail_price = $request['upRetailPrice'];
        $products->sub_whole_price = $request['upSubWholePrice'];
        $products->wholesales_price = $request['upWholesalesPrice'];
        $productPhoto = $request['upPhoto'];
        if ($productPhoto) {
            $photoName = time().'.' . $request->upPhoto->extension();
            $request->upPhoto->move(public_path('images'), $photoName);
            $products->image = $photoName;
        }
        $products->status = $request['upStatus'];
        $products->update();

        if (!$products->update()) {
            return redirect()->route('data-product.index')
                ->with('failed_message', 'Data Product gagal diperbarui.');
        }

        return redirect()->route('data-product.index')
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
