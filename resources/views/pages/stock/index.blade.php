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
        $(function() {
            // Menampilkan data kategori
            table = $('#dataTable').DataTable({
                data: dataSet,
                ajax: {
                    "url": "{{ route('stock.data') }}",
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
                    
                    // var base_url = "{{ url('/') }}";
                    // $('#data-detail-modal').append('<a href="'+ base_url +'/stock/print-invoice/' + data.id + '" class="btn btn-primary pull-right">Cetak Invoice</a>');
                    
                },
                error: function() {
                    alert('Tidak dapat menampilkan Data');
                }
            });
        }

        function convertToRupiah(angka)
        {
            var rupiah = '';		
            var angkarev = angka.toString().split('').reverse().join('');
            for(var i = 0; i < angkarev.length; i++) if(i%3 == 0) rupiah += angkarev.substr(i,3)+'.';
            return 'Rp. '+rupiah.split('',rupiah.length-1).reverse().join('');
        }
    </script>
@endsection