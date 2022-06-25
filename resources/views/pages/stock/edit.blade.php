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

                    <form method="POST" autocomplete="off" action="{{ route('stock.update', ['id' => $stock['id']]) }}" >
                        @csrf
                            <div class="modal-body">
                                <div class="card-body">
                                    <div class="form-group" style="margin: 0;">
                                        <label for="trx_date">Tanggal Transaksi</label>       
                                    </div>
                                    <div class="row">
                                        <div class="col-xl-3 col-lg-4">
                                            <div class="form-group">
                                                <input type="date" class="form-control"  name="date" value="{{ $stock['input_date'] }}" readonly/>
                                            </div>
                                        </div>
                                        <div class="col-xl-2 col-lg-4">
                                            <div class="form-group">
                                                <input type="time" class="form-control" name="time" value="{{ $stock['input_time'] }}" readonly />
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="form-group">
                                        <label for="depo_name">Nama Depo</label>
                                        <select id="depo_name" name="depo_name" class="form-control js-example-basic-single">
                                            <option value="{{ $stock['depo_id'] }}" >{{ $stock['depo'] }}</option>
                                        </select>
                                    </div>

                                    <div class="form-group">
                                        <label for="stock_type">Tipe Stok</label>
                                        <select id="stock_type" name="stock_type" class="form-control js-example-basic-single">
                                            @if ($stock['type'] == 'IN')
                                                <option value="in" >STOCK IN</option>
                                            @else 
                                                <option value="out" >STOCK OUT</option>
                                            @endif
                                        </select>
                                    </div>

                                    <div class="form-group">
                                        <label for="stock_category" id="stock_category_label">Kategori Stok @if ($stock['type'] == 'IN') {{ '(IN)' }} @else {{ '(OUT)' }} @endif</label>
                                        <select id="stock_category" name="stock_category" class="form-control js-example-basic-single">
                                            @if ($stock['type'] == 'IN')
                                                @if ($stock['desc'] == 'Dropping')
                                                    <option value="dropping" >Dropping</option>
                                                    <option value="return" >Return</option>
                                                @else 
                                                    <option value="return" >Return</option>
                                                    <option value="dropping" >Dropping</option>
                                                @endif
                                            @else 
                                                @if ($stock['desc'] == 'Sales')
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
                                                        @foreach ($stock['products'] as $product)
                                                        <tr>  
                                                            <td width="40%">
                                                                <div class="form-group">
                                                                <label for="item">Item</label>
                                                                    <select id="product_item_id" name="product_item_id[]" class="form-control product_item_id">
                                                                        <option value="{{ $product['product_id'] }}" >{{ $product['product_name'] }}</option>
                                                                        @foreach ($products_item as $b)
                                                                            @if ($b['product_id'] != $product['product_id']) 
                                                                                <option value="{{ $b['product_id'] }}" >{{ $b['product_name'] }}</option>
                                                                            @endif
                                                                        @endforeach
                                                                    </select>
                                                                </div>
                                                            </td>  
                                                            <td width="15%">
                                                                <label for="item">Remaining Stok</label>
                                                                <input type="text" name="remmaining_stock[]" id="remmaining_stock" placeholder="0" class="form-control" value="{{ $product['remaining_stock'] }}" readonly>
                                                            </td>  
                                                            <td width="12%">
                                                                <label for="item">Qty</label>
                                                                <input type="number" name="qty[]" id="qty" class="form-control" value="{{ $product['qty'] }}" >
                                                            </td> 
                                                            <td>
                                                            <label for="item">Harga</label>
                                                                <select id="price" name="price[]" class="form-control price">
                                                                <option value="{{ $product['price'] }}" >{{ $product['price'] }}</option>
                                                                    @foreach ($products_item['0']['price'] as $price)
                                                                        @if ($price != $product['price'])
                                                                            <option value="{{ $price }}" >{{ $price }}</option>
                                                                        @endif
                                                                    @endforeach
                                                                </select>
                                                            </td>
                                                        </tr>  
                                                        @endforeach
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
                                <a href="{{ route('stock.index') }}" class="btn btn-secondary">Batal</a>
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
            $('#dynamic_field').append('<tr id="row'+i+'"><td width="40%"><select id="'+i+'" name="product_item_id[]" class="form-control product_item_id"> @foreach ($products_item as $product) <option value="{{ $product['product_id'] }}" >{{ $product['product_name'] }}</option> @endforeach </select></td>  <td width="15%"><input type="text" name="remmaining_stock[]" id="remmaining_stock'+i+'" placeholder="0" value="{{ $product['stock_remaining'] }}" class="form-control" readonly><td width="12%"><input type="number" name="qty[]" id="qty" value="1" class="form-control" ></td><td><select id="price'+i+'" name="price[]" class="form-control price">@foreach ($product['price'] as $price) <option value="{{ $price }}" >{{ $price }}</option> @endforeach</select></td><td><button type="button" name="remove" id="'+i+'" class="btn btn-danger btn_remove">X</button></td></tr>');  
       
            $(document).ready(function(){
                $('.product_item_id').select2({
                    theme:'bootstrap4',
                    tags:true,
                }).on('select2:close', function(){
                    var element = $(this);
                    var element_val = $.trim(element.val());
                    var id = $(this).attr("id");

                    console.log(element_val)
                    
                    if(element_val != '') {
                    $.ajax({
                        url: "{{ url('/') }}" + "/stock/product/" + element_val,
                        method: "GET",
                        success: function(data) {
                            $('#remmaining_stock'+id+'').val(data.stock_remaining);
                            var priceOps = $('#price'+id+'');
                            priceOps.empty();
                            for (let i = 0; i < data.price.length; i++) {
                                priceOps.append('<option value="' + data.price[i] + '">'+ data.price[i] +'</option>');
                            }
                        }
                    })
                }
                })
            })
        });  

        $(document).on('click', '.btn_remove', function(){  
            var button_id = $(this).attr("id");   
            $('#row'+button_id+'').remove();  
        });  

        $(document).ready(function(){
            //Select Item
            $('.product_item_id').select2({
                theme:'bootstrap4',
                tags:true,
            }).on('select2:close', function(){
                var element = $(this);
                var element_val = $.trim(element.val());

                if(element_val != '') {
                    $.ajax({
                        url: "{{ url('/') }}" + "/stock/product/" + element_val,
                        method: "GET",
                        success: function(data) {
                            console.log(data)
                            $("#remmaining_stock").val(data.stock_remaining);
                            
                        }
                    })
                }

                console.log(element_val)
            });

            //Select Stock Type
            $('#stock_type').select2({
                theme:'bootstrap4',
                tags:true,
            }).on('select2:close', function(){
                var element = $(this);
                var element_val = $.trim(element.val());

                if(element_val != '') {
                    var stockCategoryOps = $('#stock_category');
                    var stockCategoryLabel = $('#stock_category_label');
                    stockCategoryOps.empty();
                    if (element_val == 'in') {
                        stockCategoryLabel.text('Kategori Stok (IN)');
                        stockCategoryOps.append('<option value="dropping">Dropping</option>');
                    } else {
                        stockCategoryLabel.text('Kategori Stok (OUT)');
                        stockCategoryOps.append('<option value="sales">Sales</option>');
                    }
                    stockCategoryOps.append('<option value="return">Return</option>');
                }

                console.log(element_val)
            });

        });

        // Single Date Picker
        $('input[name="singledatepicker"]').daterangepicker({
            singleDatePicker: true,
            showDropdowns: true
        });

        $(document).ready(function() {
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

        });
    </script>
@endsection