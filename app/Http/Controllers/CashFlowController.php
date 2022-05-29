<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\Product;
use Illuminate\Http\Request;
use App\Models\CashFlow;

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
        $cashflows =  CashFlow::leftJoin('depos', 'depo_id', '=', 'depos.id')
                    ->leftJoin('users', 'depos.user_id', '=', 'users.id')
                    ->select('cash_flow.id', 'input_date', 'users.name', 'cash_type', 'notes', 'amount')
                    ->orderBy('cash_flow.input_date', 'desc')
                    ->get(); 
        $no = 0;
        $status = "";
        $data = array();
        foreach ($cashflows as $cashflow) {
            $no++;
            $row = array();
            $row[] = $no;
            $row[] = $cashflow->input_date;
            $row[] = $cashflow->name;
            $row[] = strtoupper($cashflow->cash_type);
            $row[] = $cashflow->notes;
            $row[] = rupiah($cashflow->amount, TRUE);
            $row[] = '<a href="'. url("/") .'/cashflow/edit/' . $cashflow->cashflow_id . '" onclick="editForm(' . $cashflow->cashflow_id . ')" class="btn btn-warning btn-sm"><i class="far fa-edit"></i></a>
            <a href="#" onclick="detailsView(' . $cashflow->cashflow_id . ')" class="btn btn-primary btn-sm" data-toggle="modal"  data-target="#modal-details"><i class="far fa-eye"></i></a>
            <a href="'. url("/") .'/cashflow/print-invoice/' . $cashflow->cashflow_id . '" onclick="editForm(' . $cashflow->cashflow_id . ')" class="btn btn-dark btn-sm"><i class="far fa-file"></i></a>';
            
            array_push($data, $row);
        }

        $output = array("data" => $data);
        return response()->json($output);
    }
}
