@extends('layouts.dashboard')

@section('title', 'Report')
@section('breadcrumb', 'Report')

@section('content')
    <div class="row">
        <div class="col-12" id="add-order">
            <div class="card mb-3">
                <div class="card-header">
                    <h3><i class="fas fa-chart-line"></i> Edit Report</h3>
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

                    <form method="POST" autocomplete="off" action="{{ route('report.update', ['id' => $report['report_id']]) }}" enctype="multipart/form-data">
                        @csrf
                            <div class="modal-body">
                                <div class="card-body">
                                    <div class="form-group" style="margin: 0;">
                                        <label for="trx_date">Tanggal</label>       
                                    </div>
                                    <div class="row">
                                        <div class="col-xl-3 col-lg-4">
                                            <div class="form-group">
                                                <input type="date" class="form-control"  name="date" value="{{ $report['date'] }}" />
                                            </div>
                                        </div>
                                        <div class="col-xl-2 col-lg-4">
                                            <div class="form-group">
                                                <input type="time" class="form-control" name="time" value="{{ $report['time'] }}"/>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="form-group">
                                        <label for="depo_name">Nama Depo</label>
                                        <select id="depo_name" name="depo_name" class="form-control js-example-basic-single">
                                            <option value="{{ $report['depo_id'] }}" >{{ $report['depo'] }}</option>    
                                            @foreach ($depos as $depo)
                                                @if ($report['depo_id'] != $depo['id'])
                                                <option value="{{ $depo['id'] }}" >{{ $depo['name'] }}</option>
                                                @endif
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="form-group">
                                        <label for="payment_type" id="payment_type_label">Payment Type</label>
                                        <select id="payment_type" name="payment_type" class="form-control js-example-basic-single">
                                            @if ($report['type'] == 'transfer')
                                                <option value="transfer" >Transfer</option>
                                                <option value="cash" >Cash</option>
                                                <option value="return" >Return</option>
                                            @elseif ($report['type'] == 'cash')
                                                <option value="cash" >Cash</option>
                                                <option value="transfer" >Transfer</option>
                                                <option value="return" >Return</option>
                                            @else
                                                <option value="return" >Return</option>
                                                <option value="cash" >Cash</option>
                                                <option value="transfer" >Transfer</option>
                                            @endif
                                        </select>
                                    </div>

                                    <div class="form-row">
                                        <div class="form-group col-md-12">
                                            <label for="amount">Nominal </label>
                                            <input type="number" min="0" name="amount" value="{{ $report['amount'] }}" class="form-control" id="discount" required placeholder="100000">
                                        </div>
                                    </div>
                                    
                                    <div class="form-row">
                                        <div class="form-group col-md-12">
                                            <label for="validatedCustomFile">Bukti Pembayaran</label>
                                            <div class="custom-file">
                                                <input type="file" name="receipt" class="custom-file-input" id="validatedCustomFile" required>
                                                <label id="fileLabel" class="custom-file-label" for="validatedCustomFile">Pilih
                                                    file...</label>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label for="notes">Catatan</label>
                                        <textarea type="text" name="notes" class="form-control" id="notes">{{ $report['desc'] }}</textarea>
                                    </div>
                                  
                                </div>
                            </div>
                            <div class="modal-footer">
                                <a href="{{ route('report.index') }}" class="btn btn-secondary">Batal</a>
                                <button type="submit" class="btn btn-primary">Simpan</button>
                            </div>
                        </form>

                </div>
                <!-- end card-body-->
            </div>
            <!-- end card-->
        </div>
    
    </div>
    <!-- end row-->
@endsection

@section('custom_js')
    <script src="{{ asset('js/select2.js') }}"></script>
    <script>
        
        // Single Date Picker
        $('input[name="singledatepicker"]').daterangepicker({
            singleDatePicker: true,
            showDropdowns: true
        });

        $('#validatedCustomFile').on('change', function(){
            var value = $(this).val();
            $("#fileLabel").text(value);
        });

        $(document).ready(function(){

            $('#depo_name').select2({
                placeholder:'Pilih Depo',
                theme:'bootstrap4',
                tags:true,
            }).on('select2:close', function(){
                var element = $(this);
                var element_val = $.trim(element.val());

                console.log(element_val)

                var isExist = false
                for (i = 0; i < document.getElementById("depo_name").length - 1; ++i){
                    console.log(i + ") " + document.getElementById("depo_name").options[i].text)
                    if (document.getElementById("depo_name").options[i].value == element_val){
                        console.log("exist")
                        isExist = true
                        break
                    }
                }
                
                if(element_val != '' && !isExist) {
                    $.ajax({
                        url: "{{ url('/') }}" + "/depo/add",
                        method: "POST",
                        data: {
                            "_token": "{{ csrf_token() }}",
                            "depoName" : element_val
                        },
                        success: function(data) {
                            console.log(data)
                            element.append('<option value="'+ data.id +'">'+element_val+'</option>').val(data.id);
                        }
                    })
                }
            });

            //Select Cash Type
            $('#cash_type').select2({
                theme:'bootstrap4',
                tags:true,
            }).on('select2:close', function(){
                var element = $(this);
                var element_val = $.trim(element.val());
                console.log(element_val);
                if(element_val != '') {
                    var cashCategoryOps = $('#payment_type');
                    var cashCategoryLabel = $('#payment_type_label');
                    cashCategoryOps.empty();
                    if (element_val == 'revenue') {
                        cashCategoryLabel.text('Kategori Cash (Pendapatan)');
                        cashCategoryOps.append('<option value="product_sales">Penjualan</option>');
                        cashCategoryOps.append('<option value="petty_cash">Kas Kecil</option>');
                        cashCategoryOps.append('<option value="another_revenue">Lainnya</option>');
                    } else {
                        cashCategoryLabel.text('Kategori Cash (Pengeluaran)');
                        cashCategoryOps.append('<option value="expense">Pengeluaran</option>');
                        cashCategoryOps.append('<option value="transfer">Setor / Transfer</option>');
                    }
                }

                console.log(element_val)
            });

        });
    </script>
@endsection