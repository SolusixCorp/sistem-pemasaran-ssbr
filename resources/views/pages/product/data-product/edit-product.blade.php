<!-- Modal -->
<div class="modal fade custom-modal" id="modal-edit-product" tabindex="-1" role="dialog" aria-labelledby="customModal" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel2">Edit Product</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="formEdit" method="POST" autocomplete="off" >
            @method('PUT')
            @csrf
                <div class="modal-body">
                    <div class="card-body">
                        
                        <div class="form-group">
                            <label for="upProductName">Nama Produk</label>
                            <input type="text" name="upProductName" class="form-control" id="upProductName" placeholder="Gudang Garam Surya" autocomplete="off" required>
                            
                        </div>
                        <div class="form-group">
                            <label for="upCategory">Kategori</label>
                            <select id="upCategory" name="upCategory" class="form-control">
                                @foreach ($categories as $category)
                                    <option value="{{ $category->id }}">{{ $category->category_name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="upDescription">Deskripsi</label>
                            <textarea type="text" name="upDescription" class="form-control" id="upDescription"></textarea>
                        </div>
                        
                        <div class="form-group">
                            <label for="upConsumentPrice">Harga Konsumen</label>
                            <input type="number" min="0" name="upConsumentPrice" class="form-control" id="upConsumentPrice" required placeholder="40000" >
                        </div>

                        <div class="form-group">
                            <label for="upRetailPrice">Harga Retail</label>
                            <input type="number" min="0" name="upRetailPrice" class="form-control" id="upRetailPrice" required placeholder="50000">
                        </div>
                    
                        <div class="form-group">
                            <label for="upSubWholePrice">Harga Sub Whole</label>
                            <input type="number" min="0" name="upSubWholePrice" class="form-control" id="upSubWholePrice" required placeholder="50000">
                        </div>

                        
                        <div class="form-group">
                            <label for="upWholesalesPrice">Harga Whole</label>
                            <input type="number" min="0" name="upWholesalesPrice" class="form-control" id="upWholesalesPrice" required placeholder="50000">
                        </div>

                        <div class="form-row">
                            <div class="form-group col-md-12">
                                <label for="validatedCustomFile">Foto Produk</label>
                                <div class="custom-file">
                                    <input type="file" name="upPhoto" class="custom-file-input" id="validatedCustomFile">
                                    <label class="custom-file-label" for="validatedCustomFile">Pilih
                                        Gambar...</label>
                                </div>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label for="upStatus">Status</label>
                            <select id="upStatus" name="upStatus" class="form-control">
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