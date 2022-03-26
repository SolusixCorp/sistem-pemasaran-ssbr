@extends('layouts.dashboard')

@section('title', 'Settings')
@section('breadcrumb', 'Settings')

@section('content')
    <div class="row">

        <div class="col-12" id="list-category">
            <div class="card mb-3">
                <div class="card-header">
                    <h3><i class="fas fa-cogs"></i> Pengaturan</h3>
                    @include('pages/settings/edit-company')
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
                                    <th>Nama Perusaahaan</th>
                                    <th>Alamat Perusahaan</th>
                                    <th>Email</th>
                                    <th>Nomor Telepon</th>
                                    <th>Prefix Nota</th>
                                    <th width="10%">Aksi</th>
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
<script src="/nura-admin/assets/plugins/jquery.filer/js/jquery.filer.min.js"></script>

    <script>
        var table;
        $(function() {
            // Menampilkan data kategori
            table = $('#dataTable').DataTable({
                data: dataSet,
                columns: [{
                    title: "Nama Perusahaan"
                }, {
                    title: "Alamat Perusahaan"
                }, {
                    title: "Email"
                }, {
                    title: "Nomor Telepon"
                }, {
                    title: "Prefix Nota"
                },{
                    title: "Aksi"
                }],
                ajax: {
                    "url": "{{ route('settings.data') }}",
                    "type": "GET"
                }
            });

        
            $("#companyLogoEdit").filer({
                limit: 1,
                maxSize: 1,
                extensions: ['jpg', 'jpeg', 'png', 'gif', 'psd'],
                changeInput: true,
                showThumbs: true,
                addMore: true
            });


            $('h6').hide();
            $("#companyLogoEdit").click(function(){
                $('h6').show();
                $("#companyLogoImg").hide();
            });

            $("h6").click(function(){
                $('h6').hide();
                $("#companyLogoImg").show();
            });
           
        
        });

        // Form Edit Company
        function editForm($id) {
            url = "settings/" + $id;
            var imageFolder = 'images/';
            $('.modal-title').text('Edit Toko');
            $.ajax({
                url: "settings/" + $id + "/edit",
                type: "GET",
                dataType: "JSON",
                success: function(data) {
                    $('#modal-edit-company').modal('show');
                    $('.modal-title').text('Edit Data Perusahaan');
                    $('#formEdit').attr('action', url);
                    $('#nameEdit').val(data.company_name);
                    $('#addressEdit').val(data.company_address);
                    $('#emailEdit').val(data.company_email);
                    $('#phoneEdit').val(data.company_phone);
                    $('#prefixNotaEdit').val(data.invoice_prefix);
                    if (data.company_logo == '') {
                        $('#companyLogoImg').attr('src', imageFolder + "no-logo-available.png");
                    } else {
                        $('#companyLogoImg').attr('src', imageFolder + data.company_logo);
                    }
                },
                error: function() {
                    alert('Tidak dapat menampilkan Data');
                }
            });
        }
    
    </script>

   

@endsection
