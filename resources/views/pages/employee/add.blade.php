<!-- Modal -->
<div class="modal fade custom-modal" id="modal-add" tabindex="-1" role="dialog" aria-labelledby="customModal" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel2">Tambah Karyawan</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form autocomplete="off" role="form" method="POST">
            @csrf
                <div class="modal-body">
                    <div class="card-body">
                        
                        <div class="form-group">
                            <label for="addCustomerName">Nama Employee</label>
                            <input name="customerName" type="text" class="form-control" id="addCustomerName" placeholder="Ali Hasan" autocomplete="off" required>
                            
                        </div>

                        <div class="form-group">
                            <label for="addCustomerName">NIK</label>
                            <input name="customerName" type="number" class="form-control" id="addCustomerName" placeholder="321000201020102" autocomplete="off" required>
                            
                        </div>

                        <div class="form-group">
                            <label for="addCustomerName">Posisi</label>
                            <input name="customerName" type="text" class="form-control" id="addCustomerName" placeholder="Admin Gudang" autocomplete="off" required>
                            
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