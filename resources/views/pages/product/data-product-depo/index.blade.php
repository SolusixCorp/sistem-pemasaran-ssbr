@extends('layouts.dashboard')

@section('title', 'Produk Depo')
@section('breadcrumb', 'Produk Depo')

@section('content')
    <div class="row">
        <div class="col-12" id="list-product">
            <div class="card mb-3">
                <div class="card-header">
                    <span class="pull-right">
                        <button class="btn btn-primary" data-toggle="modal" data-target="#modal-add-product">
                            <i class="fas fa-plus-circle" aria-hidden="true"></i> Produk Baru
                        </button>
                    </span>
                    <h3><i class="fas fa-cubes"></i> Produk Depo</h3>
                    @include('pages/product/data-product-depo/add-product')
                    @include('pages/product/data-product-depo/edit-product')
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
                            <i class="fa fa-times-circle"></i>
                            {{ Session::get('failed_message') }}
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    @endif

                    <div class="form-row col-7 pull-right">
                        <div class="form-group col-md-4">
                        <label for="inCategorySearch">Kategori</label>
                            <select id="inCategorySearch" name="inCategorySearch" class="form-control">
                            <option value="0" selected>Semua</option>    
                                @foreach ($categories as $category)
                                    <option value="{{ $category->category_id }}" >{{ $category->category_name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group col-md-4">
                        <label for="inDepoSearch">Depo</label>
                            <select id="inDepoSearch" name="inDepoSearch" class="form-control">
                                <option value="0" selected>Semua</option>
                                @foreach ($depos as $depo)
                                    <option value="{{ $depo->depo_id }}" >{{ $depo->depo_name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group col-md-4">
                        <label for="inStatusSearch">Status</label>
                            <select id="inStatusSearch" name="inStatusSearch" class="form-control">
                                <option value="0" selected>Semua</option>
                                <option value="Aktif" >Aktif</option>
                                <option value="Tidak Aktif" >Tidak Aktif</option>
                            </select>
                        </div>
                    </div>
                    
                    <div class="table-responsive">
                        <table id="dataTable" class="table table-bordered table-hover" style="width:100%">
                            <thead>
                                <tr>
                                    <th>Kategori Produk</th>
                                    <th>Nama Produk</th>
                                    <th>Depo</th>
                                    <th>Harga Depo</th>
                                    <th>Stok</th>
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
    @include('pages/product/data-product-depo/details-product')
@endsection

@section('custom_js')
    
    <script>
    var table;
    var categoryId = 0, DepoId = 0, status = 0; 
    $(function() {
        // Menampilkan data product
        table = $('#dataTable').DataTable({
            data: dataSet,
            ajax: {
                url: "data-product-depo/data/" + categoryId + "/" + DepoId + "/" + status,
                type: "GET"
            }
        });


        // Category ketika ada event change
        $("#inCategorySearch").change(function()
        {
            categoryId = $(this).val();
            $.ajax({
                url: "data-product-depo/data/" + categoryId + "/" + DepoId + "/" + status,
                success: function(response){
                    table.ajax.url("data-product-depo/data/" + categoryId + "/" + DepoId + "/" + status).load(); 
                    } ,
                error: function() {
                    alert('Tidak dapat menampilkan Data');
                }
            });
        })

        // Depo ketika ada event change
        $("#inDepoSearch").change(function()
        {				
            depoId = $(this).val();
            $.ajax({
                url: "data-product-depo/data/" + categoryId + "/" + depoId + "/" + status,
                success: function(response){
                    table.ajax.url("data-product-depo/data/" + categoryId + "/" + depoId + "/" + status).load(); 
                } ,
                error: function() {
                    alert('Tidak dapat menampilkan Data');
                }
            });
        })

        // Status ketika ada event change
        $("#inStatusSearch").change(function()
        {				
            status = $(this).val();
            $.ajax({
                url: "data-product-depo/data/" + categoryId + "/" + depoId + "/" + status,
                success: function(response){
                    table.ajax.url("data-product-depo/data/" + categoryId + "/" + depoId + "/" + status).load(); 
                } ,
                error: function() {
                    alert('Tidak dapat menampilkan Data');
                }
            });
        })
        
    });

    // Form Tambah product
    function addForm() {
        $('input[name = method]').val('PATCH');
        $('#modal-add-product').modal('show');
        $('#modal-add-product form')[0].reset();
        $('.modal-title').text('Tambah product Baru');
    }

    // Form Edit Product
    function editForm($id) {
        url = "data-product-depo/" + $id;
        $.ajax({
            url: "data-product-depo/" + $id + "/edit",
            type: "GET",
            dataType: "JSON",
            success: function(data) {
                $('#modal-edit-product').modal('show');
                $('.modal-title').text('Edit Product');
                $('#formEdit').attr('action', url);
                $('#upProductName').val(data.product_name);
                $('#upStatus').val(data.product_status);
            },
            error: function() {
                alert('Tidak dapat menampilkan Data');
            }
        });
    }

    // View Details product
    function detailsView($id) {
        $.ajax({
            url: "data-product-depo/" + $id,
            type: "GET",
            dataType: "JSON",
            success: function(data) {
                $('#modal-details-product').modal('show');
                $('.modal-title').text('Detil Product');
                $('#vName').text(data.product_name);
                $('#vCategory').text(data.product_category);
                $('#vConsumentPrice').text(data.product_price_consument);
                $('#vRetailPrice').text(data.product_price_retail);
                $('#vSubWholePrice').text(data.product_price_sub_whole);
                $('#vWholePrice').text(data.product_price_whole);
                $('#vDepoPrice').text(data.product_price_depo);
                $('#vStock').text(data.product_stock);
                $('#vStatus').text(data.product_status);
                $('#vPhoto').attr("src", data.product_image);
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

    $('#validatedCustomFile').change(function() {
        var file = $('#validatedCustomFile')[0].files[0].name;
        $('#validatedCustomFileLabel').text(file);
    });

    $('#upValidatedCustomFile').change(function() {
        var file = $('#upValidatedCustomFile')[0].files[0].name;
        $('#upValidatedCustomFileLabel').text(file);
    });

    </script>
@endsection