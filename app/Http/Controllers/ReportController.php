<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Depo;
use App\Models\Report;

class ReportController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        return view('pages.report.index');
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

        return view('pages.report.add-report', [
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
        $in_payment_type = $request['payment_type'];
        $in_amount = $request['amount'];
        $in_receipt = $request['receipt'];
        $in_notes = $request['notes'];

        $report = new Report;
        $report->depo_id = $in_depo;
        if ($in_date != null && $in_time != null) {
            $report->payment_date = $in_date . " " . $in_time . ":00";
        }
        $report->payment_type = $in_payment_type;
        $report->payment_desc = $in_notes;
        $report->amount = $in_amount;

        if ($in_receipt) {
            $receipt_name = time().'.' . $request->receipt->extension();
            $request->receipt->move(public_path('payment'), $receipt_name);
        }
        $report->payment_file_upload= $receipt_name;

        if (!$report->save()) {
            return redirect()->route('report.index')
                ->with('failed_message', 'Data Report gagal disimpan.');
        }

        return redirect()->route('report.index')
                ->with('success_message', 'Data Report berhasil disimpan.');
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
        $user = Auth::user();

        if ($user->role == 'ho') {
            $depos = Depo::leftJoin('users', 'user_id', '=', 'users.id')->get();
        } else {
            $depos = Depo::leftJoin('users', 'user_id', '=', 'users.id')
                    ->where('user_id', '=', $user->id)
                    ->get();
        }

        $reportData = Report::leftJoin('depos', 'depo_id', '=', 'depos.id')
                    ->leftJoin('users', 'depos.user_id', '=', 'users.id')
                    ->select('ar_ap_report.id', 'depo_id', 'users.name', 'payment_date', 'payment_type', 'payment_desc', 'payment_file_upload', 'amount')
                    ->where('ar_ap_report.id', '=', $id)
                    ->first();

        $date = substr($reportData->payment_date, 0, 10);
        $time = substr($reportData->payment_date, 11, 5);

        $report = array(
            'report_id' => $reportData->id,
            'date' => $date,
            'time' => $time,
            'depo_id' => $reportData->depo_id,
            'depo' => $reportData->name,
            'type' => $reportData->payment_type,
            'desc' => $reportData->payment_desc,
            'receipt' => $reportData->payment_file_upload,
            'amount' => $reportData->amount,
        );

        return view('pages.report.edit-report', [
            "depos"  => $depos,
            "report"  => $report,   
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
        $in_payment_type = $request['payment_type'];
        $in_amount = $request['amount'];
        $in_receipt = $request['receipt'];
        $in_notes = $request['notes'];

        $report = Report::find($id);
        $report->depo_id = $in_depo;
        if ($in_date != null && $in_time != null) {
            $report->payment_date = $in_date . " " . $in_time . ":00";
        }
        $report->payment_type = $in_payment_type;
        $report->payment_desc = $in_notes;
        $report->amount = $in_amount;

        if ($in_receipt) {
            $receipt_name = time().'.' . $request->receipt->extension();
            $request->receipt->move(public_path('payment'), $receipt_name);
        }
        $report->payment_file_upload= $receipt_name;

        if (!$report->save()) {
            return redirect()->route('report.index')
                ->with('failed_message', 'Data Report gagal diperbarui.');
        }

        return redirect()->route('report.index')
                ->with('success_message', 'Data Report berhasil diperbarui.');
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
        $reports =  Report::leftJoin('depos', 'depo_id', '=', 'depos.id')
                    ->leftJoin('users', 'depos.user_id', '=', 'users.id')
                    ->select('ar_ap_report.id', 'payment_date', 'users.name', 'payment_type', 'payment_desc', 'amount', 'payment_file_upload')
                    ->orderBy('ar_ap_report.payment_date', 'desc')
                    ->get(); 
        $no = 0;
        $status = "";
        $data = array();
        foreach ($reports as $report) {
            $no++;
            $row = array();
            $row[] = $no;
            $row[] = $report->payment_date;
            $row[] = $report->name;
            $row[] = strtoupper($report->payment_type);
            $row[] = $report->payment_desc;
            $row[] = rupiah($report->amount, TRUE);
            $edit = '<a href="'. url("/") .'/report/edit/' . $report->id . '" class="btn btn-warning btn-sm"><i class="far fa-edit"></i></a>';
            $print = '<a href="'. url("/") . '/payment/'. $report->payment_file_upload . '"  target="_blank" class="btn btn-dark btn-sm"><i class="far fa-file"></i></a>';
            if ($report->payment_file_upload == '') {
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
