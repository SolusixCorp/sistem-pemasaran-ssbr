@extends('layouts.dashboard')

@section('title', 'Category')
@section('breadcrumb', 'Category')

@section('content')
    <div class="row">

        <div class="col-12" id="list-category">
            <div class="card mb-3">
                <div class="card-header">
                    <span class="pull-right"><button class="btn btn-primary" onclick="addForm()"><i class="fas fa-plus-circle" aria-hidden="true"></i> Kategori Baru</button></span>                   
                    @include('pages/product/category/add-category')
                    @include('pages/product/category/edit-category')
                    <h3><i class="fas fa-boxes"></i> Data Kategori</h3>
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

                    <div class="form-row col-3 pull-right">
                        <div class="form-group col-md-12">
                        <label for="inputStatusSearch">Status</label>
                            <select id="inputStatusSearch" name="inputStatus" class="form-control">
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
                                    <th style="width:2%">No</th>
                                    <th>Nama Kategori</th>
                                    <th style="width:15%">Status</th>
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
    var table, status=0;
    $(function() {
        // Menampilkan data kategori
        table = $('#dataTable').DataTable({
            data: dataSet,
            columns: [{
                title: "No"
            }, {
                title: "Nama Kategori"
            }, {
                title: "Status"
            }, {
                title: "Aksi"
            }],
            ajax: {
                "url": "category-product/data/" + status,
                "type": "GET"
            }
        });

        // Status ketika ada event change
        $("#inputStatusSearch").change(function()
            {				
                status = $(this).val();
                $.ajax({
                    url: "category-product/data/" + status,
                    success: function(response){
                        table.ajax.url( "category-product/data/" + status).load(); 
                    } ,
                    error: function() {
                        alert('Tidak dapat menampilkan Data');
                    }
                });
            })
    
    });

    // Form Tambah Kategori
    function addForm() {
        $('input[name = method]').val('POST');
        $('#modal-add-category').modal('show');
        $('#modal-add-category form')[0].reset();
        $('.modal-title').text('Tambah Kategori');
    }

    // Form Edit Kategori
    function editForm($id) {
        url = "category-product/" + $id;
        $('.modal-title').text('Edit Kategori');
        $.ajax({
            url: "category-product/" + $id + "/edit",
            type: "GET",
            dataType: "JSON",
            success: function(data) {
                $('#modal-edit-category').modal('show');
                $('.modal-title').text('Edit Kategori');
                $('#formEdit').attr('action', url);
                $('#id').val(data.id);
                $('#editCategoryName').val(data.category_name);
                $('#editInputStatus').val(data.status);
            },
            error: function() {
                alert('Tidak dapat menampilkan Data');
            }
        });
    }
    
    </script>
@endsection
