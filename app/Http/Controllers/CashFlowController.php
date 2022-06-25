<?php

namespace App\Http\Controllers;

use App\Models\Depo;
use App\Models\Product;
use App\Models\ProductDepo;
use Illuminate\Http\Request;
use App\Models\CashFlow;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

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
        $endDate = Carbon::now()->format('Y-m-d');
        $startDate = Carbon::now()->format('Y-m-d');

        $data = array(
            'startDate' => $startDate,
            'endDate' => $endDate,
        );

        return view('pages.cashflow.index', compact('data'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        $user = Auth::user();

        if ($user->role == 'ho') {
            $depos = Depo::leftJoin('users', 'user_id', '=', 'users.id')->get();
        } else {
            $depos = Depo::leftJoin('users', 'user_id', '=', 'users.id')
                    ->where('user_id', '=', $user->id)
                    ->get();
        }

        return view('pages.cashflow.add', [
            "depos"  => $depos,   
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
        $in_date = $request['date'];
        $in_time = $request['time'];
        $in_depo = $request['depo_name'];
        $in_cash_type = $request['cash_type'];
        $in_cash_category = $request['cash_category'];
        $in_amount = $request['amount'];
        $in_receipt = $request['receipt'];
        $in_notes = $request['notes'];
        $in_is_matched = $request['match'];

        $cash = new CashFlow;
        $cash->depo_id = $in_depo;
        if ($in_date != null && $in_time != null) {
            $cash->input_date = $in_date . " " . $in_time . date(":s", time());
        }
        $cash->cash_type = $in_cash_type;
        if ($in_cash_type == 'revenue') {
            $cash->revenue_type_in = $in_cash_category;
            $cash->expense_type = '';
        } else {
            $cash->revenue_type_in = '';
            $cash->expense_type = $in_cash_category;
        }
        $cash->notes = $in_notes;
        $cash->amount = $in_amount;
        $cash->is_matched = $in_is_matched;

        if ($in_receipt) {
            $receipt_name = time().'.' . $request->receipt->extension();
            $request->receipt->move(public_path('cash'), $receipt_name);
        }
        $cash->upload_file = $receipt_name;

        if (!$cash->save()) {
            return redirect()->route('cashflow.index')
                ->with('failed_message', 'Data cash flow gagal disimpan.');
        }

        return redirect()->route('cashflow.index')
                ->with('success_message', 'Data cash flow berhasil disimpan.');
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
        $cashData =  CashFlow::with('depo')->find($id);

        $category = '';
        if ($cashData->revenue_type_in != '') {
            $category = cashCategoryLabel($cashData->revenue_type_in);
        } else {
            $category = cashCategoryLabel($cashData->expense_type);
        } 

        $status = '';
        if ($cashData->is_matched > 0) {
            $status = '<span class="label radius-circle bg-success">Match</span>';
        } else {
            $status = '<span class="label radius-circle bg-danger">Not Match</span>';
        }

        $cash = array(
            'cash_id' => $cashData->id,
            'input_date' => $cashData->input_date,
            'depo' => $cashData->depo->user->name,
            'type' => strtoupper($cashData->cash_type),
            'category' => $category,
            'notes' => $cashData->notes,
            'total' => rupiah($cashData->amount, TRUE),
            'match' => $status,
        );

        return response()->json($cash);
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
        $user = Auth::user();

        if ($user->role == 'ho') {
            $depos = Depo::leftJoin('users', 'user_id', '=', 'users.id')->get();
        } else {
            $depos = Depo::leftJoin('users', 'user_id', '=', 'users.id')
                    ->where('user_id', '=', $user->id)
                    ->get();
        }

        $cashData =  CashFlow::with('depo')->find($id);

        $category = '';
        if ($cashData->revenue_type_in != '') {
            $category = $cashData->revenue_type_in;
        } else {
            $category = $cashData->expense_type;
        } 

        $date = substr($cashData->input_date, 0, 10);
        $time = substr($cashData->input_date, 11, 5);

        $cash = array(
            'cash_id' => $cashData->id,
            'input_date' => $date,
            'input_time' => $time,
            'depo_id' => $cashData->depo->id,
            'depo' => $cashData->depo->user->name,
            'type' => $cashData->cash_type,
            'category' => $category,
            'notes' => $cashData->notes,
            'total' => (int) $cashData->amount,
            'receipt' => $cashData->upload_file,
            'match' => $cashData->is_matched,
        );

        return view('pages.cashflow.edit', [
            "cash" => $cash,
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
        //
        $in_date = $request['date'];
        $in_time = $request['time'];
        $in_depo = $request['depo_name'];
        $in_cash_type = $request['cash_type'];
        $in_cash_category = $request['cash_category'];
        $in_amount = $request['amount'];
        $in_receipt = $request['receipt'];
        $in_notes = $request['notes'];
        $in_is_matched = $request['match'];

        $cash = CashFlow::find($id);
        $cash->depo_id = $in_depo;
        if ($in_date != null && $in_time != null) {
            $cash->input_date = $in_date . " " . $in_time . date(":s", time());
        }
        $cash->cash_type = $in_cash_type;
        if ($in_cash_type == 'revenue') {
            $cash->revenue_type_in = $in_cash_category;
            $cash->expense_type = '';
        } else {
            $cash->revenue_type_in = '';
            $cash->expense_type = $in_cash_category;
        }
        $cash->notes = $in_notes;
        $cash->amount = $in_amount;
        $cash->is_matched = $in_is_matched;

        if ($in_receipt) {
            $receipt_name = time().'.' . $request->receipt->extension();
            $request->receipt->move(public_path('cash'), $receipt_name);

            $cash->upload_file = $receipt_name;
        }

        if (!$cash->update()) {
            return redirect()->route('cashflow.index')
                ->with('failed_message', 'Data cash flow gagal diperbarui.');
        }

        return redirect()->route('cashflow.index')
                ->with('success_message', 'Data cash flow berhasil diperbarui.');
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

    public function getById($id) {
        $cashData =  CashFlow::with('depo')->find($id);

        $category = '';
        if ($cashData->revenue_type_in != '') {
            $category = cashCategoryLabel($cashData->revenue_type_in);
        } else {
            $category = cashCategoryLabel($cashData->expense_type);
        } 

        $status = '';
        if ($cashData->is_matched > 0) {
            $status = 'Match';
        } else {
            $status = 'Not Match';
        }

        $cash = array(
            'cash_id' => $cashData->id,
            'input_date' => $cashData->input_date,
            'depo' => $cashData->depo->user->name,
            'type' => strtoupper($cashData->cash_type),
            'category' => $category,
            'notes' => $cashData->notes,
            'total' => rupiah($cashData->amount, TRUE),
            'match' => $status,
        );

        return response()->json($cash);
    }

    public function getAllData($start, $end) {
        $startDate = date('Y-m-d', strtotime($start));
        $endDate = date('Y-m-d', strtotime($end . "+1 days"));

        $user = Auth::user();
        $depo = Depo::where('user_id', '=', $user->id)->first();

        if ($user->role == 'ho') {
            $cashflows =  CashFlow::leftJoin('depos', 'depo_id', '=', 'depos.id')
                    ->leftJoin('users', 'depos.user_id', '=', 'users.id')
                    ->select('cash_flow.id', 'input_date', 'users.name', 'cash_type', 'revenue_type_in', 'expense_type', 'is_matched', 'notes', 'amount', 'upload_file')
                    ->whereBetween('input_date', [$startDate . '%', $endDate . '%'])
                    ->orderBy('cash_flow.input_date', 'desc')
                    ->get(); 
        } else {
            $cashflows =  CashFlow::leftJoin('depos', 'depo_id', '=', 'depos.id')
                    ->leftJoin('users', 'depos.user_id', '=', 'users.id')
                    ->select('cash_flow.id', 'input_date', 'users.name', 'cash_type', 'revenue_type_in', 'expense_type', 'is_matched', 'notes', 'amount', 'upload_file')
                    ->whereBetween('input_date', [$startDate . '%', $endDate . '%'])
                    ->where('depo_id', '=', $depo->id)
                    ->orderBy('cash_flow.input_date', 'desc')
                    ->get(); 
        }

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
            if ($cashflow->revenue_type_in != '') {
                $row[] = '(' . cashCategoryLabel($cashflow->revenue_type_in) . ')<br>' . $cashflow->notes ;
            } else {
                $row[] = '(' . cashCategoryLabel($cashflow->expense_type) . ')<br>' . $cashflow->notes;
            }
            $row[] = rupiah($cashflow->amount, TRUE);
            if ($cashflow->is_matched > 0) {
                $row[] = '<span class="label radius-circle bg-success">Match</span>';
            } else {
                $row[] = '<span class="label radius-circle bg-danger">Not Match</span>';
            }
            
            $edit = '<a href="'. url("/") .'/cashflow/edit/' . $cashflow->id . '" class="btn btn-warning btn-sm"><i class="far fa-edit"></i></a>
                    <a href="#" onclick="detailsView(' . $cashflow->id . ')" class="btn btn-primary btn-sm" data-toggle="modal"  data-target="#modal-details"><i class="far fa-eye"></i></a>';
            $print = '<a href="'. url("/") . '/cash/'. $cashflow->upload_file . '"  target="_blank" class="btn btn-dark btn-sm"><i class="far fa-file"></i></a>';
            if ($cashflow->upload_file == '') {
                $row[] = $edit;
            } else {
                $row[] = $edit . ' ' . $print;
            }

            array_push($data, $row);
        }

        $output = array("data" => $data);
        return response()->json($output);
    }
}
