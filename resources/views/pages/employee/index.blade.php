@extends('layouts.dashboard')

@section('title', 'Employee')
@section('breadcrumb', 'Employee')

@section('content')
    <div class="row">

        <div class="col-12" id="list-category">
            <div class="card mb-3">
                <div class="card-header">
                    <span class="pull-right">
                        <a class="btn btn-primary" href="{{ url('/employee/create') }}">
                            <i class="fas fa-plus-circle" aria-hidden="true"></i> Employee
                        </a>
                    </span>    
                    <h3><i class="fas fa-users"></i> Data Employee</h3>
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
                    
                    <div class="table-responsive">
                        <table id="dataTable" class="table table-bordered table-hover" style="width:100%">
                            <thead>
                                <tr>
                                    <th style="width:2%">No</th>
                                    <th>Nama Employee</th>
                                    <th>Posisi</th>
                                    <th>NIK</th>
                                    <th>Tanggal Masuk</th>
                                    <th>Tanggal Keluar</th>
                                    <th style="width:5%">Aksi</th>
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
        $(function() {
            // Menampilkan data Employee
            table = $('#dataTable').DataTable({
                data: dataSet,
                ajax: {
                    "url": "{{ route('employee.data') }}",
                    "type": "GET"
                }
            });
        
        });

        // Status ketika ada event change
        $("#inputStatusSearch").change(function(){				
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
        });    
    </script>
@endsection
