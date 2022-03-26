<!-- Modal -->
<div class="modal fade custom-modal" id="modal-add-supplier" tabindex="-1" role="dialog" aria-labelledby="customModal" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel2">Tambah Supplier</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form autocomplete="off" role="form" method="POST">
            @csrf
                <div class="modal-body">
                    <div class="card-body">
                        
                        <input name="id" type="hidden" class="form-control" id="id" placeholder="id">

                        <div class="form-group">
                            <label for="name">Nama Supplier</label>
                            <input name="name" type="text" class="form-control" id="name" placeholder="CV Udang Makmur" autocomplete="off" required>
                            
                        </div>
                        <div class="form-group">
                            <label for="address">Alamat</label>
                            <input name="address" type="text" class="form-control" id="address" placeholder="Gebang Wetan 23 B - Sukolilo, Surabaya, Jawa Timur" autocomplete="off" required>
                            
                        </div>
                        <div class="form-group">
                            <label for="email">Email</label>
                            <input name="email" type="text" class="form-control" id="email" placeholder="udangmakmur@gmail.com" autocomplete="off" required>
                            
                        </div>
                        <div class="form-group">
                            <label for="phone">Nomor Telepon</label>
                            <input name="phone" type="text" class="form-control" id="phone" placeholder="085111222333" autocomplete="off" required>
                            
                        </div>
                        <!-- <div class="form-group">
                            <label for="inputStatus">Status</label>
                            <select id="inputStatus" name="inputStatus" class="form-control">
                                <option selected>Aktif</option>
                                <option>Tidak Aktif</option>
                            </select>
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