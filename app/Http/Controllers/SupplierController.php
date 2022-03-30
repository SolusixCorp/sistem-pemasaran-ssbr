<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Supplier;

class SupplierController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        return view('pages.supplier.index');
    }

    public function listData() {
        
        $suppliers = Supplier::orderBy('suppliers.supplier_id', 'desc')->get();
        
        $no = 0;
        $data = array();
        foreach ($suppliers as $supplier) {
            $no++;
            $row = array();
            $row[] = $no;
            $row[] = $supplier->supplier_name;
            $row[] = $supplier->supplier_address;
            $row[] = $supplier->supplier_email;
            $row[] = $supplier->supplier_phone;
            $row[] = '<a href="#" onclick="editForm(' . $supplier->supplier_id . ')" class="btn btn-warning btn-sm btn-block" data-toggle="modal"><i class="far fa-edit"></i> Edit</a>';
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
        $supplier = new Supplier;
        $supplier->supplier_name = $request['name'];
        $supplier->supplier_address = $request['address'];
        $supplier->supplier_email = $request['email'];
        $supplier->supplier_phone = $request['phone'];
        $supplier->save();

        return redirect()->route('supplier.index')
            ->with('success_message', 'Supplier berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
        $supplier = Supplier::find($id);
        echo json_encode($supplier);
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
        // $supplier = Supplier::where('supplier_id', '=', $id)->get();
        $supplier = Supplier::find($id);
        return json_encode($supplier);
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
        $supplier = Supplier::find($id);
        $supplier->supplier_name = $request['name'];
        $supplier->supplier_address = $request['address'];
        $supplier->supplier_email = $request['email'];
        $supplier->supplier_phone = $request['phone'];
        $supplier->update();
  
        return redirect()->route('supplier.index')
        ->with('success_message', 'Supplier berhasil diperbarui.');
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
