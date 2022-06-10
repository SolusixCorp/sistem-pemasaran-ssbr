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
        $startDate = Carbon::now()->format('Y-m-d');

        return $this->data($startDate, $endDate);
    }

    public function data($start, $end)
    {
        $startDate = date('Y-m-d', strtotime($start));
        $endDate = date('Y-m-d', strtotime($end . "+1 days"));

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
            $cashin[] = round(($cashIn->totals / $totCashIn) * 100, 2);
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
            $cashout[] = round(($cashOut->totals / $totCashOut) * 100, 2);
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
            $stockin[] = round(($stockIn->totals / $totStockIn) * 100, 2);
            $stockin_depo[] = $cashIn->name;
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
            $stockout[] = round(($stockOut->totals / $totStockOut) * 100, 2);
            $stockout_depo[] = $stockOut->name;
        }

        $stockoutCartData = json_encode($stockout);
        $stockoutCartDepo = json_encode($stockout_depo);

        //Cash Flow Terbaru
        $depoCashFlows = CashFlow::leftJoin('depos', 'depo_id', '=', 'depos.id')
                ->leftJoin('users', 'depos.user_id', '=', 'users.id')
                ->select('users.name', 'input_date', 'amount', 'cash_type', 'revenue_type_in', 'expense_type')
                ->whereBetween('input_date', [$startDate . '%', $endDate . '%'])
                ->orderBy('input_date', 'desc')
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
                ->orderBy('input_date', 'desc')
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
   
        $dateRange = tanggal($startDate) . ' - ' . tanggal(date('Y-m-d', strtotime($end)));
        
        $cashInCard = CashFlow::select('amount')
                    ->whereBetween('input_date', [$startDate . '%', $endDate . '%'])
                    ->where('revenue_type_in', '=', 'product_sales')
                    ->get();

        $countCashIn = $cashInCard->count();
        $sumCashIn = round($cashInCard->sum('amount'), 0);
        $aveCashIn = 0;
        if ($countCashIn > 0) {
            $aveCashIn = round($sumCashIn / $countCashIn, 0);
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
