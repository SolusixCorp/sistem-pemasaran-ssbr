<?php

namespace App\Http\Controllers;

use App\Models\CategoryBarang;
use App\Models\Barang;
use App\Models\Supplier;
use Illuminate\Http\Request;

class BarangController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $categories = CategoryBarang::orderBy('category_barang.category_name', 'asc')
            ->where('category_barang.status', '=', "Aktif")->get();
        $suppliers = Supplier::orderBy('suppliers.supplier_name', 'asc')->get();
        return view('pages.barang.data-barang.index', compact('categories'), compact('suppliers'));
    }

    public function listData($categoryId, $supplierId, $status) {
     
        if ($status != 0 && $categoryId != 0 && $supplierId == 0) {
            $barang = Barang::leftJoin('category_barang', 'category_barang.category_id', '=', 'barangs.category_id')
            ->leftJoin('suppliers', 'suppliers.supplier_id', '=', 'barangs.supplier_id')
            ->where('barangs.category_id', '=', $categoryId)
            ->where('barangs.status', '=', $status)
            ->get();
        } else if ($status != 0 && $categoryId == 0 && $supplierId != 0) { 
            $barang = Barang::leftJoin('category_barang', 'category_barang.category_id', '=', 'barangs.category_id')
            ->leftJoin('suppliers', 'suppliers.supplier_id', '=', 'barangs.supplier_id')
            ->where('suppliers.supplier_id', '=', $supplierId)
            ->where('barangs.status', '=', $status)
            ->get(); 
        } else if ($status != 0 && $categoryId != 0 && $supplierId != 0) {
            $barang = Barang::leftJoin('category_barang', 'category_barang.category_id', '=', 'barangs.category_id')
            ->leftJoin('suppliers', 'suppliers.supplier_id', '=', 'barangs.supplier_id')
            ->where('barangs.category_id', '=', $categoryId)
            ->where('barangs.status', '=', $status)
            ->where('suppliers.supplier_id', '=', $supplierId)
            ->get(); 
        } else if ($status == 0 && $categoryId != 0 && $supplierId == 0) {
            $barang = Barang::leftJoin('category_barang', 'category_barang.category_id', '=', 'barangs.category_id')
            ->leftJoin('suppliers', 'suppliers.supplier_id', '=', 'barangs.supplier_id')
            ->where('barangs.category_id', '=', $categoryId)
            ->get(); 
        } else if ($status == 0 && $categoryId == 0 && $supplierId != 0) { 
            $barang = Barang::leftJoin('category_barang', 'category_barang.category_id', '=', 'barangs.category_id')
            ->leftJoin('suppliers', 'suppliers.supplier_id', '=', 'barangs.supplier_id')
            ->where('suppliers.supplier_id', '=', $supplierId)
            ->get(); 
        } else if ($status != 0 && $categoryId == 0 && $supplierId == 0) { 
            $barang = Barang::leftJoin('category_barang', 'category_barang.category_id', '=', 'barangs.category_id')
            ->leftJoin('suppliers', 'suppliers.supplier_id', '=', 'barangs.supplier_id')
            ->where('barangs.status', '=', $status)
            ->get(); 
        } else if ($status == 0 && $categoryId != 0 && $supplierId != 0) { 
            $barang = Barang::leftJoin('category_barang', 'category_barang.category_id', '=', 'barangs.category_id')
            ->leftJoin('suppliers', 'suppliers.supplier_id', '=', 'barangs.supplier_id')
            ->where('barangs.category_id', '=', $categoryId)
            ->where('suppliers.supplier_id', '=', $supplierId)
            ->get(); 
        } else {
            $barang = Barang::leftJoin('category_barang', 'category_barang.category_id', '=', 'barangs.category_id')
            ->leftJoin('suppliers', 'suppliers.supplier_id', '=', 'barangs.supplier_id')->get();
        }

        $no = 0;
        $status = "";
        $data = array();
        foreach ($barang as $barang) {
            $no++;
            $row = array();
            $row[] = $barang->category_name;
            $row[] = $barang->supplier_name;
            $row[] = $barang->name;
            $row[] = $barang->merk;
            $row[] = rupiah($barang->selling_price, TRUE);
            $row[] = rupiah($barang->buying_price, TRUE);
            $row[] = $barang->stock;
            $row[] = '<a href="#" onclick="editForm(' . $barang->barang_id . ')" class="btn btn-warning btn-sm btn-block" data-toggle="modal"><i class="far fa-edit"></i> Edit</a>
            <a href="#" onclick="detailsView(' . $barang->barang_id . ')" class="btn btn-primary btn-sm btn-block" data-toggle="modal"><i class="far fa-eye"></i> Details</a>';
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
        $barang = new Barang;
        $barang->name = $request['name'];
        $barang->supplier_id = $request['inputSupplier'];
        $barang->category_id = $request['inputCategory'];
        $barang->merk = $request['merk'];
        $barang->selling_price = $request['sellingPrice'];
        $barang->buying_price = $request['buyingPrice'];
        $barang->discount = $request['discount'] || 0;
        $barang->discount_type = $request['discountType'] || "";
        $barang->stock = $request['stock'];
        $barang->status = $request['inputStatus'];
        $barang->save();

        return redirect()->route('data-barang.index')
            ->with('success_message', 'Barang berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $barang = Barang::leftJoin('category_barang', 'category_barang.category_id', '=', 'barangs.category_id')
        ->leftJoin('suppliers', 'suppliers.supplier_id', '=', 'barangs.supplier_id')
        ->where('barangs.barang_id', '=', $id)
        ->first(); 
        $barang->selling_price = rupiah($barang->selling_price, TRUE);
        $barang->buying_price  = rupiah($barang->buying_price, TRUE);
        if ($barang->discount_type != "%") {
            $barang->discount  = rupiah($barang->discount, TRUE);
        } else {
            $barang->discount  = $barang->discount ."". $barang->discount_type;
        }
        
        echo json_encode($barang);
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
        $barang = Barang::find($id);
        echo json_encode($barang);
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
        $barang = Barang::find($id);
        $barang->name = $request['name'];
        $barang->supplier_id = $request['inputSupplier'];
        $barang->category_id = $request['inputCategory'];
        $barang->merk = $request['merk'];
        $barang->selling_price = $request['sellingPrice'];
        $barang->buying_price = $request['buyingPrice'];
        $barang->discount = $request['discount'] || 0;
        $barang->discount_type = $request['discountType'] || "";
        $barang->stock = $request['stock'];
        $barang->status = $request['inputStatus'];
        $barang->update();

        if (!$barang->update()) {
            return redirect()->route('data-barang.index')
                ->with('failed_message', 'Data Barang gagal diperbarui.');
        }

        return redirect()->route('data-barang.index')
                ->with('success_message', 'Data Barang berhasil diperbarui.');
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
