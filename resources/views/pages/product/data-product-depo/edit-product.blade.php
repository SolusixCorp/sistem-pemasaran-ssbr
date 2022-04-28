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
            <form id="formEdit" method="POST" autocomplete="off" enctype="multipart/form-data">
            @method('PUT')
            @csrf
                <div class="modal-body">
                    <div class="card-body">
                        
                        <div class="form-group">
                            <label for="upProductName">Nama Produk</label>
                            <input type="text" name="upProductName" class="form-control" id="upProductName" placeholder="Gudang Garam Surya" autocomplete="off" required readonly>
                        </div>
                        
                        <div class="form-group">
                            <label for="upStatus">Status</label>
                            <select id="upStatus" name="upStatus" class="form-control">
                                <option>Aktif</option>
                                <option>Tidak Aktif</option>
                            </select>
                        </div>
                        
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