@extends('layouts.dashboard')

@section('title', 'Stock Flow')
@section('breadcrumb', 'Stock Flow')

@section('content')
    <div class="row">
        <div class="col-12" id="add-order">
            <div class="card mb-3">
                <div class="card-header">
                    <h3><i class="fas fa-dolly-flatbed"></i> Stock Flow Baru</h3>
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

                    <form method="POST" autocomplete="off" action="{{ route('stock.store') }}" >
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
                                            @foreach ($depos as $depo)
                                                <option value="{{ $depo->id }}" >{{ $depo->name }}</option>
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
                                        <label for="stock_category" id="stock_category_label">Kategori Stok (IN)</label>
                                        <select id="stock_category" name="stock_category" class="form-control js-example-basic-single">
                                            <option value="dropping" >Dropping</option>
                                            <option value="return" >Return</option>
                                            <option value="stock" >Stock</option>
                                            <option value="return" >Return</option>
                                        </select>
                                    </div>
                                    
                                    <div class="form-group" id="order_items">
                                        <!-- <div id="items">   -->
                                            <div class="table-responsive alert-primary">  
                                                <table class="table table-bordered" id="" cellspacing="0" cellpadding="0" style="border:none; border-collapse: collapse;">  
                                                    <tbody id="dynamic_field">
                                                        <tr>  
                                                            <td width="50%">
                                                                <div class="form-group">
                                                                <label for="item">Item</label>
                                                                    <select id="product_item_id" name="product_item_id[]" class="form-control product_item_id">
                                                                        @foreach ($products_item as $b)
                                                                            <option value="{{ $b['product_id'] }}" >{{ $b['product_name'] }}</option>
                                                                        @endforeach
                                                                    </select>
                                                                </div>
                                                            </td>  
                                                            <td width="15%">
                                                                <label for="item">Remaining Stok</label>
                                                                <input type="text" name="remmaining_stock[]" id="remmaining_stock" placeholder="0" value="100" class="form-control" readonly>
                                                            </td>  
                                                            <td width="10%">
                                                                <label for="item">Qty</label>
                                                                <input type="number" name="qty[]" id="qty" oninput="qtyCheck()" value="1" class="form-control" >
                                                            </td> 
                                                            <td>
                                                                <label for="item">Harga</label>
                                                                <select id="price" name="price[]" class="form-control price">
                                                                    @foreach ($products_item as $b)
                                                                        <option value="{{ $b['product_id'] }}" >{{ $b['product_name'] }}</option>
                                                                    @endforeach
                                                                </select>
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
            $('#dynamic_field').append('<tr id="row'+i+'"><td width="40%"><select id="'+i+'" name="product_item_id[]" class="form-control product_item_id"> @foreach ($products_item as $product) <option value="{{ $product['product_id'] }}" >{{ $product['product_name'] }}</option> @endforeach </select></td>  <td width="15%"><input type="text" name="remmaining_stock[]" id="remmaining_stock'+i+'" placeholder="0" value="100" class="form-control" readonly><td width="10%"><input type="number" name="qty[]" id="qty" value="1" class="form-control" ></td><td><select id="price'+i+'" name="price[]" class="form-control price">@foreach ($product['price'] as $price) <option value="{{ $price }}" >{{ $price }}</option> @endforeach</select></td><td><button type="button" name="remove" id="'+i+'" class="btn btn-danger btn_remove">X</button></td></tr>');  
       
            $(document).ready(function(){
                $('.product_item_id').select2({
                    theme:'bootstrap4',
                    tags:true,
                }).on('select2:close', function(){
                    var element = $(this);
                    var element_val = $.trim(element.val());

                    console.log(element_val)
                    if(element_val != '') {
                    $.ajax({
                        url: "{{ url('/') }}" + "/stock/product/" + element_val,
                        method: "GET",
                        success: function(data) {
                            $('#remmaining_stock'+i+'').val(data.stock_remaining);
                            var priceOps = $('#price'+i+'');
                            priceOps.empty();
                            for (let i = 0; i < data.price.length; i++) {
                                priceOps.append('<option value="' + data.price[i] + '">'+ data.price[i] +'</option>');
                            }
                            console.log(id)
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

            

            // $('input[name="qty"]').addEventListener('change', (e) => {  
            //     console.log(e.target.value);  
            // });

        });

        // function qtyCheck() {
        //     var element = $(this);
        //     var element_val = $.trim(element.val());
        //     console.log(element_val); 
        // }

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