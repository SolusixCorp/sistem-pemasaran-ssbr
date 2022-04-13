@extends('layouts.dashboard')

@section('title', 'Stock Flow')
@section('breadcrumb', 'Stock Flow')

@section('content')
    <div class="row">
        <div class="col-12" id="add-order">
            <div class="card mb-3">
                <div class="card-header">
                    <h3><i class="fas fa-dolly-flatbed"></i> Edit Stock Flow</h3>
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

                    <form method="POST" autocomplete="off" action="{{ route('stock.update', ['id' => $order->id]) }}" >
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
                                            @foreach ($customers as $c)
                                                <option value="{{ $c->id }}" @if($c->id == $order->customer->id) {{ "selected" }} @endif>{{ $c->customer_name }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="form-group">
                                        <label for="stock_type">Tipe Stok</label>
                                        <select id="stock_type" name="stock_type" class="form-control js-example-basic-single">
                                            <option value="in" >STOCK IN</option>
                                            <option value="out" >STOCK OUT</option>
                                        </select>
                                    </div>

                                    <div class="form-group">
                                        <label for="stock_category">Kategori Stok (IN / OUT)</label>
                                        <select id="stock_category" name="stock_category" class="form-control js-example-basic-single">
                                            <option value="dropping" >Dropping</option>
                                            <option value="return" >Return</option>
                                            <option value="stock" >stock</option>
                                            <option value="return" >Return</option>
                                        </select>
                                    </div>

                                    <div class="form-group" id="order_items">
                                        <div class="table-responsive alert-primary">  
                                            <table class="table table-bordered" id="" cellspacing="0" cellpadding="0" style="border:none; border-collapse: collapse;">  
                                                <tbody id="dynamic_field">
                                                    @foreach($order->order_items as $item)
                                                        <tr>  
                                                            <td width="40%">
                                                                <div class="form-group">
                                                                <label for="item">Item</label>
                                                                    <select id="barang_item_id" name="barang_item_id[]" class="form-control barang_item_id">
                                                                        @foreach ($barangs_item as $b)
                                                                            <option value="{{ $b->barang_id }}" >{{ $b->name . ' (' . rupiah($b->selling_price, TRUE) . ')' }}</option>
                                                                        @endforeach
                                                                    </select>
                                                                </div>
                                                            </td>  
                                                            <td width="15%">
                                                                <label for="item">Remaining Stok</label>
                                                                <input type="text" name="notes_item[]" id="notes_item" placeholder="0" value="100" class="form-control" readonly>
                                                            </td>  
                                                            <td width="10%">
                                                                <label for="item">Qty</label>
                                                                <input type="number" name="qty[]" id="qty" value="{{ $item->qty }}" class="form-control" >
                                                            </td> 
                                                            <td></td>
                                                        </tr>
                                                    @endforeach  
                                                </tbody>
                                                <tfoot>
                                                    <td><button type="button" name="add" id="add" onclick="" class="btn btn-primary">Tambah Item</button></td>   
                                                </tfoot>
                                            </table>  
                                        </div>  
                                    </div>

                                    <div class="form-row">
                                        <div class="form-group col-md-3">
                                            <label for="discount">Diskon</label>
                                            <input type="number" min="0" value="<?php if($order->discount_type == 'percentage') { echo (int)$order->discount_percentage; } else { echo (int) $order->discount_rp; } ?>" name="discount" class="form-control" id="discount" required placeholder="50">
                                        </div>
                                        <div class="form-group col-md-2">
                                            <label for="inputDiscType">Tipe</label>
                                            <select id="inputDiscType" name="discountType" class="form-control">
                                                <option value="percentage" @if($order->discount_type == "percentage") {{ "selected" }} @endif>%</option>
                                                <option value="rp" @if($order->discount_type == "rp") {{ "selected" }} @endif>Rupiah</option>
                                            </select>
                                        </div>
                                        <div class="form-group col-md-4">
                                            <label for="discount_notes">Keterangan Diskon</label>
                                            <input type="text" value="{{ $order->discount_notes }}" name="discount_notes" class="form-control" id="discount" required placeholder="50">
                                        </div>
                                        <div class="form-group col-md-3">
                                        <label for="customer_name">Total Transaksi</label>
                                            <input type="text" name="notes_item[]" id="notes_item" placeholder="Total" value="{{ rupiah($order->total_with_discount, TRUE) }}" readonly="true" class="form-control" >
                                        
                                            
                                        </div>
                                    </div>


                                    <div class="form-row">
                                        <div class="form-group col-md-4">
                                            <label for="bayar">Uang Bayar </label>
                                            <input type="number" min="0" name="bayar" value="{{ $order->bayar }}" class="form-control" id="discount" required placeholder="100000">
                                        </div>
                                    </div>

                                </div>
                            </div>
                            <div class="modal-footer">
                                <a href="/stock" class="btn btn-secondary">Batal</a>
                                <button type="submit" class="btn btn-primary">Edit Stock Flow</button>
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