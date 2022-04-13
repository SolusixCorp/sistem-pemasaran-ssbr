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
                                    <th>Nama Pembeli</th>
                                    <th>Tanggal Transaksi</th>
                                    <th>Grand Total</th>
                                    <th style="width:15%">Aksi</th>
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

        // View Details Barang
        function detailsView(id) {
            $.ajax({
                url: "{{ url('/') }}" + "/stock/" + id,
                type: "GET",
                dataType: "JSON",
                success: function(data) {
                    console.log(data)
                    $('#modal-details-barang').modal('show');
                    $('.modal-title').text('Detail Transaksi');
                    $('#vKodeTransaksi').text("#TRXAPP000" + data.id);
                    $('#vTanggalTransaksi').text(data.order_date);
                    $('#vNamaPembeli').text(data.customer.customer_name);
                    $('#vTotal').text(convertToRupiah(data.total));
                    $('#vDiscountPercentage').text(data.discount_percentage);
                    $('#vDiscountRp').text(convertToRupiah(data.discount_rp));
                    $('#vTotalWithDiscount').text(convertToRupiah(data.total_with_discount));
                    $('#vBayar').text(convertToRupiah(data.bayar));
                    $('#vKembalian').text(convertToRupiah(data.kembalian));
                    $('#vCatatan').text(data.notes);

                    for(var i = 0; i < data.order_items.length; i++) {
                        $('#data-detail-modal').append('<div class="message-item"><h6 id="vItems" class="message-item-user message-item-date" style="position:initial !important">' + data.order_items[i].barang.name  + ' => ' + data.order_items[i].qty + ' '  + ' x ' + data.order_items[i].barang.selling_price + ' = ' + data.order_items[i].sub_total + '</h6></div>');  
                    }
                    
                    var base_url = "{{ url('/') }}";
                    $('#data-detail-modal').append('<a href="'+ base_url +'/stock/print-invoice/' + data.id + '" class="btn btn-primary pull-right">Cetak Invoice</a>');
                    
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