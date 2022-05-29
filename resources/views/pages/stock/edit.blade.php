@extends('layouts.dashboard')

@section('title', 'Stock Flow')
@section('breadcrumb', 'Stock Flow')

@section('content')
    <div class="row">
        <div class="col-12" id="add-stock">
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

                    <form method="POST" autocomplete="off" action="{{ route('stock.update', ['id' => $stock->id]) }}" >
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
                                            <option value="{{ $stock->depo->id }}" >{{ $stock->depo->user->name }}</option>
                                            @foreach ($depos as $depo)
                                                @if ($depo->id != $stock->depo->id)
                                                    <option value="{{ $depo->id }}" >{{ $depo->name }}</option>
                                                @endif
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="form-group">
                                        <label for="stock_type">Tipe Stok</label>
                                        <select id="stock_type" name="stock_type" class="form-control js-example-basic-single">
                                            @if (Auth::user()->role == 'ho')
                                                @if ($stock->stock_type == 'in')
                                                    <option value="in" >STOCK IN</option>
                                                    <option value="out" >STOCK OUT</option>
                                                @else 
                                                    <option value="out" >STOCK OUT</option>
                                                    <option value="in" >STOCK IN</option>
                                                @endif
                                            @else
                                                <option value="out" >STOCK OUT</option>
                                            @endif
                                        </select>
                                    </div>

                                    <div class="form-group">
                                        <label for="stock_category">Kategori Stok @if ($stock->stock_type == 'in') {{ '(IN)' }} @else {{ '(OUT)' }} @endif</label>
                                        <select id="stock_category" name="stock_category" class="form-control js-example-basic-single">
                                            @if ($stock->stock_type == 'in')
                                                @if ($stock->stockin_category == 'dropping')
                                                    <option value="dropping" >Dropping</option>
                                                    <option value="return" >Return</option>
                                                @else 
                                                    <option value="return" >Return</option>
                                                    <option value="dropping" >Dropping</option>
                                                @endif
                                            @else 
                                                @if ($stock->stockout_category == 'sales')
                                                    <option value="sales" >Sales</option>
                                                    <option value="return" >Return</option>
                                                @else 
                                                    <option value="return" >Return</option>
                                                    <option value="sales" >Sales</option>
                                                @endif
                                            @endif
                                        </select>
                                    </div>
                                    
                                    <div class="form-group" id="stock_items">
                                        <!-- <div id="items">   -->
                                            <div class="table-responsive alert-primary">  
                                                <table class="table table-bstocked" id="" cellspacing="0" cellpadding="0" style="bstock:none; bstock-collapse: collapse;">  
                                                    <tbody id="dynamic_field">
                                                        <tr>  
                                                            <td width="50%">
                                                                <div class="form-group">
                                                                <label for="item">Item</label>
                                                                    <select id="barang_item_id" name="barang_item_id[]" class="form-control barang_item_id">
                                                                        @foreach ($products_item as $b)
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
                                                                <input type="number" name="qty[]" id="qty" value="1" class="form-control" >
                                                            </td> 
                                                            <td>
                                                                <label for="item">Harga</label>
                                                                <input type="number" name="price[]" id="price" value="0" class="form-control" >
                                                            </td>
                                                        </tr>  
                                                    </tbody>
                                                    <tfoot>
                                                        <td><button type="button" name="add" id="add" onclick="" class="btn btn-primary">Tambah Item</button></td>  
                                                    </tfoot>
                                                </table>  
                                            </div>  
                                        <!-- </div> -->
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
            bstock: 1px solid #CCC;
            bstock-collapse: collapse;
        }

        td {
            bstock: none;
        }
        .table-bstocked td, .table-bstocked th {
            bstock: 0px solid #dee2e6;
        }
    </style>
@endsection

@section('custom_js')
    <script src="{{ asset('js/select2.js') }}"></script>
    <script>
        $('#add').click(function(){  
            i++;  
            $('#dynamic_field').append('<tr id="row'+i+'"><td width="40%"><select id="barang_item_id" name="barang_item_id[]" class="form-control barang_item_id"> @foreach ($products_item as $barang) <option value="{{ $barang->barang_id }}" >{{ $barang->name . " (" . rupiah($b->selling_price, TRUE) . ")" }}</option> @endforeach </select></td>  <td width="15%"><input type="text" name="notes_item[]" id="notes_item" placeholder="0" value="100" class="form-control" >  <td width="10%"><input type="number" name="qty[]" id="qty" value="1" class="form-control" ></td><td><input type="number" name="price[]" id="price" value="0" class="form-control"></td><td><button type="button" name="remove" id="'+i+'" class="btn btn-danger btn_remove">X</button></td></tr>');  
            
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

            $('#depo_name').select2({
                placeholder:'Pilih Pembeli',
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