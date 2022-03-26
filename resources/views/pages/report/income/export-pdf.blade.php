
<!DOCTYPE html>
<html lang="en">

<head>
    <title>Laporan Pendapatan</title>
    <meta name="description" content="Dashboard | Nura Admin">
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="author" content="Your website">
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" rel="stylesheet"/>
    <link href="{{ asset('css/bootstrap-pdf.css') }}" rel="stylesheet" type="text/css"/>
    <!-- <link href="{{ asset('nura-admin/assets/css/bootstrap.min.css') }}" rel="stylesheet" type="text/css" /> -->

    <script src="{{ asset('js/bootstrap.min.js') }}"><script>
</head>

<body class="adminbody">
    <div id="main">
    <?php echo asset('css/bootstrap.min.css'); ?>
        <div class="content-page">

            <!-- Start content -->
            <div class="content">
                <div class="container-fluid">
                    <div class="row">

                        <div class="col-12" id="list-income">
                            <div class="card mb-3">
                                <div class="card-body">
                                <div class="text-center large">LAPORAN PENDAPATAN </div>
                                <div class="text-center small">Per Tanggal {{ tanggal($dateRange) }}</div>
                                    <br>
                                    <div class="table-responsive">
                                        <table id="dataTable" class="table table-bordered small" style="width:100%">
                                            <thead>
                                                <tr>
                                                    <th class="text-center">TANGGAL</th>
                                                    <th class="text-center">PENJUALAN</th>
                                                    <th class="text-center">PEMBELIAN</th>
                                                    <th class="text-center">PENDAPATAN</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($incomes as $income)
                                                <tr>
                                                    <td class="text-center">{{ tanggal($income->date) }}</td>
                                                    <td class="text-right">{{ rupiah($income->item_expense, FALSE) }}</td>
                                                    <td class="text-right">{{ rupiah($income->total, FALSE) }}</td>
                                                    <td class="text-right">{{ rupiah($income->income, FALSE) }}</td>
                                                </tr>
                                                @endforeach
                                                <tr>
                                                    <td colspan="3" class="text-center"><b>TOTAL PENDAPATAN</b></td>
                                                    <td class="text-right"><b>{{ rupiah($incomesTotal, FALSE) }}</b></td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                    <!-- end table-responsive-->

                                </div>
                                <!-- end card-body-->
                            </div>
                            <!-- end card-->
                        </div>
                    
                    </div>
                    <!-- end row-->
                </div>
                <!-- END content-page -->
            </div>
        </div>
    </div>
</body>

</html>

