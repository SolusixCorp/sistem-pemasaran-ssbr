<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Customer;

class CustomerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        return view('pages.customer.index');
    }

    public function listData() {
        $customers = Customer::orderBy('id', 'desc')->get();
        $no = 0;
        $status = "";
        $data = array();
        foreach ($customers as $customer) {
            $no++;
            $row = array();
            $row[] = $no;
            $row[] = $customer->customer_name;
            $row[] = $customer->customer_phone;
            $row[] = '<a onclick="editForm(' . $customer->id . ')" class="btn btn-warning btn-sm btn-block"><i class="far fa-edit"></i> Edit</a>';
            $data[] = $row;
        }

        $output = array("data" => $data);
        return response()->json($output);
    }

    public function add(Request $request)
    {
        $customerExist = Customer::where('customer.customer_name', '=', $request['customerName'])->get(); 

        if (count($customerExist) > 0) {
            return response()->json([
                "error" => true,
                "message" => "Customer is already exist !"
            ]);
        }

        $customer = new Customer;
        $customer->customer_name = $request['customerName'];
        $customer->customer_phone = "";
        $customer->save();
        
        return response()->json($customer);
  
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
        $customer = new Customer;
        $customer->customer_name = $request['customerName'];
        $customer->customer_phone = $request['customerPhone'];
        
        if (!$customer->save()) {
            return redirect()->route('customer.index')
                    ->with('failed_message', 'Data gagal disimpan.');
        }
        
        return redirect()->route('customer.index')
            ->with('success_message', 'Data berhasil ditambahkan.');
  
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
        $customer = Customer::find($id);
        echo json_encode($customer);
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
        $customer = Customer::find($id);
        $customer->customer_name = $request['customerName'];
        $customer->customer_phone = $request['customerPhone'];
        $customer->update();

        if (!$customer->update()) {
            return redirect()->route('customer.index')
                ->with('failed_message', 'Data Customer gagal diperbarui.');
        }

        return redirect()->route('customer.index')
                ->with('success_message', 'Data Customer berhasil diperbarui.');
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
