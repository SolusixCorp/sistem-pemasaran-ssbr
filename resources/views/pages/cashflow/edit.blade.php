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

                    <form method="POST" autocomplete="off" action="" >
                        @csrf
                            <div class="modal-body">
                                <div class="card-body">
                                    <div class="form-group" style="margin: 0;">
                                        <label for="trx_date">Tanggal Transaksi</label>       
                                    </div>
                                    <div class="row">
                                        <div class="col-xl-3 col-lg-4">
                                            <div class="form-group">
                                                <input type="date" class="form-control"  name="date" />
                                            </div>
                                        </div>
                                        <div class="col-xl-2 col-lg-4">
                                            <div class="form-group">
                                                <input type="time" class="form-control" name="time" />
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="form-group">
                                        <label for="depo_name">Nama Depo</label>
                                        <select id="depo_name" name="depo_name" class="form-control js-example-basic-single">
                                            @foreach ($customers as $customer)
                                                <option value="{{ $customer->id }}" >{{ $customer->customer_name }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="form-group">
                                        <label for="Cashflow_type">Tipe Cash Flow</label>
                                        <select id="Cashflow_type" name="Cashflow_type" class="form-control js-example-basic-single">
                                            <option value="revenue" >Pendapatan</option>
                                            <option value="Expense" >Pengeluaran</option>
                                        </select>
                                    </div>

                                    <div class="form-group">
                                        <label for="cashflow_category">Kategori Cash Flow (Pendapatan / Pengeluaran)</label>
                                        <select id="cashflow_category" name="cashflow_category" class="form-control js-example-basic-single">
                                            <option value="petty_cash" >Kas Kecil</option>
                                            <option value="another_revenue" >Pendapatan Lainnya</option>
                                            <option value="expense" >Pengeluaran</option>
                                            <option value="setor" >Setoran / Transfer</option>
                                        </select>
                                    </div>

                                    <div class="form-row">
                                        <div class="form-group col-md-12">
                                            <label for="bayar">Nominal </label>
                                            <input type="number" min="0" name="bayar" value="" class="form-control" id="discount" required placeholder="100000">
                                        </div>
                                    </div>
                                    
                                    <div class="form-row">
                                        <div class="form-group col-md-12">
                                            <label for="validatedCustomFile">Bukti Transaksi</label>
                                            <div class="custom-file">
                                                <input type="file" class="custom-file-input" id="validatedCustomFile" required>
                                                <label class="custom-file-label" for="validatedCustomFile">Pilih
                                                    file...</label>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label for="notes">Catatan</label>
                                        <textarea type="text" name="notes" class="form-control" id="notes"></textarea>
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
        $('#add').click(function(){  
            i++;  
            $('#dynamic_field').append('<tr id="row'+i+'"><td width="30%"><select id="barang_item_id" name="barang_item_id[]" class="form-control barang_item_id"> @foreach ($barangs_item as $barang) <option value="{{ $barang->barang_id }}" >{{ $barang->name . "(" . rupiah($barang->selling_price, TRUE) . ")" }}</option> @endforeach </select></td>  <td width="40%"><input type="text" name="notes_item[]" id="notes_item" placeholder="Catatan" value="" class="form-control" >  <td width="15%"><input type="number" name="qty[]" id="qty" value="1" class="form-control" ></td>    <td><button type="button" name="remove" id="'+i+'" class="btn btn-danger btn_remove">X</button></td></tr>');  
       
            
            $(document).ready(function(){
                $('.barang_item_id').select2({
                    theme:'bootstrap4',
                    tags:true,
                }).on('select2:close', function(){
                    var element = $(this);
                    var element_val = $.trim(element.val());

                    console.log(element_val)
                })
            })
        });  
        $(document).on('click', '.btn_remove', function(){  
            var button_id = $(this).attr("id");   
            $('#row'+button_id+'').remove();  
        });  

        $(document).ready(function(){
            $('.barang_item_id').select2({
                theme:'bootstrap4',
                tags:true,
            }).on('select2:close', function(){
                var element = $(this);
                var element_val = $.trim(element.val());

                console.log(element_val)
            })
        })

        // Single Date Picker
        $('input[name="singledatepicker"]').daterangepicker({
            singleDatePicker: true,
            showDropdowns: true
        });

        $(document).ready(function(){

            $('#customer_name').select2({
                placeholder:'Pilih Pembeli',
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

        });
    </script>
@endsection