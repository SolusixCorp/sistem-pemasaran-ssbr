@extends('layouts.dashboard')

@section('title', 'Data Supply')
@section('breadcrumb', 'Supply')

@section('content')
    <div class="row">
        <div class="col-12" id="list-barang">
            <div class="card mb-3">
                <div class="card-header">
                    <span class="pull-right">
                        <button class="btn btn-primary" data-toggle="modal" data-target="#modal-add">
                            <i class="fas fa-plus" aria-hidden="true"></i> Pembelian Baru
                        </button>
                    </span>
                    <h3><i class="fas fa-cubes"></i> Data Supply</h3>
                    @include('pages/supply/add')
                    @include('pages/supply/edit')
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
                                    <th>Tanggal</th>
                                    <th>Nama Supplier</th>
                                    <th>Nama Produk</th>
                                    <th>Qty</th>
                                    <th>Total</th>
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
    @include('pages/supply/details')
@endsection

@section('custom_css')
    <link href="{{ asset('css/select2.css') }}" rel="stylesheet"/>
    <style>
        .modal-dialog {
            max-width: 700px !important;
        }
        /* .select2-container .select2-selection--single .select2-selection__rendered {
            width: 500px;
        } */
    </style>
@endsection

@section('custom_js')
    
    <script src="{{ asset('js/select2.js') }}"></script>
    <script>
        // $(document).ready(function(){
        //     $('#inputBarang').select2({
        //         theme:'bootstrap4',
        //         // tags:true,
        //     }).on('select2:close', function(){
        //         var element = $(this);
        //         var element_val = $.trim(element.val());

        //         console.log(element_val)
        //     })
        // })
    var table;
    var categoryId = 0, supplierId = 0, status = 0; 
    $(function() {
        // Menampilkan data Supply
        table = $('#dataTable').DataTable({
            data: dataSet,
            columns: [{
                title: "Tanggal"
            }, {
                title: "Nama Supplier"
            }, {
                title: "Nama Product"
            }, {
                title: "Qty"
            }, {
                title: "Total"
            }, {
                title: "#"
            }],
            ajax: {
                url: "{{ url('/') }}/supply/data",
                type: "GET"
            }
        });


        // Category ketika ada event change
        $("#inputCategorySearch").change(function()
        {
            categoryId = $(this).val();
            $.ajax({
                url: "data-barang/data/" + categoryId + "/" + supplierId + "/" + status,
                success: function(response){
                    table.ajax.url("data-barang/data/" + categoryId + "/" + supplierId + "/" + status).load(); 
                    } ,
                error: function() {
                    alert('Tidak dapat menampilkan Data');
                }
            });
        })

        // Supplier ketika ada event change
        $("#inputSupplierSearch").change(function()
        {				
            supplierId = $(this).val();
            $.ajax({
                url: "data-barang/data/" + categoryId + "/" + supplierId + "/" + status,
                success: function(response){
                    table.ajax.url("data-barang/data/" + categoryId + "/" + supplierId + "/" + status).load(); 
                } ,
                error: function() {
                    alert('Tidak dapat menampilkan Data');
                }
            });
        })

        // Status ketika ada event change
        $("#inputStatusSearch").change(function()
        {				
            status = $(this).val();
            $.ajax({
                url: "data-barang/data/" + categoryId + "/" + supplierId + "/" + status,
                success: function(response){
                    table.ajax.url("data-barang/data/" + categoryId + "/" + supplierId + "/" + status).load(); 
                } ,
                error: function() {
                    alert('Tidak dapat menampilkan Data');
                }
            });
        })
        
    });

    // Form Tambah Supply
    function addForm() {
        $('input[name = method]').val('PATCH');
        $('#modal-add-barang').modal('show');
        $('#modal-add-barang form')[0].reset();
        $('.modal-title').text('Tambah Supply Baru');
    }


    function convertToRupiah(angka)
        {
            var rupiah = '';		
            var angkarev = angka.toString().split('').reverse().join('');
            for(var i = 0; i < angkarev.length; i++) if(i%3 == 0) rupiah += angkarev.substr(i,3)+'.';
            return 'Rp. '+rupiah.split('',rupiah.length-1).reverse().join('');
        }

    // Form Edit Supply
    function editForm($id) {
        url = "{{ url('/') }}/data-barang/" + $id;
        $.ajax({
            url: "{{ url('/') }}/data-barang/" + $id + "/edit",
            type: "GET",
            dataType: "JSON",
            success: function(data) {
                $('#modal-edit-barang').modal('show');
                $('.modal-title').text('Edit Supply');
                $('#formEdit').attr('action', url);
                $('#id').val(data.id);
                $('#inputSupplierEdit').val(data.supplier_id);
                $('#productNameEdit').val(data.name);
                $('#inputCategoryEdit').val(data.category_id);
                $('#merkEdit').val(data.merk);
                $('#sellingPriceEdit').val(data.selling_price);
                $('#buyingPriceEdit').val(data.buying_price);
                $('#discountEdit').val(data.discount);
                $('#inputDiscTypeEdit').val(data.discount_type);
                $('#stockEdit').val(data.stock);
                $('#inputStatusEdit').val(data.status);
            },
            error: function() {
                alert('Tidak dapat menampilkan Data');
            }
        });
    }

    // View Details Supply
    function detailsView($id) {
        $.ajax({
            url: "{{ url('/') }}/supply/" + $id,
            type: "GET",
            dataType: "JSON",
            success: function(data) {
                $('#modal-details').modal('show');
                $('.modal-title').text('Detil Supply');
                $('#vTanggalSupply').text(data.supply_date);
                $('#vSupplier').text(data.supplier_name);
                $('#vBarang').text(data.barang_name);
                $('#vHargaBarang').text(convertToRupiah(data.buying_price));
                $('#vQty').text(data.qty);
                $('#vTotal').text(convertToRupiah(data.total));
            },
            error: function() {
                alert('Tidak dapat menampilkan Data');
            }
        });
    }

    // Date Range Picker
    $('input[name="daterange"]').daterangepicker();

    // Date and Time Picker
    $('input[name="daterange"]').daterangepicker({
        timePicker: true,
        timePickerIncrement: 30,
        locale: {
            format: 'MM/DD/YYYY h:mm A'
        }
    });

    // Single Date Picker
    $('input[name="singledatepicker"]').daterangepicker({
        singleDatePicker: true,
        showDropdowns: true
    });

    // Predefined Ranges
    var start = moment().subtract(29, 'days');
    var end = moment();

    function cb(start, end) {
        $('#reportrange span').html(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'));
    }
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

    
    </script>
@endsection