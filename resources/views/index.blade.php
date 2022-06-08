@extends('layouts.dashboard')

@section('title', 'Dashboard')
@section('breadcrumb', 'Dashboard')

@section('content')
    <div class="row">
        <div class="col-xs-12 col-md-12 col-lg-12 col-xl-12 mb-3">
                <div id="reportrange" class="form-control col-4 pull-right" style="background: #fff; cursor: pointer; padding: 5px 10px; border: 1px solid #ccc; width: 100%">
                    <i class="fas fa-calendar"></i>&nbsp;
                    <span></span> <b class="caret"></b>
                </div>  
        </div>  
    </div>

    <div class="row">

        <div class="col-xs-12 col-md-6 col-lg-6 col-xl-4">
            <div class="card-box noradius noborder bg-warning" id="count">
                <i class="fas fa-shopping-cart float-right text-white"></i>
                <h6 class="text-white text-uppercase m-b-20">Total Transaksi</h6>
                <h2 class="m-b-20 text-white"></h2>
                <span class="text-white">Hari Ini</span>
            </div>
        </div>

        <div class="col-xs-12 col-md-6 col-lg-6 col-xl-4">
            <div class="card-box noradius noborder bg-dark" id="sum">
                <i class="fas fa-wallet float-right text-white"></i>
                <h6 class="text-white text-uppercase m-b-20">Total Penjualan</h6>
                <h2 class="m-b-20 text-white"></h2>
                <span class="text-white">Hari Ini</span>
            </div>
        </div>

        <div class="col-xs-12 col-md-6 col-lg-6 col-xl-4">
            <div class="card-box noradius noborder bg-primary" id="average">
                <i class="fas fa-hand-holding-usd float-right text-white"></i>
                <h6 class="text-white text-uppercase m-b-20">Rata - rata Penjualan</h6>
                <h2 class="m-b-20 text-white"></h2>
                <span class="text-white">Hari Ini</span>
            </div>
        </div>
    </div>
    <!-- end row -->

    <div class="row">

        <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6 col-xl-6">
            <div class="card mb-3">
                <div class="card-header">
                    <h3><i class="fas fa-chart-pie"></i> Cash In per Depo (%)</h3>
                </div>

                <div class="card-body">
                    <canvas id="pieChartCashIn"></canvas>
                </div>
                <div class="card-footer small text-muted">Updated yesterday at 11:59 PM</div>
            </div>
            <!-- end card-->
        </div>

        <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6 col-xl-6">
            <div class="card mb-3">
                <div class="card-header">
                    <h3><i class="fas fa-chart-pie"></i> Cash Out per Depo (%)</h3>
                </div>

                <div class="card-body">
                    <canvas id="pieChartCashOut"></canvas>
                </div>
                <div class="card-footer small text-muted">Updated yesterday at 11:59 PM</div>
            </div>
            <!-- end card-->
        </div>
    </div>
    <!-- end row -->

    <div class="row">

        <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6 col-xl-6">
            <div class="card mb-3">
                <div class="card-header">
                    <h3><i class="fas fa-chart-pie"></i> Stock In per Depo (%)</h3>
                </div>

                <div class="card-body">
                    <canvas id="doughnutChartStockIn"></canvas>
                </div>
                <div class="card-footer small text-muted">Updated yesterday at 11:59 PM</div>
            </div>
            <!-- end card-->
        </div>

        <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6 col-xl-6">
            <div class="card mb-3">
                <div class="card-header">
                    <h3><i class="fas fa-chart-pie"></i> Stock Out per Depo (%)</h3>
                </div>

                <div class="card-body">
                    <canvas id="doughnutChartStockOut"></canvas>
                </div>
                <div class="card-footer small text-muted">Updated yesterday at 11:59 PM</div>
            </div>
            <!-- end card-->
        </div>

    </div>
    <!-- end row -->

    <div class="row">

        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-6 col-xl-6">
            <div class="card mb-3">
                <div class="card-header">
                    <h3><i class="fas fa-balance-scale"></i> Cash Flow Terbaru</h3>
                    Lorem ipsum dolor sit amet, consectetur adipiscing elit.
                </div>

                <div class="card-body">

                    <div class="widget-messages nicescroll" style="height: 400px;">
                        @foreach ($depoCashFlowNewDatas as $depoCashFlowNewData)
                        <a href="#">
                            <div class="message-item">
                                <p class="message-item-user">{{ $depoCashFlowNewData['depo_name'] }}</p>
                                <p class="message-item-msg">{{ $depoCashFlowNewData['desc'] }}</p>
                                <p class="message-item-date">{{ $depoCashFlowNewData['date'] }}</p>
                            </div>
                        </a>

                        @endforeach
                    </div>

                </div>
                <div class="card-footer small text-muted">Updated today at 11:59 PM</div>
            </div>
            <!-- end card-->
        </div>

        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-6 col-xl-6">
            <div class="card mb-3">
                <div class="card-header">
                    <h3><i class="fas fa-dolly-flatbed"></i> Stock Flow Terbaru</h3>
                    Lorem ipsum dolor sit amet, consectetur adipiscing elit.
                </div>

                <div class="card-body">

                    <div class="widget-messages nicescroll" style="height: 400px;">
                        @foreach ($depoStockFlowNewDatas as $depoStockFlowNewData)
                        <a href="#">
                            <div class="message-item">
                                <p class="message-item-user">{{ $depoStockFlowNewData['depo_name'] }}</p>
                                <p class="message-item-msg">{{ $depoStockFlowNewData['desc'] }}</p>
                                <p class="message-item-date">{{ $depoStockFlowNewData['date'] }}</p>
                            </div>
                        </a>
                        @endforeach
                    </div>

                </div>
                <div class="card-footer small text-muted">Updated today at 11:59 PM</div>
            </div>
            <!-- end card-->
        </div>
    </div>

