@extends('layouts.dashboard')

@section('title', 'Pendapatan')
@section('breadcrumb', 'Pendapatan')

@section('content')
    <div class="row">

        <div class="col-12" id="list-income">
            <div class="card mb-3">
                <div class="card-header">
                    <div class="button-list pull-right">
                        <span id="download" ><a class="btn btn-dark" ><i class="fas fa-download" aria-hidden="true"></i> Download PDF</a></span> 
                        <span id="export" ><a class="btn btn-primary" ><i class="fas fa-sign-out-alt" aria-hidden="true"></i> Export PDF</a></span>
                        <!-- <span id="print" ><a class="btn btn-info" ><i class="fas fa-sign-out-alt" aria-hidden="true"></i> Print Receipt</a></span> -->
                    </div>
                    <h3><i class="fas fa-hand-holding-usd"></i> Laporan Pendapatan</h3>
                    <br><br>
                    <div id="reportrange" class="form-control col-4 pull-right" style="background: #fff; cursor: pointer; padding: 5px 10px; border: 1px solid #ccc; width: 100%">
                        <i class="fas fa-calendar"></i>&nbsp;
                        <span></span> <b class="caret"></b>
                    </div>
                    
                </div>
                <div class="card-body">
                    @if(Session::has('success_message'))
                        <div class="alert alert-success alert-dismissable flat" style="margin-left: 0px;">
                            <i class="fa fa-check"></i>
                            {{ Session::get('success_message') }}
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    @endif
                    @if(Session::has('failed_message'))
                        <div class="alert alert-danger alert-dismissable flat" style="margin-left: 0px;">
                            <i class="fa fa-check"></i>
                            {{ Session::get('failed_message') }}
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    @endif
                    <div class="table-responsive">
                        <table id="dataTable" class="table table-bordered table-hover" style="width:100%">
                            <thead>
                                <tr>
                                    <th style="width:2%">No</th>
                                    <th>Tanggal</th>
                                    <th>Total Pembelian</th>
                                    <th>Total Penjualan</th>
                                    <th>Pendapatan</th>
                                </tr>
                            </thead>
                            <tbody>
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
@endsection

@section('custom_js')
    <script>
    var table;
    var start = moment();
    var end = moment();
    var startDate, endDate;
    $(function() {
        // Menampilkan data Pemasukan
        table = $('#dataTable').DataTable({
            data: dataSet,
            columns: [{
                title: "No"
            }, {
                title: "Tanggal"
            }, {
                title: "Total Pembelian"
            }, {
                title: "Total Penjualan"
            }, {
                title: "Total Pendapatan"
            }],
            ajax: {
                "url": "{{ url('/') }}" + "/    income/" + start.format('YYYY-MM-D') + "/" + end.format('YYYY-MM-D'),
                "type": "GET"
            }
        });

        $('#reportrange').daterangepicker({
            startDate: start,
            endDate: end,
            ranges: {
                'Hari Ini': [moment(), moment()],
                'Kemarin': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                '7 Hari Lalu': [moment().subtract(6, 'days'), moment()],
                '30 hari Lalu': [moment().subtract(29, 'days'), moment()],
                'Bulan Ini': [moment().startOf('month'), moment().endOf('month')],
                'Bulan Lalu': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1,
                    'month').endOf('month')]
            }
        }, cb);

        cb(start, end);

        function cb(start, end) {
            startDate = start;
            endDate = end;
            $('#reportrange span')
            .html(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'));
            table.ajax.url("{{ url('/') }}" +"/income/" + start.format('YYYY-MM-D') + "/" + end.format('YYYY-MM-D')).load();          
        }

    });


    $( "#export" ).click(function() {
        // alert( "Handler for .click() called." );
        url = "{{ url('/') }}" + "/income/export/" + startDate.format('YYYY-MM-D') + "/" + endDate.format('YYYY-MM-D');
        window.open(url, '_blank');
    
    });

    $( "#print" ).click(function() {
        // alert( "Handler for .click() called." );
        url =  "{{ url('/') }}" + "/income/print/";
        window.open(url, '_blank');
    });

    $( "#download" ).click(function() {
        // alert( "Handler for .click() called." );
        url = "{{ url('/') }}" +"/income/download/" + startDate.format('YYYY-MM-D') + "/" + endDate.format('YYYY-MM-D');
        window.open(url, '_blank');
    
    });

    </script>
@endsection
