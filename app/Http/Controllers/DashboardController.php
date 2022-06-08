<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\CashFlow;
use App\Models\StockFlow;
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
        $endDate = Carbon::now()->subDays(-1)->format('Y-m-d');
        $currentDate = Carbon::now()->format('Y-m-d');
        $startDate = Carbon::now()->subDays(7)->format('Y-m-d');

        $purchaseCarts = CashFlow::select(
            DB::raw('sum(amount) as totals'))
            ->whereBetween('input_date', [$startDate, $endDate])
            ->where('revenue_type_in', '=', 'product_sales')
            ->get();

        $dateCarts = CashFlow::select(
            DB::raw('input_date'))
            ->whereBetween('input_date', [$startDate, $endDate])
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

        // Cash In
        $depoCartsCashIn = CashFlow::leftJoin('depos', 'depo_id', '=', 'depos.id')
                ->leftJoin('users', 'depos.user_id', '=', 'users.id')
                ->select('users.name', 'amount')
                ->whereBetween('input_date', [$startDate . '%', $endDate . '%'])
                ->where('cash_type', '=', 'revenue')
                ->groupBy('depo_id')
                ->get();

        $deposCashIn = array();
        foreach($depoCartsCashIn as $depo) {
            $deposCashIn[] = $depo->name;
        }

        $cashInTot = $depoCartsCashIn->sum('amount');
        $cashin = array();
        foreach($depoCartsCashIn as $depo) {
            $cashin[] = ($depo->amount / $cashInTot) * 100;
        }

        $depoCartsCashInData = json_encode($deposCashIn);
        $cashInCartsData = json_encode($cashin);

        // Cash Out
        $depoCartsCashOut = CashFlow::leftJoin('depos', 'depo_id', '=', 'depos.id')
                ->leftJoin('users', 'depos.user_id', '=', 'users.id')
                ->select('users.name', 'amount')
                ->whereBetween('input_date', [$startDate . '%', $endDate . '%'])
                ->where('cash_type', '=', 'expense')
                ->groupBy('depo_id')
                ->get();

        $deposCashOut = array();
        foreach($depoCartsCashOut as $depo) {
            $deposCashOut[] = $depo->name;
        }

        $cashOutTot = $depoCartsCashOut->sum('amount');
        $cashout = array();
        foreach($depoCartsCashOut as $depo) {
            $cashout[] = ($depo->amount / $cashOutTot) * 100;
        }

        $depoCartsCashOutData = json_encode($deposCashOut);
        $cashOutCartsData = json_encode($cashout);

        // Stock In
        $depoCartsStockIn = StockFlow::leftJoin('depos', 'depo_id', '=', 'depos.id')
                ->leftJoin('users', 'depos.user_id', '=', 'users.id')
                ->select('users.name', 'qty')
                ->whereBetween('input_date', [$startDate . '%', $endDate . '%'])
                ->where('stock_type', '=', 'in')
                ->groupBy('depo_id')
                ->get();

        $deposStockIn = array();
        foreach($depoCartsStockIn as $depo) {
            $deposStockIn[] = $depo->name;
        }

        $stockInTot = $depoCartsStockIn->sum('qty');
        $stockin = array();
        foreach($depoCartsStockIn as $depo) {
            $stockin[] = ($depo->qty / $stockInTot) * 100;
        }

        $depoCartsStockInData = json_encode($deposStockIn);
        $stockInCartsData = json_encode($stockin);

        // Stock Out
        $depoCartsStockOut = StockFlow::leftJoin('depos', 'depo_id', '=', 'depos.id')
                ->leftJoin('users', 'depos.user_id', '=', 'users.id')
                ->select('users.name', 'qty')
                ->whereBetween('input_date', [$startDate . '%', $endDate . '%'])
                ->where('stock_type', '=', 'out')
                ->groupBy('depo_id')
                ->get();

        $deposStockOut = array();
        foreach($depoCartsStockOut as $depo) {
            $deposStockOut[] = $depo->name;
        }

        $stockOutTot = $depoCartsStockOut->sum('qty');
        $stockout = array();
        foreach($depoCartsStockOut as $depo) {
            $stockout[] = ($depo->qty / $stockOutTot) * 100;
        }

        $depoCartsStockOutData = json_encode($deposStockOut);
        $stockOutCartsData = json_encode($stockout);
        
        //Cash Flow Terbaru
        $depoCashFlows = CashFlow::leftJoin('depos', 'depo_id', '=', 'depos.id')
                ->leftJoin('users', 'depos.user_id', '=', 'users.id')
                ->select('users.name', 'input_date', 'amount', 'cash_type', 'revenue_type_in', 'expense_type')
                ->whereBetween('input_date', [$startDate . '%', $endDate . '%'])
                ->limit(10)
                ->get();

        $depoCashFlowNewDatas = array();
        foreach ($depoCashFlows as $depoCashFlow) {
            $category = '';
            if ($depoCashFlow->cash_type == 'revenue') {
                $category = $depoCashFlow->revenue_type_in;
            } else {
                $category = $depoCashFlow->expense_type;
            }

            $depoCashFlowNewDatas[] = array(
                'depo_name' => $depoCashFlow->name,
                'desc' => strtoupper($depoCashFlow->cash_type) . ' - ' . cashCategoryLabel($category) . ' (' . rupiah($depoCashFlow->amount, TRUE) . ')',
                'date' => $depoCashFlow->input_date,
            );
        }

        //Stock Flow Terbaru
        $depoStockFlows = StockFlow::leftJoin('depos', 'depo_id', '=', 'depos.id')
                ->leftJoin('users', 'depos.user_id', '=', 'users.id')
                ->leftJoin('products', 'product_id', '=', 'products.id')
                ->select('users.name', 'products.name as product', 'input_date', 'qty', 'stock_type', 'stockin_category', 'stockout_category')
                ->whereBetween('input_date', [$startDate . '%', $endDate . '%'])
                ->limit(10)
                ->get();

        $depoStockFlowNewDatas = array();
        foreach ($depoStockFlows as $depoStockFlow) {
            $category = '';
            if ($depoStockFlow->stock_type == 'in') {
                $category = $depoStockFlow->stockin_category;
            } else {
                $category = $depoStockFlow->stockout_category;
            }

            $depoStockFlowNewDatas[] = array(
                'depo_name' => $depoStockFlow->name,
                'desc' => strtoupper('Stock ' . $depoStockFlow->stock_type) . ' - ' . ucfirst($category) . ' ' . $depoStockFlow->product . ' (Qty : ' . $depoStockFlow->qty . ')',
                'date' => $depoStockFlow->input_date,
            );
        }

        return view('index', compact('purchaseCartData', 'depoCartsCashInData', 'cashInCartsData', 'depoCartsCashOutData', 'cashOutCartsData', 'depoCartsStockInData', 'stockInCartsData', 'depoCartsStockOutData', 'stockOutCartsData', 'depoCashFlowNewDatas', 'depoStockFlowNewDatas', 'dateCartData', 'startDate', 'endDate'));
        
    }

    public function data($start, $end)
    {
        $startDate = date('Y-m-d', strtotime($start));
        $endtDate =date('Y-m-d', strtotime($end));

        $endDate = Carbon::now()->format('Y-m-d');

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
            ->whereBetween('input_date', [$startDate, $endDate])
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
        $endDate = Carbon::now()->format('Y-m-d');

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
        $endDate = Carbon::now()->format('Y-m-d');

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
        $endDate = Carbon::now()->format('Y-m-d');

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
