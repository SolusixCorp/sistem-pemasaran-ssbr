@extends('layouts.dashboard')

@section('title', 'Dashboard')
@section('breadcrumb', 'Dashboard')

@section('content')
    <div class="row">
        <div class="col-xs-12 col-md-12 col-lg-12 col-xl-12 mb-3">
                <div id="reportrange" class="form-control col-3 pull-right" style="background: #fff; cursor: pointer; padding: 5px 10px; border: 1px solid #ccc; width: 100%">
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
                <h4 class="m-b-20 text-white"></h4>
                <span class="text-white">Hari Ini</span>
            </div>
        </div>

        <div class="col-xs-12 col-md-6 col-lg-6 col-xl-4">
            <div class="card-box noradius noborder bg-dark" id="sum">
                <i class="fas fa-wallet float-right text-white"></i>
                <h6 class="text-white text-uppercase m-b-20">Total Penjualan</h6>
                <h4 class="m-b-20 text-white"></h4>
                <span class="text-white">Hari Ini</span>
            </div>
        </div>

        <div class="col-xs-12 col-md-6 col-lg-6 col-xl-4">
            <div class="card-box noradius noborder bg-primary" id="average">
                <i class="fas fa-hand-holding-usd float-right text-white"></i>
                <h6 class="text-white text-uppercase m-b-20">Rata - rata Penjualan</h6>
                <h4 class="m-b-20 text-white"></h4>
                <span class="text-white">Hari Ini</span>
            </div>
        </div>
    </div>
    <!-- end row -->

    @if (Auth::user()->role == 'depo')
    <div class="row">

        <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6 col-xl-6">
            <div class="card mb-3">
                <div class="card-header">
                    <h3><i class="fas fa-chart-bar"></i> CashFlow (Rupiah)</h3>
                </div>

                <div class="card-body">
                    <canvas id="comboBarLineChartCashFlow"></canvas>
                </div>
                <div class="card-footer small text-muted"></div>
            </div>
            <!-- end card-->
        </div>

        <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6 col-xl-6">
            <div class="card mb-3">
                <div class="card-header">
                    <h3><i class="fas fa-chart-bar"></i> Stock Flow (Quantity)</h3>
                </div>

                <div class="card-body">
                    <canvas id="comboBarLineChartStockFlow"></canvas>
                </div>
                <div class="card-footer small text-muted"></div>
            </div>
            <!-- end card-->
        </div>

    </div>

    @else
    <div class="row">

        <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6 col-xl-6">
            <div class="card mb-3">
                <div class="card-header">
                    <h3><i class="fas fa-chart-pie"></i> Cash In per Depo (%)</h3>
                </div>

                <div class="card-body">
                    <canvas id="pieChartCashIn"></canvas>
                </div>
                <div class="card-footer small text-muted"></div>
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
                <div class="card-footer small text-muted"></div>
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
                <div class="card-footer small text-muted"></div>
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
                <div class="card-footer small text-muted"></div>
            </div>
            <!-- end card-->
        </div>

    </div>
    <!-- end row -->
    @endif

    <div class="row">

        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-6 col-xl-6">
            <div class="card mb-3">
                <div class="card-header">
                    <h3><i class="fas fa-balance-scale"></i> Cash Flow Terbaru</h3>
                </div>

                <div class="card-body">

                    <div class="widget-messages nicescroll" style="height: 400px;">
                        @foreach ($data['depoCashFlowNewDatas'] as $depoCashFlowNewData)
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
                <div class="card-footer small text-muted"></div>
            </div>
            <!-- end card-->
        </div>

        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-6 col-xl-6">
            <div class="card mb-3">
                <div class="card-header">
                    <h3><i class="fas fa-dolly-flatbed"></i> Stock Flow Terbaru</h3>
                </div>

                <div class="card-body">

                    <div class="widget-messages nicescroll" style="height: 400px;">
                        @foreach ($data['depoStockFlowNewDatas'] as $depoStockFlowNewData)
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
        
        var start = "<?php echo $data['startDate'] ?>";
        var end = "<?php echo $data['endDate']; ?>";
        var dateRange = "<?php echo $data['dateRange']; ?>";

        var trxCount = <?php echo $data['countCashIn']; ?>;
        var trxSum = "<?php echo $data['sumCashIn']; ?>";
        var trxAv = "<?php echo $data['avCashIn']; ?>";

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
            }, filter)

            $('#reportrange span').html(start + ' - ' + end);
            
            function filter(start, end) {
                showFilterData(start, end);
            }

            // counter-up
            $('.counter').counterUp({
                delay: 10,
                time: 600
            });

        });

        function showFilterData(start, end) {
            var startDate = start.format('YYYY-MM-DD');
            var endDate = end.format('YYYY-MM-DD');
            window.location = "{{ url('/') }}" + "/home/" + startDate + "/" + endDate;
        }

        $('#count h4').html(trxCount);
        $('#count span').html(dateRange);
        $('#sum h4').html(trxSum);
        $('#sum span').html(dateRange);
        $('#average h4').html(trxAv);
        $('#average span').html(dateRange);
        $('.card-footer').html(dateRange);

        var role = "<?php echo Auth::user()->role ?>";
        if (role == 'depo') {
            // comboBarLineChartCashFlow
            var dataCashInLine = <?php echo $data['cashInLineCartData']; ?>;
            var dataCashOutLine = <?php echo $data['cashOutLineCartData']; ?>;
            
            var ctx_combo_bar = document.getElementById("comboBarLineChartCashFlow").getContext('2d');
            var comboBarLineChart = new Chart(ctx_combo_bar, {
                    type: 'bar',
                    data: {
                        labels: ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"],
                        datasets: [{
                                type: 'bar',
                                label: 'Cash IN',
                                backgroundColor: '#FF6B8A',
                                data: dataCashInLine,
                                borderColor: 'white',
                                borderWidth: 0
                            }, {
                                type: 'bar',
                                label: 'Cash OUT',
                                backgroundColor: '#059BFF',
                                data: dataCashOutLine,
                            }], 
                            borderWidth: 1
                    },
                    options: {
                        scales: {
                            yAxes: [{
                                ticks: {
                                    beginAtZero:true
                                }
                            }]
                        }
                    }
            });	

            // comboBarLineChartStockFlow
            var dataStockInLine = <?php echo $data['stockInLineCartData']; ?>;
            var dataStockOutLine = <?php echo $data['stockOutLineCartData']; ?>;

            var ctx_combo_bar = document.getElementById("comboBarLineChartStockFlow").getContext('2d');
            var comboBarLineChart = new Chart(ctx_combo_bar, {
                    type: 'bar',
                    data: {
                        labels: ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"],
                        datasets: [{
                                type: 'bar',
                                label: 'Stock IN',
                                backgroundColor: '#FF6B8A',
                                data: dataStockInLine,
                                borderColor: 'white',
                                borderWidth: 0
                            }, {
                                type: 'bar',
                                label: 'Stock OUT',
                                backgroundColor: '#059BFF',
                                data: dataStockOutLine,
                            }], 
                            borderWidth: 1
                    },
                    options: {
                        scales: {
                            yAxes: [{
                                ticks: {
                                    beginAtZero:true
                                }
                            }]
                        }
                    }
            });	

        } else {

            // pieChart Cash In
            var dataCashIn = <?php echo $data['cashinCart']; ?>;
            var depoCashIn = <?php echo $data['cashinCartDepo']; ?>;

            var ctx_pie_chart = document.getElementById("pieChartCashIn").getContext('2d');
            var pieChart = new Chart(ctx_pie_chart, {
                type: 'pie',
                data: {
                        datasets: [{
                            data: dataCashIn,
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
                        labels: depoCashIn
                    },
                    options: {
                        responsive: true
                    }
            
            });
        
            // pieChart Cash Out
            var dataCashOut = <?php echo $data['cashoutCart']; ?>;
            var depoCashOut = <?php echo $data['cashoutCartDepo']; ?>;

            var ctx_pie_chart = document.getElementById("pieChartCashOut").getContext('2d');
            var pieChart = new Chart(ctx_pie_chart, {
                type: 'pie',
                data: {
                        datasets: [{
                            data: dataCashOut,
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
                        labels: depoCashOut
                    },
                    options: {
                        responsive: true
                    }
            
            });

            // doughnutChart Stock In
            var dataStockIn = <?php echo $data['stockinCart']; ?>;
            var depoStockIn = <?php echo $data['stockinCartDepo']; ?>;

            var ctx_doughnut_chart = document.getElementById("doughnutChartStockIn").getContext('2d');
            var doughnutChart = new Chart(ctx_doughnut_chart, {
                type: 'doughnut',
                data: {
                        datasets: [{
                            data: dataStockIn,
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
                        labels: depoStockIn
                    },
                    options: {
                        responsive: true
                    }
            
            });

            // doughnutChart Stock Out
            var dataStockOut = <?php echo $data['stockoutCart']; ?>;
            var depoStockOut = <?php echo $data['stockoutCartDepo']; ?>;
            
            var ctx_doughnut_chart = document.getElementById("doughnutChartStockOut").getContext('2d');
            var doughnutChart = new Chart(ctx_doughnut_chart, {
                type: 'doughnut',
                data: {
                        datasets: [{
                            data: dataStockOut,
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
                        labels: depoStockOut
                    },
                    options: {
                        responsive: true
                    }
            
            });
        }

    </script>

@endsection