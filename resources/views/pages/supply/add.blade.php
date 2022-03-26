<!-- Modal -->
<div class="modal fade custom-modal" id="modal-add" tabindex="-1" role="dialog" aria-labelledby="customModal" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel2">Tambah Supply Baru</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form method="POST" autocomplete="off" >
            @csrf
                <div class="modal-body">
                    <div class="card-body">
                        
                        <label for="inputSupplier">Barang</label>
                        <div class="form-row">
                            <div class="form-group col-md-10">
                                <select id="inputBarang" name="inputBarang" class="form-control">
                                    @foreach ($barangs as $barang)
                                        <option value="{{ $barang->barang_id }}" >{{ $barang->supplier->supplier_name . " - " . $barang->name . " - " . rupiah($barang->buying_price, true) }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group col-md-2">
                                    <a href="/data-barang" id="btnAddSupplier" class="form-control btn btn-primary col-md-12">
                                        <i class="fas fa-plus" aria-hidden="tru"></i> Barang
                                    </a>
                                
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="qty">Qty</label>
                            <input type="number"  min="0" name="qty" class="form-control" id="qty" placeholder="10" required>
                        </div>
                        <div class="form-group">
                            <label for="notes">Catatan</label>
                            <input type="text" name="notes" class="form-control" id="notes" required placeholder="Catatan" value="-">
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