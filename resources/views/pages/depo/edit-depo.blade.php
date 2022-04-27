<!-- Modal -->
<div class="modal fade custom-modal" id="modal-edit-depo" tabindex="-1" role="dialog" aria-labelledby="customModal" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel2">Edit Depo</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form method="POST" autocomplete="off" role="form" id="formEdit">
            @method('PUT')
            @csrf
                <div class="modal-body">
                    <div class="card-body">

                        <div class="form-group">
                            <label for="upUser">Pemilik Depo</label>
                            <select id="upUser" name="upUser" class="form-control">
                                @foreach ($users as $user)
                                <option value="{{ $user->user_id }}" >{{ $user->user_name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="upDepoType">Tipe Depo</label>
                            <select id="upDepoType" name="upDepoType" class="form-control">
                                <option value="principle" >Principle</option>
                                <option value="freelance" >Freelance</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="upDepoAddress">Alamat</label>
                            <input name="upDepoAddress" type="text" class="form-control" id="upDepoAddress" placeholder="Gebang Wetan 23 B - Sukolilo, Surabaya, Jawa Timur" autocomplete="off" required>
                            
                        </div>

                        <div class="form-group">
                            <label for="upDepoCity">Kota</label>
                            <input name="upDepoCity" type="text" class="form-control" id="upDepoCity" placeholder="Surabaya" autocomplete="off" required>
                            
                        </div>

                        <div class="form-group">
                            <label for="upDepoEmail">Email</label>
                            <input name="upDepoEmail" type="text" class="form-control" id="upDepoEmail" placeholder="admin-depo@gmail.com" autocomplete="off" required>
                            
                        </div>
                        <div class="form-group">
                            <label for="upDepoPhone">Nomor Telepon</label>
                            <input name="upDepoPhone" type="text" class="form-control" id="upDepoPhone" placeholder="085111222333" autocomplete="off" required>
                            
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