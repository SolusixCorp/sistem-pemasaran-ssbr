@extends('layouts.dashboard')

@section('title', 'Supplier')
@section('breadcrumb', 'Supplier')

@section('content')
    <div class="row">

        <div class="col-12" id="list-category">
            <div class="card mb-3">
                <div class="card-header">
                    <span class="pull-right"><button class="btn btn-primary" onclick="addForm()"><i class="fas fa-plus" aria-hidden="true"></i> Supplier Baru</button></span>                   
                    @include('pages/supplier/add-supplier')
                    @include('pages/supplier/edit-supplier')
                    <h3><i class="fas fa-truck"></i> Data Supplier</h3>
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
                                    <th>Nama Supplier</th>
                                    <th>Alamat</th>
                                    <th>Email</th>
                                    <th>Nomor Telepon</th>
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
        var status = 0;
        $(function() {
            // Menampilkan data Supplier
            table = $('#dataTable').DataTable({
                data: dataSet,
                columns: [{
                    title: "No"
                }, {
                    title: "Nama Supplier"
                }, {
                    title: "Nama Alamat"
                }, {
                    title: "Email"
                }, {
                    title: "Nomor Telepon"
                }, {
                    title: "Aksi"
                }],
                ajax: {
                    "url": "supplier/data/",
                    "type": "GET"
                }
            });
        
        });

        // Form Tambah Supplier
        function addForm() {
            $('#modal-add-supplier').modal('show');
            $('.modal-title').text('Tambah Supplier');
        }

        // Form Edit Supplier
        function editForm($id) {
            url = "supplier/" + $id;
            $('.modal-title').text('Edit Supplier');
            $.ajax({
                url: "supplier/" + $id + "/edit",
                type: "GET",
                dataType: "JSON",
                success: function(data) {
                    $('#modal-edit-supplier').modal('show');
                    $('.modal-title').text('Edit Supplier');
                    $('#formEdit').attr('action', url);
                    $('#nameEdit').val(data.supplier_name);
                    $('#addressEdit').val(data.supplier_address);
                    $('#emailEdit').val(data.supplier_email);
                    $('#phoneEdit').val(data.supplier_phone);
                },
                error: function() {
                    alert('Tidak dapat menampilkan Data');
                }
            });
        }
        
    </script>
@endsection
