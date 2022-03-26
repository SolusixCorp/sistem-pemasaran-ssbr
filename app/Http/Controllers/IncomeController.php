<?php


namespace App\Http\Controllers;

// require '../../vendor/autoload.php';
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Models\Income;
use App\Models\Item;
use PDF;
use Mike42\Escpos\Printer;
use Mike42\Escpos\PrintConnectors\WindowsPrintConnector;
use Mike42\Escpos\EscposImage;
use Mike42\Escpos\PrintConnectors\FilePrintConnector;
class IncomeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function index()
    {
        //
        $currentDate = Carbon::now()->format('Y-m-d');
        $start= $currentDate;
        $end = $currentDate;
        return view('pages.report.income.index', compact('start', 'end'));
    }

    public function exportPdf($start, $end) {
        $currentDate = Carbon::now()->format('Y-m-d');

        $startDate = date($start);
        $endDate = date($end);
        if ($start == $end) {
            $dateRange = $endDate;
            $incomes = Income::groupBy('date')
            ->orderBy('date', 'desc')
            ->where('date', $start)
            ->get();

            $incomesTotal = Income::groupBy('date')
            ->orderBy('date', 'desc')
            ->where('date', $start)
            ->get()
            ->sum('income');
        } else {
            $dateRange = $startDate . " s/d " .  $endDate;
            $incomes = Income::groupBy('date')
            ->orderBy('date', 'desc')
            ->whereBetween('date', [$startDate, $endDate])
            ->get();

            $incomesTotal = Income::groupBy('date')
            ->orderBy('date', 'desc')
            ->whereBetween('date', [$startDate, $endDate])
            ->get()
            ->sum('income');
        }

        $pdf = PDF::loadView('pages.report.income.export-pdf', compact('incomes', 'dateRange', 'incomesTotal'));
        $pdf->setPaper('a4', 'potrait');
        return $pdf->stream();
    }

    public function downloadPdf($start, $end) {
        $currentDate = Carbon::now()->format('Y-m-d');

        $startDate = date($start);
        $endDate = date($end);
        if ($start == $end) {
            $dateRange = $endDate;
            $incomes = Income::groupBy('date')
            ->orderBy('date', 'desc')
            ->where('date', $start)
            ->get();

            $incomesTotal = Income::groupBy('date')
            ->orderBy('date', 'desc')
            ->where('date', $start)
            ->get()
            ->sum('income');
        } else {
            $dateRange = $startDate . " s/d " .  $endDate;
            $incomes = Income::groupBy('date')
            ->orderBy('date', 'desc')
            ->whereBetween('date', [$startDate, $endDate])
            ->get();

            $incomesTotal = Income::groupBy('date')
            ->orderBy('date', 'desc')
            ->whereBetween('date', [$startDate, $endDate])
            ->get()
            ->sum('income');
        }

        $pdf = PDF::loadView('pages.report.income.export-pdf', compact('incomes', 'dateRange', 'incomesTotal'));
        $pdf->setPaper('a4', 'potrait');
        return $pdf->download('laporan_' . $startDate . "_" . $endDate . ".pdf");
    }

    public function listData($start, $end) {
        $currentDate = Carbon::now()->format('Y-m-d');
        $startDate = date($start);
        $endtDate = date($end);
        if ($start == $end) {
            $incomes = Income::groupBy('date')
            ->orderBy('date', 'desc')
            ->where('date', $start)
            ->get();
        } else {
            $incomes = Income::groupBy('date')
            ->orderBy('date', 'desc')
            ->whereBetween('date', [$startDate, $endtDate])
            ->get();
        }
        $no = 0;
        $status = "";
        $data = array();
        foreach ($incomes as $income) {
            $no++;
            $row = array();
            $row[] = $no;
            $row[] = tanggal($income->date);
            $row[] = rupiah($income->item_expense, TRUE);
            $row[] = rupiah($income->total, TRUE);
            $row[] = rupiah($income->income, TRUE);
            $data[] = $row;
        }

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

    public function printReceipt() {
        try {
            // Enter the share name for your USB printer here
            $connector = new WindowsPrintConnector("Receipt Printer");
            $printer = new Printer($connector);
        
            /* Print a "Hello world" receipt" */
            $printer -> text("Hello World!\n");
            $printer -> cut();
            
            /* Close printer */
            $printer -> close();
            
        } catch(Exception $e) {
            echo "Couldn't print to this printer: " . $e -> getMessage() . "\n";
        }
    }
}
