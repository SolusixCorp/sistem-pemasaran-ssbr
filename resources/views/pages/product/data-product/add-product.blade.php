<!-- Modal -->
<div class="modal fade custom-modal" id="modal-add-product" tabindex="-1" role="dialog" aria-labelledby="customModal" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel2">Tambah Product Baru</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form method="POST" autocomplete="off" >
            @csrf
                <div class="modal-body">
                    <div class="card-body">
                        
                        <div class="form-group">
                            <label for="productName">Nama Produk</label>
                            <input type="text" name="inProductName" class="form-control" id="productName" placeholder="Gudang Garam Surya" autocomplete="off" required>
                            
                        </div>
                        <div class="form-group">
                            <label for="inputCategory">Kategori</label>
                            <select id="inputCategory" name="inCategory" class="form-control">
                                @foreach ($categories as $category)
                                <option value="{{ $category->id }}" >{{ $category->category_name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="description">Deskripsi</label>
                            <textarea type="text" name="inDescription" class="form-control" id="description"></textarea>
                        </div>
                        
                        <div class="form-group">
                            <label for="consumentPrice">Harga Konsumen</label>
                            <input type="number" min="0" name="inConsumentPrice" class="form-control" id="consumentPrice" required placeholder="40000" >
                        </div>

                        <div class="form-group">
                            <label for="retailPrice">Harga Retail</label>
                            <input type="number" min="0" name="inRetailPrice" class="form-control" id="retailPrice" required placeholder="50000">
                        </div>
                    
                        <div class="form-group">
                            <label for="retailPrice">Harga Sub Whole</label>
                            <input type="number" min="0" name="inSubWholePrice" class="form-control" id="retailPrice" required placeholder="50000">
                        </div>

                        
                        <div class="form-group">
                            <label for="retailPrice">Harga Whole</label>
                            <input type="number" min="0" name="inWholesalesPrice" class="form-control" id="retailPrice" required placeholder="50000">
                        </div>

                        <div class="form-row">
                            <div class="form-group col-md-12">
                                <label for="validatedCustomFile">Foto Produk</label>
                                <div class="custom-file">
                                    <input type="file" name="inPhoto" class="custom-file-input" id="validatedCustomFile">
                                    <label class="custom-file-label" for="validatedCustomFile">Pilih
                                        Gambar...</label>
                                </div>
                            </div>
                        </div>
                        <!-- <div class="form-row">
                            <div class="form-group col-md-9">
                                <label for="discount">Diskon</label>
                                <input type="number" name="discount" class="form-control" id="discount" required placeholder="50">
                            </div>
                            <div class="form-group col-md-3">
                                <label for="inputDiscType">Tipe</label>
                                <select id="inputDiscType" name="discountType" class="form-control">
                                    <option selected>%</option>
                                    <option>Rupiah</option>
                                </select>
                            </div>
                        
                        </div> -->
                        <!-- <div class="form-group">
                            <label for="stock">Stok</label>
                            <input type="number"  min="0" name="stock" class="form-control" id="stock" placeholder="50" required>
                        </div> -->
                        <div class="form-group">
                            <label for="inputStatus">Status</label>
                            <select id="inputStatus" name="inStatus" class="form-control">
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