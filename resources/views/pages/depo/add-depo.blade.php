<!-- Modal -->
<div class="modal fade custom-modal" id="modal-add-depo" tabindex="-1" role="dialog" aria-labelledby="customModal" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel2">Tambah Depo</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form autocomplete="off" role="form" method="POST">
            @csrf
                <div class="modal-body">
                    <div class="card-body">

                        <div class="form-group">
                            <label for="inUser">Pemilik Depo</label>
                            <select id="inUser" name="inUser" class="form-control">
                                @foreach ($users as $user)
                                <option value="{{ $user->user_id }}" >{{ $user->user_name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="inDepoType">Tipe Depo</label>
                            <select id="inDepoType" name="inDepoType" class="form-control">
                                <option value="principle" >Principle</option>
                                <option value="freelance" >Freelance</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="inDepoAddress">Alamat</label>
                            <input name="inDepoAddress" type="text" class="form-control" id="inDepoAddress" placeholder="Gebang Wetan 23 B - Sukolilo, Surabaya, Jawa Timur" autocomplete="off" required>
                            
                        </div>

                        <div class="form-group">
                            <label for="inDepoCity">Kota</label>
                            <input name="inDepoCity" type="text" class="form-control" id="inDepoCity" placeholder="Surabaya" autocomplete="off" required>
                            
                        </div>

                        <div class="form-group">
                            <label for="inDepoEmail">Email</label>
                            <input name="inDepoEmail" type="text" class="form-control" id="inDepoEmail" placeholder="admin-depo@gmail.com" autocomplete="off" required>
                            
                        </div>
                        <div class="form-group">
                            <label for="inDepoPhone">Nomor Telepon</label>
                            <input name="inDepoPhone" type="text" class="form-control" id="inDepoPhone" placeholder="085111222333" autocomplete="off" required>
                            
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