<!-- Modal -->
<div class="modal fade custom-modal" id="modal-edit-employee" tabindex="-1" role="dialog" aria-labelledby="customModal" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel2">Tambah Employee Baru</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="formEdit" autocomplete="off" role="form" method="POST">
            @method('PUT')
            @csrf
                <div class="modal-body">
                    <div class="card-body">
                        
                        <input name="id" type="hidden" class="form-control" id="id" placeholder="id">
                        
                        <div class="form-group">
                            <label for="editCustomerName">Nama Customer</label>
                            <input name="customerName" type="text" class="form-control" id="editCustomerName" placeholder="Ali Hasan" autocomplete="off" required>
                            
                        </div>
                        <div class="form-group">
                            <label for="editCustomerPhone">Phone</label>
                            <input name="customerPhone" type="text" class="form-control" id="editCustomerPhone" placeholder="082000111222" autocomplete="off" required>
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