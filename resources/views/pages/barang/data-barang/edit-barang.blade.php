<!-- Modal -->
<div class="modal fade custom-modal" id="modal-edit-barang" tabindex="-1" role="dialog" aria-labelledby="customModal" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel2">Edit Barang</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="formEdit" method="POST" autocomplete="off" >
            @method('PUT')
            @csrf
                <div class="modal-body">
                    <div class="card-body">
                        
                        <label for="inputSupplierEdit">Supplier</label>
                        <div class="form-row">
                            <div class="form-group col-md-9">
                                <select id="inputSupplierEdit" name="inputSupplier" class="form-control">
                                    @foreach ($suppliers as $supplier)
                                        <option value="{{ $supplier->supplier_id }}" >{{ $supplier->supplier_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group col-md-3">
                                    <a href="/supplier" id="btneditSupplier" class="form-control btn btn-primary col-md-12">
                                        <i class="fas fa-plus" aria-hidden="tru"></i> Supplier
                                    </a>
                                
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="productNameEdit">Nama Produk</label>
                            <input type="text" name="name" class="form-control" id="productNameEdit" placeholder="Kerupuk" autocomplete="off" required>
                            
                        </div>
                        <div class="form-group">
                            <label for="inputCategoryEdit">Kategori</label>
                            <select id="inputCategoryEdit" name="inputCategory" class="form-control">
                                @foreach ($categories as $category)
                                <option value="{{ $category->category_id }}" >{{ $category->category_name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="merkEdit">Merek</label>
                            <input type="text" name="merk" class="form-control" id="merkEdit" required placeholder="Udang Makmur">
                        </div>
                        <div class="form-group">
                            <label for="buyingPriceEdit">Harga Beli</label>
                            <input type="number" min="0" name="buyingPrice" class="form-control" id="buyingPriceEdit" required placeholder="40000" >
                        </div>
                        <div class="form-group">
                            <label for="sellingPriceEdit">Harga Jual</label>
                            <input type="number" min="0" name="sellingPrice" class="form-control" id="sellingPriceEdit" required placeholder="500">
                        </div>
                        <!-- <div class="form-row">
                            <div class="form-group col-md-9">
                                <label for="discountEdit">Diskon</label>
                                <input type="number" name="discount" class="form-control" id="discountEdit" required placeholder="50">
                            </div>
                            <div class="form-group col-md-3">
                                <label for="inputDiscTypeEdit">Tipe</label>
                                <select id="inputDiscTypeEdit" name="discountType" class="form-control">
                                    <option selected>%</option>
                                    <option>Rupiah</option>
                                </select>
                            </div>
                        
                        </div> -->
                        <div class="form-group">
                            <label for="stockEdit">Stok</label>
                            <input type="number" min="0" name="stock" class="form-control" id="stockEdit" placeholder="50" required>
                        </div>
                        <div class="form-group">
                            <label for="inputStatusEdit">Status</label>
                            <select id="inputStatusEdit" name="inputStatus" class="form-control">
                                <option selected>Aktif</option>
                                <option>Tidak Aktif</option>
                            </select>
                        </div>
                        <!-- <div class="card-body">
                            <div class="col-12"></div>
                            <input type="file" name="files[]" id="filer_example1" multiple="multiple">
                        </div> -->
                        
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
        
    </div>
</div>