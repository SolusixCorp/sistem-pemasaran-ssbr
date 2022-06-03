@extends('layouts.dashboard')

@section('title', 'Cash Flow')
@section('breadcrumb', 'Cash Flow')

@section('content')
    <div class="row">
        <div class="col-12" id="list-order">
            <div class="card mb-3">
                <div class="card-header">
                    <span class="pull-right">
                        <a class="btn btn-primary" href="{{ url('/cashflow/create') }}">
                            <i class="fas fa-plus-circle" aria-hidden="true"></i> Cash Flow
                        </a>
                    </span>
                    <h3><i class="fas fa-balance-scale"></i> Data Cash Flow</h3>
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
                                    <th style="width:17%">Tanggal</th>
                                    <th>Nama Depo</th>
                                    <th style="width:13%">Tipe Kas</th>
                                    <th style="width:17%">Keterangan</th>
                                    <th style="width:15%">Total</th>
                                    <th style="width:13%">Aksi</th>
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

    @include('pages/cashflow/details')
@endsection

@section('custom_js')
    <script>
        var table;
        $(function() {
            // Menampilkan data kategori
            table = $('#dataTable').DataTable({
                data: dataSet,
                ajax: {
                    "url": "{{ route('cashflow.data') }}",
                    "type": "GET"
                }
            });
        
        });

        // View Details Barang
        function detailsView(id) {
            $.ajax({
                url: "{{ url('/') }}" + "/cashflow/" + id,
                type: "GET",
                dataType: "JSON",
                success: function(data) {
                    console.log(data)
                    $('#modal-details').modal('show');
                    $('.modal-title').text('Details Cash Flow');
                    $('#vTanngalInput').text(data.input_date);
                    $('#vDepoName').text(data.depo);
                    $('#vCashType').text(data.type);
                    $('#vCategory').text(data.category);
                    $('#vNotes').text(data.notes);
                    $('#vTotal').text(data.total);

                    for(var i = 0; i < data.order_items.length; i++) {
                        $('#data-detail-modal').append('<div class="message-item"><h6 id="vItems" class="message-item-user message-item-date" style="position:initial !important">' + data.order_items[i].barang.name  + ' => ' + data.order_items[i].qty + ' '  + ' x ' + data.order_items[i].barang.selling_price + ' = ' + data.order_items[i].sub_total + '</h6></div>');  
                    }
                    
                    var base_url = "{{ url('/') }}";
                    $('#data-detail-modal').append('<a href="'+ base_url +'/cashflow/print-invoice/' + data.id + '" class="btn btn-primary pull-right">Cetak Invoice</a>');
                    
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