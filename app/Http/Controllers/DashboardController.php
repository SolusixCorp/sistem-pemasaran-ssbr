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
        $endDate = Carbon::now()->format('Y-m-d');
        $startDate = Carbon::now()->format('Y-m-d');

        return $this->data($startDate, $endDate);
    }

    public function data($start, $end)
    {
        $user = Auth::user();
        $depo = Depo::where('user_id', '=', $user->id)->first();
        
        $startDate = date('Y-m-d', strtotime($start));
        $endDate = date('Y-m-d', strtotime($end . "+1 days"));

        // Cash In Depo
        $cashInLineCarts = CashFlow::leftJoin('depos', 'depo_id', '=', 'depos.id')
            ->leftJoin('users', 'depos.user_id', '=', 'users.id')
            ->select(DB::raw('sum(amount) as totals, name, MONTH(input_date) month'))
            ->whereBetween('input_date', [$startDate . '%', $endDate . '%'])
            ->where('revenue_type_in', '=', 'product_sales')
            ->where('depo_id', '=', $depo->id)
            ->groupBy('month')
            ->get();

        $cashinLineCarts = array();
        for ($i=1; $i < 12; $i++) {
            $month = false;
            foreach ($cashInLineCarts as $cashInLineCart) {
                if ($cashInLineCart->month == $i) {
                    $cashinLineCarts[] = $cashInLineCart->totals;
                    break;
                }
            }

            if (!$month) {
                $cashinLineCarts[] = 0; 
            }
        }

        $cashInLineCartData = json_encode($cashinLineCarts);

        // Cash Out Depo
        $cashOutLineCarts = CashFlow::leftJoin('depos', 'depo_id', '=', 'depos.id')
            ->leftJoin('users', 'depos.user_id', '=', 'users.id')
            ->select(DB::raw('sum(amount) as totals, name, MONTH(input_date) month'))
            ->whereBetween('input_date', [$startDate . '%', $endDate . '%'])
            ->where('expense_type', '=', 'expense')
            ->where('depo_id', '=', $depo->id)
            ->groupBy('month')
            ->get();

        $cashoutLineCarts = array();
        for ($i=1; $i < 12; $i++) {
            $month = false;
            foreach ($cashOutLineCarts as $cashOutLineCart) {
                if ($cashOutLineCart->month == $i) {
                    $cashoutLineCarts[] = $cashOutLineCart->totals;
                    break;
                }
            }

            if (!$month) {
                $cashoutLineCarts[] = 0; 
            }
        }

        $cashOutLineCartData = json_encode($cashoutLineCarts);

        // Stock In Depo
        $stockInLineCarts = StockFlow::leftJoin('depos', 'depo_id', '=', 'depos.id')
                ->leftJoin('users', 'depos.user_id', '=', 'users.id')
                ->select(DB::raw('sum(qty) as totals, name, MONTH(input_date) month'))
                ->whereBetween('input_date', [$startDate . '%', $endDate . '%'])
                ->where('stock_type', '=', 'in')
                ->where('depo_id', '=', $depo->id)
                ->groupBy('month')
                ->get();

        $stockinLineCarts = array();
        for ($i=1; $i < 12; $i++) {
            $month = false;
            foreach ($stockInLineCarts as $stockInLineCart) {
                if ($stockInLineCart->month == $i) {
                    $stockinLineCarts[] = $stockInLineCart->totals;
                    break;
                }
            }

            if (!$month) {
                $stockinLineCarts[] = 0; 
            }
        }

        $stockInLineCartData = json_encode($stockinLineCarts);

        // Stock Out Depo
        $stockOutLineCarts = StockFlow::leftJoin('depos', 'depo_id', '=', 'depos.id')
                ->leftJoin('users', 'depos.user_id', '=', 'users.id')
                ->select(DB::raw('sum(qty) as totals, name, MONTH(input_date) month'))
                ->whereBetween('input_date', [$startDate . '%', $endDate . '%'])
                ->where('stock_type', '=', 'out')
                ->where('depo_id', '=', $depo->id)
                ->groupBy('month')
                ->get();

        $stockoutLineCarts = array();
        for ($i=1; $i < 12; $i++) {
            $month = false;
            foreach ($stockOutLineCarts as $stockOutLineCart) {
                if ($stockOutLineCart->month == $i) {
                    $stockoutLineCarts[] = $stockOutLineCart->totals;
                    break;
                }
            }

            if (!$month) {
                $stockoutLineCarts[] = 0; 
            }
        }

        $stockOutLineCartData = json_encode($stockoutLineCarts);

        // Cash In
        $cashInCart = CashFlow::leftJoin('depos', 'depo_id', '=', 'depos.id')
            ->leftJoin('users', 'depos.user_id', '=', 'users.id')
            ->select(DB::raw('sum(amount) as totals, name'))
            ->whereBetween('input_date', [$startDate . '%', $endDate . '%'])
            ->where('revenue_type_in', '=', 'product_sales')
            ->groupBy('depo_id')
            ->get();
            

        $totCashIn = $cashInCart->sum('totals');
        $cashin = array();
        $cashin_depo = array();
        foreach($cashInCart as $cashIn) {
            if ($cashIn->totals > 0 && $totCashIn > 0) {
                $cashin[] = round(($cashIn->totals / $totCashIn) * 100, 2);
            } else {
                $cashin[] = 0;
            }
            $cashin_depo[] = $cashIn->name;
        }

        $cashinCartData = json_encode($cashin);
        $cashinCartDepo = json_encode($cashin_depo);
        
        // Cash Out
        $cashOutCart = CashFlow::leftJoin('depos', 'depo_id', '=', 'depos.id')
            ->leftJoin('users', 'depos.user_id', '=', 'users.id')
            ->select(DB::raw('sum(amount) as totals, name'))
            ->whereBetween('input_date', [$startDate . '%', $endDate . '%'])
            ->where('expense_type', '=', 'expense')
            ->groupBy('depo_id')
            ->get();

        $totCashOut = $cashOutCart->sum('totals');
        $cashout = array();
        $cashout_depo = array();
        foreach($cashOutCart as $cashOut) {
            if ($cashOut->totals > 0 && $totCashOut > 0) {
                $cashout[] = round(($cashOut->totals / $totCashOut) * 100, 2);
            } else {
                $cashout[] = 0;
            }
            $cashout_depo[] = $cashOut->name;
        }

        $cashoutCartData = json_encode($cashout);
        $cashoutCartDepo = json_encode($cashout_depo);

        // Stock In
        $stockInCart = StockFlow::leftJoin('depos', 'depo_id', '=', 'depos.id')
                ->leftJoin('users', 'depos.user_id', '=', 'users.id')
                ->select(DB::raw('sum(qty) as totals, name'))
                ->whereBetween('input_date', [$startDate . '%', $endDate . '%'])
                ->where('stock_type', '=', 'in')
                ->groupBy('depo_id')
                ->get();

        $totStockIn= $stockInCart->sum('totals');
        $stockin = array();
        $stockin_depo = array();
        foreach($stockInCart as $stockIn) {
            if ($stockIn->totals > 0 && $totStockIn > 0) {
                $stockin[] = round(($stockIn->totals / $totStockIn) * 100, 2);
            } else {
                $stockin[] = 0;
            }
            $stockin_depo[] = $stockIn->name;
        }

        $stockinCartData = json_encode($stockin);
        $stockinCartDepo = json_encode($stockin_depo);

        // Stock Out
        $stockOutCart = StockFlow::leftJoin('depos', 'depo_id', '=', 'depos.id')
                ->leftJoin('users', 'depos.user_id', '=', 'users.id')
                ->select(DB::raw('sum(qty) as totals, name'))
                ->whereBetween('input_date', [$startDate . '%', $endDate . '%'])
                ->where('stock_type', '=', 'out')
                ->groupBy('depo_id')
                ->get();

        $totStockOut = $stockOutCart->sum('totals');
        $stockout = array();
        $stockout_depo = array();
        foreach($stockOutCart as $stockOut) {
            if ($stockOut->totals > 0 && $totStockOut > 0) {
                $stockout[] = round(($stockOut->totals / $totStockOut) * 100, 2);
            } else {
                $stockout[] = 0;
            }
            $stockout_depo[] = $stockOut->name;
        }

        $stockoutCartData = json_encode($stockout);
        $stockoutCartDepo = json_encode($stockout_depo);

        $dateRange = tanggal($startDate) . ' - ' . tanggal(date('Y-m-d', strtotime($end)));
        
        if ($user->role == 'ho') {
            $cashInCard = CashFlow::select('amount')
                    ->whereBetween('input_date', [$startDate . '%', $endDate . '%'])
                    ->where('revenue_type_in', '=', 'product_sales')
                    ->get();

            //Cash Flow Terbaru
            $depoCashFlows = CashFlow::leftJoin('depos', 'depo_id', '=', 'depos.id')
                    ->leftJoin('users', 'depos.user_id', '=', 'users.id')
                    ->select('users.name', 'input_date', 'amount', 'cash_type', 'revenue_type_in', 'expense_type')
                    ->whereBetween('input_date', [$startDate . '%', $endDate . '%'])
                    ->orderBy('input_date', 'desc')
                    ->limit(10)
                    ->get();

            //Stock Flow Terbaru
            $depoStockFlows = StockFlow::leftJoin('depos', 'depo_id', '=', 'depos.id')
                    ->leftJoin('users', 'depos.user_id', '=', 'users.id')
                    ->leftJoin('products', 'product_id', '=', 'products.id')
                    ->select('users.name', 'products.name as product', 'input_date', 'qty', 'stock_type', 'stockin_category', 'stockout_category')
                    ->whereBetween('input_date', [$startDate . '%', $endDate . '%'])
                    ->orderBy('input_date', 'desc')
                    ->limit(10)
                    ->get();
        } else {
            $cashInCard = CashFlow::select('amount')
                    ->whereBetween('input_date', [$startDate . '%', $endDate . '%'])
                    ->where('revenue_type_in', '=', 'product_sales')
                    ->where('depo_id', '=', $depo->id)
                    ->get();

            //Cash Flow Terbaru
            $depoCashFlows = CashFlow::leftJoin('depos', 'depo_id', '=', 'depos.id')
                    ->leftJoin('users', 'depos.user_id', '=', 'users.id')
                    ->select('users.name', 'input_date', 'amount', 'cash_type', 'revenue_type_in', 'expense_type')
                    ->whereBetween('input_date', [$startDate . '%', $endDate . '%'])
                    ->where('depo_id', '=', $depo->id)
                    ->orderBy('input_date', 'desc')
                    ->limit(10)
                    ->get();

            //Stock Flow Terbaru
            $depoStockFlows = StockFlow::leftJoin('depos', 'depo_id', '=', 'depos.id')
                    ->leftJoin('users', 'depos.user_id', '=', 'users.id')
                    ->leftJoin('products', 'product_id', '=', 'products.id')
                    ->select('users.name', 'products.name as product', 'input_date', 'qty', 'stock_type', 'stockin_category', 'stockout_category')
                    ->whereBetween('input_date', [$startDate . '%', $endDate . '%'])
                    ->where('depo_id', '=', $depo->id)
                    ->orderBy('input_date', 'desc')
                    ->limit(10)
                    ->get();
        }

        //Cash Flow Terbaru
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

        $countCashIn = $cashInCard->count();
        $sumCashIn = round($cashInCard->sum('amount'), 0);
        $aveCashIn = 0;
        if ($countCashIn > 0) {
            if ($sumCashIn > 0 && $countCashIn > 0) {
                $aveCashIn = round($sumCashIn / $countCashIn, 0);
            } else {
                $aveCashIn = 0;
            }
        }
        
        $data = array(
            'dateRange' => $dateRange,
            'startDate' => $start,
            'endDate' => $end,
            'countCashIn' => $countCashIn,
            'sumCashIn' => rupiah($sumCashIn, TRUE),
            'avCashIn' => rupiah($aveCashIn, TRUE),
            'depoCashFlowNewDatas' => $depoCashFlowNewDatas,
            'depoStockFlowNewDatas' => $depoStockFlowNewDatas,
            'cashInLineCartData' => $cashInLineCartData,
            'cashOutLineCartData' => $cashOutLineCartData,
            'stockInLineCartData' => $stockInLineCartData,
            'stockOutLineCartData' => $stockOutLineCartData,
            'cashinCart' => $cashinCartData,
            'cashinCartDepo' => $cashinCartDepo,
            'cashoutCart' => $cashoutCartData,
            'cashoutCartDepo' => $cashoutCartDepo,
            'stockinCart' => $stockinCartData,
            'stockinCartDepo' => $stockinCartDepo,
            'stockoutCart' => $stockoutCartData,
            'stockoutCartDepo' => $stockoutCartDepo,
        );

        return view('index', compact('data'));
       
    }

    public function show($id)
    {
        return view('user.profile', [
            'user' => User::findOrFail($id)
        ]);
    }
}
