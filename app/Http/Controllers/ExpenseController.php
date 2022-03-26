<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Expense;
use PDF;

class ExpenseController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        return view('pages.report.expense.index');
    }

    public function listDataPdf() {
        $expenses = Expense::orderBy('expense_date', 'desc')->get();
        $pdf = PDF::loadView('pages.report.expense.export-pdf', compact('expenses'));
        $pdf->setPaper('a4', 'potrait');
        return $pdf->stream();
    }

    public function getData($start, $end) {

        $startDate = date($start);
        $endtDate = date($end);
        if ($start == $end) {
            $expenses = Expense::orderBy('expense_date', 'desc')->where('expense_date', $start)->get();
        } else {
            $expenses = Expense::orderBy('expense_date', 'desc')->whereBetween('expense_date', [$startDate, $endtDate])->get();
        }
        
        $no = 0;
        $status = "";
        $data = array();
        foreach ($expenses as $expense) {
            $no++;
            $row = array();
            $row[] = $no;
            $row[] = $expense->expense_date;
            $row[] = $expense->expense_type;
            $row[] = rupiah($expense->expense_nominal, TRUE);
            $row[] = '<a onclick="editForm(' . $expense->id . ')" class="btn btn-success btn-sm btn-block"><i class="far fa-edit"></i> Edit</a>
            ';
            $data[] = $row;
        }

        return $data;
    }

    public function listData($start, $end) {

        $data = $this->getData($start, $end);
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
        $expense = new Expense;
        $expense->expense_date = $request['expenseDate'];
        $expense->expense_type = $request['expenseType'];
        $expense->expense_nominal = $request['expenseNominal'];
        $expense->save();
        
        return redirect()->route('expense.index')
            ->with('success_message', 'Data Pengeluaran berhasil ditambahkan.');
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
        $expense = Expense::find($id);
        echo json_encode($expense);
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
        $expense = Expense::find($id);
        $expense->expense_date = $request['expenseDate'];
        $expense->expense_type = $request['expenseType'];
        $expense->expense_nominal = $request['expenseNominal'];
        $expense->update();
  
        return redirect()->route('expense.index')
            ->with('success_message', 'Expense updated successfully.');
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
        $expense = Expense::find($id);
        $expense->delete();
    }
}
