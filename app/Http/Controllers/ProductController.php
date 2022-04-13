<?php

namespace App\Http\Controllers;

use App\Models\CategoryProduct;
use App\Models\Product;
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
        $categories = CategoryProduct::orderBy('category_barang.category_name', 'asc')
            ->where('category_barang.status', '=', "Aktif")->get();
        $suppliers = Depo::orderBy('suppliers.supplier_name', 'asc')->get();
        return view('pages.product.data-product.index', compact('categories'), compact('suppliers'));
    }

    public function listData($categoryId, $supplierId, $status) {
     
        if ($status != 0 && $categoryId != 0 && $supplierId == 0) {
            $products = Product::leftJoin('category_barang', 'category_barang.category_id', '=', 'barangs.category_id')
            ->leftJoin('suppliers', 'suppliers.supplier_id', '=', 'barangs.supplier_id')
            ->where('barangs.category_id', '=', $categoryId)
            ->where('barangs.status', '=', $status)
            ->get();
        } else if ($status != 0 && $categoryId == 0 && $supplierId != 0) { 
            $products = Product::leftJoin('category_barang', 'category_barang.category_id', '=', 'barangs.category_id')
            ->leftJoin('suppliers', 'suppliers.supplier_id', '=', 'barangs.supplier_id')
            ->where('suppliers.supplier_id', '=', $supplierId)
            ->where('barangs.status', '=', $status)
            ->get(); 
        } else if ($status != 0 && $categoryId != 0 && $supplierId != 0) {
            $products = Product::leftJoin('category_barang', 'category_barang.category_id', '=', 'barangs.category_id')
            ->leftJoin('suppliers', 'suppliers.supplier_id', '=', 'barangs.supplier_id')
            ->where('barangs.category_id', '=', $categoryId)
            ->where('barangs.status', '=', $status)
            ->where('suppliers.supplier_id', '=', $supplierId)
            ->get(); 
        } else if ($status == 0 && $categoryId != 0 && $supplierId == 0) {
            $products = Product::leftJoin('category_barang', 'category_barang.category_id', '=', 'barangs.category_id')
            ->leftJoin('suppliers', 'suppliers.supplier_id', '=', 'barangs.supplier_id')
            ->where('barangs.category_id', '=', $categoryId)
            ->get(); 
        } else if ($status == 0 && $categoryId == 0 && $supplierId != 0) { 
            $products = Product::leftJoin('category_barang', 'category_barang.category_id', '=', 'barangs.category_id')
            ->leftJoin('suppliers', 'suppliers.supplier_id', '=', 'barangs.supplier_id')
            ->where('suppliers.supplier_id', '=', $supplierId)
            ->get(); 
        } else if ($status != 0 && $categoryId == 0 && $supplierId == 0) { 
            $products = Product::leftJoin('category_barang', 'category_barang.category_id', '=', 'barangs.category_id')
            ->leftJoin('suppliers', 'suppliers.supplier_id', '=', 'barangs.supplier_id')
            ->where('barangs.status', '=', $status)
            ->get(); 
        } else if ($status == 0 && $categoryId != 0 && $supplierId != 0) { 
            $products = Product::leftJoin('category_barang', 'category_barang.category_id', '=', 'barangs.category_id')
            ->leftJoin('suppliers', 'suppliers.supplier_id', '=', 'barangs.supplier_id')
            ->where('barangs.category_id', '=', $categoryId)
            ->where('suppliers.supplier_id', '=', $supplierId)
            ->get(); 
        } else {
            $products = Product::leftJoin('category_barang', 'category_barang.category_id', '=', 'barangs.category_id')
            ->leftJoin('suppliers', 'suppliers.supplier_id', '=', 'barangs.supplier_id')->get();
        }

        $no = 0;
        $status = "";
        $data = array();
        foreach ($products as $products) {
            $no++;
            $row = array();
            $row[] = $products->category_name;
            $row[] = $products->name;
            $row[] = rupiah($products->selling_price, TRUE);
            $row[] = rupiah($products->buying_price, TRUE);
            $row[] = rupiah($products->buying_price, TRUE);
            $row[] = rupiah($products->buying_price, TRUE);
            $row[] = $products->stock;
            $row[] = '<a href="#" onclick="editForm(' . $products->barang_id . ')" class="btn btn-warning btn-sm" data-toggle="modal"><i class="far fa-edit"></i></a>
            <a href="#" onclick="detailsView(' . $products->barang_id . ')" class="btn btn-primary btn-sm" data-toggle="modal"><i class="far fa-eye"></i></a>';
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
        $products = new Product;
        $products->name = $request['name'];
        $products->supplier_id = $request['inputSupplier'];
        $products->category_id = $request['inputCategory'];
        $products->merk = $request['merk'];
        $products->selling_price = $request['sellingPrice'];
        $products->buying_price = $request['buyingPrice'];
        $products->discount = $request['discount'] || 0;
        $products->discount_type = $request['discountType'] || "";
        $products->stock = $request['stock'];
        $products->status = $request['inputStatus'];
        $products->save();

        return redirect()->route('data-Product.index')
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
        $products = Product::leftJoin('category_barang', 'category_barang.category_id', '=', 'barangs.category_id')
        ->leftJoin('suppliers', 'suppliers.supplier_id', '=', 'barangs.supplier_id')
        ->where('barangs.barang_id', '=', $id)
        ->first(); 
        $products->selling_price = rupiah($products->selling_price, TRUE);
        $products->buying_price  = rupiah($products->buying_price, TRUE);
        if ($products->discount_type != "%") {
            $products->discount  = rupiah($products->discount, TRUE);
        } else {
            $products->discount  = $products->discount ."". $products->discount_type;
        }
        
        echo json_encode($products);
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
        $products = Product::find($id);
        echo json_encode($products);
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
        $products->name = $request['name'];
        $products->supplier_id = $request['inputSupplier'];
        $products->category_id = $request['inputCategory'];
        $products->merk = $request['merk'];
        $products->selling_price = $request['sellingPrice'];
        $products->buying_price = $request['buyingPrice'];
        $products->discount = $request['discount'] || 0;
        $products->discount_type = $request['discountType'] || "";
        $products->stock = $request['stock'];
        $products->status = $request['inputStatus'];
        $products->update();

        if (!$products->update()) {
            return redirect()->route('data-Product.index')
                ->with('failed_message', 'Data Product gagal diperbarui.');
        }

        return redirect()->route('data-Product.index')
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
