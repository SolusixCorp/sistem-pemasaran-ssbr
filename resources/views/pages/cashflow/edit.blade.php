@extends('layouts.dashboard')

@section('title', 'Cash Flow')
@section('breadcrumb', 'Cash Flow')

@section('content')
    <div class="row">
        <div class="col-12" id="add-order">
            <div class="card mb-3">
                <div class="card-header">
                    <h3><i class="fas fa-balance-scale"></i> Edit Cash Flow</h3>
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

                    <form method="POST" autocomplete="off" action="{{ route('cashflow.update', ['id' => $cash['cash_id']]) }}" enctype="multipart/form-data" >
                        @csrf
                            <div class="modal-body">
                                <div class="card-body">
                                    <div class="form-group" style="margin: 0;">
                                        <label for="trx_date">Tanggal Transaksi</label>       
                                    </div>
                                    <div class="row">
                                        <div class="col-xl-3 col-lg-4">
                                            <div class="form-group">
                                                <input type="date" class="form-control"  name="date" value="{{ $cash['input_date'] }}" />
                                            </div>
                                        </div>
                                        <div class="col-xl-2 col-lg-4">
                                            <div class="form-group">
                                                <input type="time" class="form-control" name="time" value="{{ $cash['input_time'] }}" />
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="form-group">
                                        <label for="depo_name">Nama Depo</label>
                                        <select id="depo_name" name="depo_name" class="form-control js-example-basic-single">
                                            <option value="{{ $cash['depo_id'] }}" >{{ $cash['depo'] }}</option>
                                            @foreach ($depos as $depo)
                                                @if ($depo['id'] != $cash['depo_id']) 
                                                    <option value="{{ $depo['id'] }}" >{{ $depo['name'] }}</option>
                                                @endif
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="form-group">
                                        <label for="cash_type">Tipe Cash Flow</label>
                                        <select id="cash_type" name="cash_type" class="form-control js-example-basic-single">
                                            @if ($cash['type'] == 'revenue') 
                                                <option value="revenue" >Pendapatan</option>
                                                <option value="expense" >Pengeluaran</option>
                                            @else 
                                                <option value="expense" >Pengeluaran</option>
                                                <option value="revenue" >Pendapatan</option>
                                            @endif
                                        </select>
                                    </div>

                                    <div class="form-group">
                                        <label for="cash_category" id="cash_category_label">Kategori Cash Flow @if ($cash['type'] == 'revenue') (Pendapatan) @else (Pengeluaran) @endif</label>
                                        <select id="cash_category" name="cash_category" class="form-control js-example-basic-single">
                                            @if ($cash['type'] == 'revenue') 
                                                @if ($cash['category'] == 'product_sales') 
                                                    <option value="product_sales" >Penjualan</option>
                                                    <option value="petty_cash" >Kas Kecil</option>
                                                    <option value="another_revenue" >Lainnya</option>
                                                @elseif ($cash['category'] == 'petty_cash') 
                                                    <option value="petty_cash" >Kas Kecil</option>
                                                    <option value="product_sales" >Penjualan</option>
                                                    <option value="another_revenue" >Lainnya</option>
                                                @else
                                                    <option value="another_revenue" >Lainnya</option>
                                                    <option value="product_sales" >Penjualan</option>
                                                    <option value="petty_cash" >Kas Kecil</option>
                                                @endif
                                            @else 
                                                @if ($cash['category'] == 'expense') 
                                                    <option value="expense" >Pengeluaran</option>
                                                    <option value="transfer" >Setoran / Transfer</option>
                                                @else
                                                    <option value="transfer" >Setoran / Transfer</option>
                                                    <option value="expense" >Pengeluaran</option>
                                                @endif
                                            @endif
                                        </select>
                                    </div>

                                    <div class="form-row">
                                        <div class="form-group col-md-12">
                                            <label for="amount">Nominal </label>
                                            <input type="number" min="0" name="amount" value="{{ $cash['total'] }}" class="form-control" id="discount" required placeholder="100000">
                                        </div>
                                    </div>
                                    
                                    <div class="form-row">
                                        <div class="form-group col-md-12">
                                            <label for="validatedCustomFile">Bukti Transaksi</label>
                                            <div class="custom-file">
                                                <input type="file" name="receipt" class="custom-file-input" id="validatedCustomFile">
                                                <label class="custom-file-label" for="validatedCustomFile">Pilih
                                                    file...</label>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label for="notes">Catatan</label>
                                        <textarea type="text" name="notes" class="form-control" id="notes">{{ $cash['notes'] }}</textarea>
                                    </div>
                                  
                                </div>
                            </div>
                            <div class="modal-footer">
                                <a href="/cashflow" class="btn btn-secondary">Batal</a>
                                <button type="submit" class="btn btn-primary">Edit Transaksi</button>
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

@section('custom_css')
    <link href="{{ asset('css/select2.css') }}" rel="stylesheet"/>

    <style>
        table {
            border: 1px solid #CCC;
            border-collapse: collapse;
        }

        td {
            border: none;
        }
        .table-bordered td, .table-bordered th {
            border: 0px solid #dee2e6;
        }
    </style>
@endsection

@section('custom_js')
    <script src="{{ asset('js/select2.js') }}"></script>
    <script>
        
        // Single Date Picker
        $('input[name="singledatepicker"]').daterangepicker({
            singleDatePicker: true,
            showDropdowns: true
        });

        $(document).ready(function(){

            $('#customer_name').select2({
                placeholder:'Pilih Depo',
                theme:'bootstrap4',
                tags:true,
            }).on('select2:close', function(){
                var element = $(this);
                var element_val = $.trim(element.val());

                console.log(element_val)

                var isExist = false
                for (i = 0; i < document.getElementById("customer_name").length - 1; ++i){
                    console.log(i + ") " + document.getElementById("customer_name").options[i].text)
                    if (document.getElementById("customer_name").options[i].value == element_val){
                        console.log("exist")
                        isExist = true
                        break
                    }
                }
                
                if(element_val != '' && !isExist) {
                    $.ajax({
                        url: "{{ url('/') }}" + "/customer/add",
                        method: "POST",
                        data: {
                            "_token": "{{ csrf_token() }}",
                            "customerName" : element_val
                        },
                        success: function(data) {
                            console.log(data)
                            if (data.error != null || data.error != undefined || data.error == true) {
                                if(data == 'yes') {
                                    element.append('<option value="'+ data.id +'">'+element_val+'</option>').val(data.id);
                                }
                            }
                        }
                    })
                }
            });

            $('#cash_type').select2({
                theme:'bootstrap4',
                tags:true,
            }).on('select2:close', function(){
                var element = $(this);
                var element_val = $.trim(element.val());
                console.log(element_val);
                if(element_val != '') {
                    var cashCategoryOps = $('#cash_category');
                    var cashCategoryLabel = $('#cash_category_label');
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