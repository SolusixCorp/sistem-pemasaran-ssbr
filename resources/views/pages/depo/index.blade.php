@extends('layouts.dashboard')

@section('title', 'Depo')
@section('breadcrumb', 'Depo')

@section('content')
    <div class="row">

        <div class="col-12" id="list-category">
            <div class="card mb-3">
                <div class="card-header">
                    <span class="pull-right"><button class="btn btn-primary" onclick="addForm()"><i class="fas fa-plus-circle" aria-hidden="true"></i>  Depo Baru</button></span>                   
                    @include('pages/depo/add-depo')
                    @include('pages/depo/edit-depo')
                    <h3><i class="fas fa-store"></i> Data Depo</h3>
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
                                    <th>Nama Depo</th>
                                    <th>Tipe</th>
                                    <th style="width:20%">Alamat</th>
                                    <th>Kota</th>
                                    <th>Kontak</th>
                                    <th>Piutang</th>
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
        var status = 0;
        $(function() {
            // Menampilkan data depo
            table = $('#dataTable').DataTable({
                data: dataSet,
                ajax: {
                    "url": "depo/data/",
                    "type": "GET"
                }
            });
        
        });

        // Form Tambah depo
        function addForm() {
            $('#modal-add-depo').modal('show');
            $('.modal-title').text('Tambah Depo');
        }

        // Form Edit Depo
        function editForm($id) {
            url = "depo/" + $id;
            $('.modal-title').text('Edit Depo');
            $.ajax({
                url: "depo/" + $id + "/edit",
                type: "GET",
                dataType: "JSON",
                success: function(data) {
                    $('#modal-edit-depo').modal('show');
                    $('.modal-title').text('Edit Depo');
                    $('#formEdit').attr('action', url);
                    $('#upUser').val(data.depo_id);
                    $('#upDepoType').val(data.depo_type);
                    $('#upDepoAddress').val(data.depo_address);
                    $('#upDepoCity').val(data.depo_city);
                    $('#upDepoEmail').val(data.depo_email);
                    $('#upDepoPhone').val(data.depo_phone);
                },
                error: function() {
                    alert('Tidak dapat menampilkan Data');
                }
            });
        }
        
    </script>
@endsection