@endsection

@section('custom_js')
    <script>
        var start = moment().subtract(6, 'days');
        var end = moment();
        
        $(document).on('ready', function() {

            $('#reportrange').daterangepicker({
                onSelect: function(dateText) {
                    console.log("Selected date: " + dateText + "; input's current value: " + this.value);
                },
                change: function() {
                    alert('date has changed!');
                }, 
                ranges: {
                    'Hari Ini': [moment(), moment()],
                    'Kemarin': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                    '7 Hari Lalu': [moment().subtract(6, 'days'), moment()],
                    '30 hari Lalu': [moment().subtract(29, 'days'), moment()],
                    'Bulan Ini': [moment().startOf('month'), moment().endOf('month')],
                    'Bulan Lalu': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1,
                        'month').endOf('month')]
                }
            }, cb)

            cb(start, end);

            function cb(start, end) {
                $('#reportrange span')
                .html(start.format('YYYY-MM-DD') + ' - ' + end.format('YYYY-MM-DD'));
                showFilterData(start, end);
            }

            // counter-up
            $('.counter').counterUp({
                delay: 10,
                time: 600
            });

            showFilterData(start, end);

        });

        // Form Edit Pengeluaran
        function showFilterData(start, end) {
            var startDate = start.format('YYYY-MM-D');
            var endDate = end.format('YYYY-MM-D');
            url = "{{ url('/') }}" + "/home/" + startDate + "/" + endDate;
            $.ajax({
                url: url,
                type: "GET",
                dataType: "JSON",
                success: function(data) {
                    $('#sum h2').html(data.sumData.sum)
                    $('#sum span').html(data.sumData.date)
                    $('#count h2').html(data.countData.count)
                    $('#count span').html(data.countData.date)
                    $('#average h2').html(data.averageData.average)
                    $('#average span').html(data.averageData.date)
                },
                error: function() {
                    alert('Tidak dapat menampilkan Data');
                }
            });
        }

        // pieChart Cash In
        var ctx_pie_chart = document.getElementById("pieChartCashIn").getContext('2d');
        var pieChart = new Chart(ctx_pie_chart, {
            type: 'pie',
            data: {
                    datasets: [{
                        data: <?php echo $cashInCartsData; ?>,
                        backgroundColor: [
                            'rgba(255,99,132,1)',
                            'rgba(54, 162, 235, 1)',
                            'rgba(255, 206, 86, 1)',
                            'rgba(75, 192, 192, 1)',
                            'rgba(153, 102, 255, 1)',
                            'rgba(255, 159, 64, 1)'
                        ],
                        label: 'Dataset 1'
                    }],
                    labels: <?php echo $depoCartsCashInData; ?>
                },
                options: {
                    responsive: true
                }
        
        });

        // pieChart Cash Out
        var ctx_pie_chart = document.getElementById("pieChartCashOut").getContext('2d');
        var pieChart = new Chart(ctx_pie_chart, {
            type: 'pie',
            data: {
                    datasets: [{
                        data: <?php echo $cashOutCartsData; ?>,
                        backgroundColor: [
                            'rgba(255,99,132,1)',
                            'rgba(54, 162, 235, 1)',
                            'rgba(255, 206, 86, 1)',
                            'rgba(75, 192, 192, 1)',
                            'rgba(153, 102, 255, 1)',
                            'rgba(255, 159, 64, 1)'
                        ],
                        label: 'Dataset 1'
                    }],
                    labels: <?php echo $depoCartsCashOutData; ?>
                },
                options: {
                    responsive: true
                }
        
        });

        // doughnutChart Stock In
        var ctx_doughnut_chart = document.getElementById("doughnutChartStockIn").getContext('2d');
        var doughnutChart = new Chart(ctx_doughnut_chart, {
            type: 'doughnut',
            data: {
                    datasets: [{
                        data: <?php echo $stockInCartsData ?>,
                        backgroundColor: [
                            'rgba(255,99,132,1)',
                            'rgba(54, 162, 235, 1)',
                            'rgba(255, 206, 86, 1)',
                            'rgba(75, 192, 192, 1)',
                            'rgba(153, 102, 255, 1)',
                            'rgba(255, 159, 64, 1)'
                        ],
                        label: 'Dataset 1'
                    }],
                    labels: <?php echo $depoCartsStockInData ?>
                },
                options: {
                    responsive: true
                }
        
        });

        // doughnutChart Stock In
        var ctx_doughnut_chart = document.getElementById("doughnutChartStockOut").getContext('2d');
        var doughnutChart = new Chart(ctx_doughnut_chart, {
            type: 'doughnut',
            data: {
                    datasets: [{
                        data: <?php echo $stockOutCartsData ?>,
                        backgroundColor: [
                            'rgba(255,99,132,1)',
                            'rgba(54, 162, 235, 1)',
                            'rgba(255, 206, 86, 1)',
                            'rgba(75, 192, 192, 1)',
                            'rgba(153, 102, 255, 1)',
                            'rgba(255, 159, 64, 1)'
                        ],
                        label: 'Dataset 1'
                    }],
                    labels: <?php echo $depoCartsStockOutData ?>
                },
                options: {
                    responsive: true
                }
        
        });

    </script>

@endsection