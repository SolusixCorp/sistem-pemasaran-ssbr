@extends('layouts.dashboard')

@section('title', 'Pengeluaran')
@section('breadcrumb', 'Pengeluaran')

@section('content')
    <div class="row">

        <div class="col-12" id="list-expense">
            <div class="card mb-3">
                <div class="card-header">
                    <span class="pull-right"><button class="btn btn-primary" onclick="addForm()"><i class="fas fa-plus" aria-hidden="true"></i> Pengeluaran</button></span>                   
                    @include('pages/report/expense/add-expense')
                    @include('pages/report/expense/edit-expense')
                    <h3><i class="fas fa-upload"></i> Data Pengeluaran</h3>
                    <br>
                    <div id="reportrange" class="form-control col-4 pull-right" style="background: #fff; cursor: pointer; padding: 5px 10px; border: 1px solid #ccc; width: 100%">
                        <i class="fas fa-calendar"></i>&nbsp;
                        <span></span> <b class="caret"></b>
                    </div>
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
                                    <th>Tanggal</th>
                                    <th>Jenis Pengeluaran</th>
                                    <th>Nominal</th>
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
    var start = moment();
    var end = moment();
    var startDate, endDate;
    $(function() {
        // Menampilkan data Pengeluaran
        table = $('#dataTable').DataTable({
            data: dataSet,
            columns: [{
                title: "No"
            }, {
                title: "Tanggal"
            }, {
                title: "Jenis Pengeluaran"
            }, {
                title: "Nominal"
            }, {
                title: "Aksi"
            }],
            ajax: {
                "url": "/expense/" + start.format('YYYY-MM-D') + "/" + end.format('YYYY-MM-D'),
                "type": "GET"
            }
        });

        // Single Date Picker
        $('input[name="singledatepicker"]').daterangepicker({
            singleDatePicker: true,
            showDropdowns: true
        });
        
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

        function cb(start, end) {
            startDate = start;
            endDate = end;
            $('#reportrange span')
            .html(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'));
            table.ajax.url("/expense/" + start.format('YYYY-MM-D') + "/" + end.format('YYYY-MM-D')).load();          
        }

    });

    // Form Tambah Pengeluaran
    function addForm() {
        save_method = "add";
        $('input[name = method]').val('POST');
        $('#modal-add-expense').modal('show');
        $('#modal-add-expense form')[0].reset();
        $('.modal-title').text('Tambah Pengeluaran');
    }

    // Form Edit Pengeluaran
    function editForm($id) {
        url = "expense/" + $id;
        $('#modal-edit-expense form')[0].reset();
        $('.modal-title').text('Edit Pengeluaran');
        $.ajax({
            url: "expense/" + $id + "/edit",
            type: "GET",
            dataType: "JSON",
            success: function(data) {
                $('#modal-edit-expense').modal('show');
                $('.modal-title').text('Edit Pengeluaran');
                $('#formEdit').attr('action', url);
                $('#id').val(data.id);
                $('#expenseDateEdit').val(data.expense_date);
                $('#expenseTypeEdit').val(data.expense_type);
                $('#expenseNominalEdit').val(data.expense_nominal);
            },
            error: function() {
                alert('Tidak dapat menampilkan Data');
            }
        });
    }
    
    </script>
@endsection
