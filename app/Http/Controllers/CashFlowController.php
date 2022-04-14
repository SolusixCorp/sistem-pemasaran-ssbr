<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\Product;
use Illuminate\Http\Request;
use App\Models\Order;

class CashFlowController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        return view('pages.cashflow.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        $barangs = Product::orderBy('barang_id', 'asc')->get();
        $employees = Employee::orderBy('id', 'desc')->get();

        return view('pages.cashflow.add', [
            "barangs_item" => $barangs,
            "customers"  => $employees,   
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
        //
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
        $barangs = Product::orderBy('barang_id', 'asc')->get();
        $employees = Employee::orderBy('id', 'desc')->get();

        return view('pages.cashflow.edit', [
            "barangs_item" => $barangs,
            "customers"  => $employees,   
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
        //
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

    public function getAllData() {
        $orders =  Order::leftJoin('customer', 'customer.id', '=', 'orders.customer_id')
                    ->leftJoin('users', 'users.id', '=', 'orders.kasir_id')
                    ->select(['orders.id as order_id', 'orders.order_date', 'customer.customer_name', 'orders.total_with_discount'])
                    ->orderBy('orders.order_date', 'desc')
                    ->get(); 
        $no = 0;
        $status = "";
        $data = array();
        foreach ($orders as $order) {
            $no++;
            $row = array();
            $row[] = $no;
            $row[] = $order->order_date;
            $row[] = 'Depo Malang';
            $row[] = 'Pendapatan';
            $row[] = 'Penjualan Produk';
            $row[] = rupiah(100000000, true);
            $row[] = '<a href="'. url("/") .'/cashflow/edit/' . $order->order_id . '" onclick="editForm(' . $order->order_id . ')" class="btn btn-warning btn-sm"><i class="far fa-edit"></i></a>
            <a href="#" onclick="detailsView(' . $order->order_id . ')" class="btn btn-primary btn-sm" data-toggle="modal"  data-target="#modal-details"><i class="far fa-eye"></i></a>
            <a href="'. url("/") .'/cashflow/print-invoice/' . $order->order_id . '" onclick="editForm(' . $order->order_id . ')" class="btn btn-dark btn-sm"><i class="far fa-file"></i></a>';
            
            array_push($data, $row);
        }

        $output = array("data" => $data);
        return response()->json($output);
    }
}
