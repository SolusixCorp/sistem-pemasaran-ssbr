<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\Income;
use App\Models\Expense;
use DB;

class DashboardController extends Controller
{
    public function index()
    {
        $currentDate = Carbon::now()->format('Y-m-d');
        $startDate = Carbon::now()->subDays(7)->format('Y-m-d');

        // $purchaseCarts = Income::select(
        //     DB::raw('sum(total) as nominals'))
        //     ->whereBetween('date', [$startDate, $currentDate])
        //     ->groupBy('date')
        //     ->get();

        // $incomeCarts = Income::select(
        //     DB::raw('sum(income) as nominals'))
        //     ->whereBetween('date', [$startDate, $currentDate])
        //     ->groupBy('date')
        //     ->get();

        // $dateCarts = Income::select(
        //     DB::raw('date'))
        //     ->whereBetween('date', [$startDate, $currentDate])
        //     ->groupBy('date')
        //     ->get();

        // $income = array();
        // foreach($incomeCarts as $nominal) {
        //     $income[] = $nominal->nominals;
        // }

        // $purchase = array();
        // foreach($purchaseCarts as $nominal) {
        //     $purchase[] = $nominal->nominals;
        // }

        // $dates = array();
        // foreach($dateCarts as $date) {
        //     $dates[] = tanggal($date->date);
        // }

        // $incomeCartData = json_encode($income);
        // $purchaseCartData = json_encode($purchase);
        // $dateCartData = json_encode($dates);
        // return view('index', compact('incomeCartData', 'purchaseCartData', 'dateCartData', 'startDate', 'currentDate'));
        
        return view('index');
    }

    public function data($start, $end)
    {
        $startDate = date($start);
        $endtDate = date($end);
        $currentDate = Carbon::now()->format('Y-m-d');

        if ($startDate == $endtDate) {
            $purchaseCount = Income::where('date', $startDate)
            ->get()->count();

            $purchaseSum = Income::where('date', $startDate)
            ->get()->sum('total');

            $dateRange = tanggal($startDate);
        } else {
            $purchaseCount = Income::whereBetween('date', [$startDate, $endtDate])
            ->get()->count();

            $purchaseSum = Income::whereBetween('date', [$startDate, $endtDate])
            ->get()->sum('total');

            $dateRange = tanggal($startDate) . " - " . tanggal($endtDate);
        }

        $incomeCarts = Income::select(
            DB::raw('sum(income) as nominals'))
            ->groupBy('date')
            ->get();
            
        $purchaseCarts = Income::select(
            DB::raw('sum(total) as nominals'))
            ->groupBy('date')
            ->get();

        $dateCarts = Income::select(
            DB::raw('date'))
            ->whereBetween('date', [$startDate, $currentDate])
            ->groupBy('date')
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
            $dates[] = tanggal($date->date);
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
            $purchaseCount = Income::where('date', $endtDate)
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
            $purchaseSum = Income::where('date', $endtDate)
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
       
        $purchaseSum = Income::where('date', $endtDate)
        ->get()->sum('income');

        $purchaseCount = Income::where('date', $endtDate)
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
            $purchaseSum = Income::select(
                DB::raw('sum(income) as nominals'))
                ->where('date', $startDate)
                ->groupBy('date')
                ->get();
        } else {
            $purchaseSum = Income::select(
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
