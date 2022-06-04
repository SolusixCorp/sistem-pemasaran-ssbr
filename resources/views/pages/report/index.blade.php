@extends('layouts.dashboard')

@section('title', 'Report')
@section('breadcrumb', 'Report')

@section('content')
    <div class="row">

        <div class="col-12" id="list-report">
            <div class="card mb-3">
                <div class="card-header">
                    <span class="pull-right">
                        <a class="btn btn-primary" href="{{ url('/report/create') }}">
                            <i class="fas fa-plus-circle" aria-hidden="true"></i> Report
                        </a>
                    </span>                   
                    <h3><i class="fas fa-chart-line"></i> Data Report</h3>
                    <br>
                    <!-- <div id="reportrange" class="form-control col-4 pull-right" style="background: #fff; cursor: pointer; padding: 5px 10px; border: 1px solid #ccc; width: 100%">
                        <i class="fas fa-calendar"></i>&nbsp;
                        <span></span> <b class="caret"></b>
                    </div> -->
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
                                    <th>Depo</th>
                                    <th>Tipe Pembayaran</th>
                                    <th>Keterangan</th>
                                    <th>Total</th>
                                    <th style="width:10%">Aksi</th>
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
        // Menampilkan data Report
        table = $('#dataTable').DataTable({
            data: dataSet,
            ajax: {
                "url": "/report/" + start.format('YYYY-MM-D') + "/" + end.format('YYYY-MM-D'),
                "type": "GET"
            }
        });

        // Single Date Picker
        $('input[name="singledatepicker"]').daterangepicker({
            singleDatePicker: true,
            showDropdowns: true
        });
        
        $('#reportrange').daterangepicker({
            startDate: start,
            endDate: end,
            ranges: {
                'Today': [moment(), moment()],
                'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                'Last 7 Days': [moment().subtract(6, 'days'), moment()],
                'Last 30 Days': [moment().subtract(29, 'days'), moment()],
                'This Month': [moment().startOf('month'), moment().endOf('month')],
                'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1,
                    'month').endOf('month')]
            }
        }, cb);

        cb(start, end);

        function cb(start, end) {
            startDate = start;
            endDate = end;
            $('#reportrange span')
            .html(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'));
            table.ajax.url("/report/" + start.format('YYYY-MM-D') + "/" + end.format('YYYY-MM-D')).load();          
        }

    });
    
    </script>
@endsection
