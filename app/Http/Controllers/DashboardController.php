<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\CashFlow;
use App\Models\Expense;
use DB;

class DashboardController extends Controller
{
    public function index()
    {
        $currentDate = Carbon::now()->format('Y-m-d');
        // $startDate = Carbon::now()->format('Y-m-d');

        $startDate = Carbon::now()->subDays(7)->format('Y-m-d');

        $purchaseCarts = CashFlow::select(
            DB::raw('sum(amount) as totals'))
            ->whereBetween('input_date', [$startDate, $currentDate])
            ->where('revenue_type_in', '=', 'product_sales')
            ->get();

        $incomeCarts = CashFlow::select(
            DB::raw('sum(amount) as nominals'))
            ->whereBetween('input_date', [$startDate, $currentDate])
            ->groupBy('input_date')
            ->get();

        $dateCarts = CashFlow::select(
            DB::raw('input_date'))
            ->whereBetween('input_date', [$startDate, $currentDate])
            ->groupBy('input_date')
            ->get();

        $income = array();
        foreach($incomeCarts as $nominal) {
            $income[] = $nominal->nominals;
        }

        $purchase = array();
        foreach($purchaseCarts as $total) {
            $purchase[] = $total->totals;
        }

        $dates = array();
        foreach($dateCarts as $date) {
            $dates[] = tanggal($date->input_date);
        }

        $incomeCartData = json_encode($income);
        $purchaseCartData = json_encode($purchase);
        $dateCartData = json_encode($dates);
        return view('index', compact('incomeCartData', 'purchaseCartData', 'dateCartData', 'startDate', 'currentDate'));
        
        return view('index');
    }

    public function data($start, $end)
    {
        $startDate = date($start);
        $endtDate = date($end);
        $currentDate = Carbon::now()->format('Y-m-d');

        if ($startDate == $endtDate) {
            $purchaseCount = CashFlow::where('input_date', $startDate)
            ->get()->count();

            $purchaseSum = CashFlow::where('input_date', $startDate)
                ->where('revenue_type_in', '=', 'product_sales')
                ->get()
                ->sum('amount');

            $dateRange = tanggal($startDate);
        } else {
            $purchaseCount = CashFlow::whereBetween('input_date', [$startDate, $endtDate])
            ->get()->count();

            $purchaseSum = CashFlow::whereBetween('input_date', [$startDate, $endtDate])
            ->get()->sum('amount');

            $dateRange = tanggal($startDate) . " - " . tanggal($endtDate);
        }

        $incomeCarts = CashFlow::select(
            DB::raw('sum(amount) as nominals'))
            ->groupBy('input_date')
            ->get();
            
        $purchaseCarts = CashFlow::select(
            DB::raw('sum(amount) as nominals'))
            ->groupBy('input_date')
            ->get();

        $dateCarts = CashFlow::select(
            DB::raw('input_date'))
            ->whereBetween('input_date', [$startDate, $currentDate])
            ->groupBy('input_date')
            ->get();

        if ($purchaseSum != 0 && $purchaseSum != 0) {
            $purchaseAverage = $purchaseSum / $purchaseCount;
        } else {
            $purchaseAverage = 0;
        }

        $income = array();
        foreach($incomeCarts as $nominal) {       
            $income[] = $nominal->nominals;
        }

        $dates = array();
        foreach($dateCarts as $date) {
            $dates[] = tanggal($date->input_date);
        }

        $purchase = array();
        foreach($purchaseCarts as $nominal) {
            $purchase[] = $nominal->nominals;
        }

        $incomeCartData = json_encode($income);
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
