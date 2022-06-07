<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\CashFlow;
use App\Models\Expense;
use App\Models\Depo;
use DB;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $depo = Depo::where('user_id', '=', $user->id)->first();
        $currentDate = Carbon::now()->format('Y-m-d');
        // $startDate = Carbon::now()->format('Y-m-d');

        $startDate = Carbon::now()->subDays(7)->format('Y-m-d');

        $purchaseCarts = CashFlow::select(
            DB::raw('sum(amount) as totals'))
            ->whereBetween('input_date', [$startDate, $currentDate])
            ->where('revenue_type_in', '=', 'product_sales')
            ->where('depo_id', '=', $depo->id)
            ->get();

        $dateCarts = CashFlow::select(
            DB::raw('input_date'))
            ->whereBetween('input_date', [$startDate, $currentDate])
            ->where('depo_id', '=', $depo->id)
            ->groupBy('input_date')
            ->get();

        $purchase = array();
        foreach($purchaseCarts as $total) {
            $purchase[] = $total->totals;
        }

        $dates = array();
        foreach($dateCarts as $date) {
            $dates[] = tanggal($date->input_date);
        }

        $purchaseCartData = json_encode($purchase);
        $dateCartData = json_encode($dates);
        return view('index', compact('purchaseCartData', 'dateCartData', 'startDate', 'currentDate'));
        
        return view('index');
    }

    public function data($start, $end)
    {
        $startDate = date('Y-m-d', strtotime($start));
        $endtDate =date('Y-m-d', strtotime($end));

        $currentDate = Carbon::now()->format('Y-m-d');

        if ($startDate == $endtDate) {
            $purchaseCount = CashFlow::where('input_date', 'like', $startDate)
                ->where('revenue_type_in', '=', 'product_sales')
                ->get()
                ->count();

            $purchaseSum = CashFlow::where('input_date', 'like', $startDate)
                ->where('revenue_type_in', '=', 'product_sales')
                ->get()
                ->sum('amount');

            $dateRange = tanggal($startDate);

            $cashNew = CashFlow::where('input_date', 'like', $startDate)
                ->where('revenue_type_in', '=', 'product_sales')
                ->orderBy('input_date', 'desc')
                ->limit(10)
                ->get();
                
        } else {
            $purchaseCount = CashFlow::whereBetween('input_date', [$startDate.'%', $endtDate.'%'])
                ->where('revenue_type_in', '=', 'product_sales')   
                ->get()
                ->count();

            $purchaseSum = CashFlow::whereBetween('input_date', [$startDate.'%', $endtDate.'%'])
                ->where('revenue_type_in', '=', 'product_sales')
                ->get()
                ->sum('amount');

            $dateRange = tanggal($startDate) . " - " . tanggal($endtDate);

            $cashNew = CashFlow::whereBetween('input_date', [$startDate.'%', $endtDate.'%'])
                ->get();
        }

        // return $cashNew;
            
        $purchaseCarts = CashFlow::select(
            DB::raw('sum(amount) as nominals'))
            ->where('revenue_type_in', '=', 'product_sales')
            ->groupBy('input_date')
            ->get();

        $dateCarts = CashFlow::select(
            DB::raw('input_date'))
            ->whereBetween('input_date', [$startDate, $currentDate])
            ->where('revenue_type_in', '=', 'product_sales')
            ->groupBy('input_date')
            ->get();

        if ($purchaseSum != 0 && $purchaseSum != 0) {
            $purchaseAverage = $purchaseSum / $purchaseCount;
        } else {
            $purchaseAverage = 0;
        }

        $dates = array();
        foreach($dateCarts as $date) {
            $dates[] = tanggal($date->input_date);
        }

        $purchase = array();
        foreach($purchaseCarts as $nominal) {
            $purchase[] = $nominal->nominals;
        }

        $purchaseCartData = json_encode($purchase);
        $dateCartData = json_encode($dates);
   
        $sumData = array('sum' => rupiah($purchaseSum, TRUE), 'date' => $dateRange);
        $countData = array('count' => $purchaseCount, 'date' => $dateRange);
        $averageData = array('average' => rupiah($purchaseAverage, TRUE), 'date' => $dateRange);
        $data = array('sumData' => $sumData, 'countData' => $countData, 'averageData' => $averageData);

        return json_encode($data);
       
    }

    public function purchaseCount($start, $end)
    {
        $startDate = date($start);
        $endtDate = date($end);
        $currentDate = Carbon::now()->format('Y-m-d');

        if ($start == $end) {
            $purchaseCount = CashFlow::where('date', $endtDate)
            ->get()->count();
        }

        return $purchaseCount;
    }

    public function purchaseSum($start, $end)
    {
        $startDate = date($start);
        $endtDate = date($end);
        $currentDate = Carbon::now()->format('Y-m-d');

        if ($start == $end) {
            $purchaseSum = CashFlow::where('date', $endtDate)
            ->get()->sum('income');
        }

        echo $purchaseSum;
    }

    public function purchaseAverage($start, $end)
    {
        $startDate = date($start);
        $endtDate = date($end);
        $currentDate = Carbon::now()->format('Y-m-d');

        if ($start == $end) {
       
        $purchaseSum = CashFlow::where('date', $endtDate)
        ->get()->sum('income');

        $purchaseCount = CashFlow::where('date', $endtDate)
        ->get()->count();

        }

        if ($purchaseSum != 0 && $purchaseCount != 0) {
            $purchaseAverage = $purchaseSum / $purchaseCount;
        } else {
            $purchaseAverage = 0;
        }

        return $purchaseAverage;
    }

    public function cartsIncome($start, $end)
    {
        $startDate = date($start);
        $endtDate = date($end);
        if ($start == $end) {
            $purchaseSum = CashFlow::select(
                DB::raw('sum(income) as nominals'))
                ->where('date', $startDate)
                ->groupBy('date')
                ->get();
        } else {
            $purchaseSum = CashFlow::select(
                DB::raw('sum(income) as nominals'))
                ->whereBetween('date', [$startDate, $endtDate])
                ->groupBy('date')
                ->get();
        }

        foreach($purchaseSum as $nominal) {
            $data = array();
            $data[] = $nominal->nominals;
        }
        
        return response()->json($data);
    }


    public function show($id)
    {
        return view('user.profile', [
            'user' => User::findOrFail($id)
        ]);
    }
}
