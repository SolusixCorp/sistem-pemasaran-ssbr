@extends('layouts.dashboard')

@section('title', 'Stock Flow')
@section('breadcrumb', 'Stock Flow')

@section('content')
    <div class="row">
        <div class="col-12" id="list-order">
            <div class="card mb-3">
                <div class="card-header">
                    <span class="pull-right">
                        <a class="btn btn-primary" href="{{ url('/stock/create') }}">
                            <i class="fas fa-plus-circle" aria-hidden="true"></i> Stock Flow
                        </a>
                    </span>
                    <h3><i class="fas fa-dolly-flatbed"></i> Data Stock Flow</h3>
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

                    <div class="row">
                        <div class="col-xs-12 col-md-12 col-lg-12 col-xl-12 mb-3">
                                <div id="reportrange" class="form-control col-3 pull-right" style="background: #fff; cursor: pointer; padding: 5px 10px; border: 1px solid #ccc; width: 100%">
                                    <i class="fas fa-calendar"></i>&nbsp;
                                    <span></span> <b class="caret"></b>
                                </div>  
                        </div>  
                    </div>

                    <div class="table-responsive">
                        <table id="dataTable" class="table table-bordered table-hover" style="width:100%">
                            <thead>
                                <tr>
                                    <th style="width:2%">No</th>
                                    <th>Tanggal</th>
                                    <th>Nama Depo</th>
                                    <th>Tipe Stok</th>
                                    <th>Kategori</th>
                                    <th>Qty</th>
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

    @include('pages/stock/details')
@endsection

@section('custom_js')
    <script>
        var table;
        var start = "<?php echo $data['startDate'] ?>";
        var end = "<?php echo $data['endDate']; ?>";

        $(function() {
            // Menampilkan data
            table = $('#dataTable').DataTable({
                data: dataSet,
                ajax: {
                    "url": "{{ url('/') }}" + "/stock/data/" + start + "/" + end,
                    "type": "GET"
                }
            });
        
        });

        // View Details
        function detailsView(date) {
            $.ajax({
                url: "{{ url('/') }}" + "/stock/date/" + date,
                type: "GET",
                dataType: "JSON",
                success: function(data) {
                    $('#modal-details').modal('show');
                    $('.modal-title').text('Detail Transaksi');
                    $('#vTanggalTransaksi').text(data.input_date);
                    $('#vDepoNama').text(data.depo);
                    $('#vStockType').text(data.type);
                    $('#vDesc').text(data.desc);

                    $('#details_product').empty();
                    for(var i = 0; i < data.products.length; i++) {
                        var no = i+1;
                        $('#details_product').append('<tr><th scope="row"> ' + no + ' </th><td>' + data.products[i].product_name + '</td><td>' + data.products[i].qty + '</td><td>' + data.products[i].remaining_stock + '</td></tr>');  
                    }
                    
                },
                error: function() {
                    alert('Tidak dapat menampilkan Data');
                }
            });
        }

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

            $('#reportrange span').html(startDate + ' - ' + endDate);

            $.ajax({
                url: "{{ url('/') }}" + "/stock/data/" + startDate + "/" + endDate,
                type: "GET",
                dataType: "JSON",
                success: function(data) {
                    table.ajax.url("{{ url('/') }}" + "/stock/data/" + startDate + "/" + endDate).load();
                },
                error: function() {
                    alert('Tidak dapat menampilkan Data');
                }
            });
            }
    </script>
@endsection