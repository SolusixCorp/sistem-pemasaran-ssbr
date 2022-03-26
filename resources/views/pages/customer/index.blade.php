@extends('layouts.dashboard')

@section('title', 'Customer')
@section('breadcrumb', 'Customer')

@section('content')
    <div class="row">

        <div class="col-12" id="list-category">
            <div class="card mb-3">
                <div class="card-header">
                    <span class="pull-right">
                        <button class="btn btn-primary" onclick="addForm()">
                            <i class="fas fa-plus" aria-hidden="true"></i> Tambah Customer
                        </button>
                    </span>    
                    <h3><i class="fas fa-user-friends"></i> Data Customer</h3>
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
                                    <th>Nama Customer</th>
                                    <th>Nomor Telepon</th>
                                    <th>#</th>
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

    @include('pages/customer/add')
    @include('pages/customer/edit')
    </div>
    <!-- end row-->
@endsection

@section('custom_js')
    <script>
        var table;
        $(function() {
            // Menampilkan data kategori
            table = $('#dataTable').DataTable({
                data: dataSet,
                columns: [{
                    title: "No"
                }, {
                    title: "Nama Customer"
                }, {
                    title: "Nomor Telepon"
                }, {
                    title: "#"
                }],
                ajax: {
                    "url": "{{ route('customer.data') }}",
                    "type": "GET"
                }
            });
        
        });

        // Status ketika ada event change
        $("#inputStatusSearch").change(function(){				
            status = $(this).val();
            $.ajax({
                url: "category-barang/data/" + status,
                success: function(response){
                    table.ajax.url( "category-barang/data/" + status).load(); 
                } ,
                error: function() {
                    alert('Tidak dapat menampilkan Data');
                }
            });
        });
    
        function addForm() {
            $('#modal-add').modal('show');
            $('.modal-title').text('Tambah Customer');
        }

        // Form Edit 
        function editForm($id) {
            url = "customer/" + $id;
            $('.modal-title').text('Edit Customer');
            $.ajax({
                url: "customer/" + $id + "/edit",
                type: "GET",
                dataType: "JSON",
                success: function(data) {
                    $('#modal-edit-customer').modal('show');
                    $('.modal-title').text('Edit Customer');
                    $('#formEdit').attr('action', url);
                    $('#id').val(data.id);
                    $('#editCustomerName').val(data.customer_name);
                    $('#editCustomerPhone').val(data.customer_phone);
                },
                error: function() {
                    alert('Tidak dapat menampilkan Data');
                }
            });
        }
    
    
    </script>
@endsection
