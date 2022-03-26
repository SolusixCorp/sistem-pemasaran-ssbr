<!-- Modal -->
<div class="modal fade custom-modal" id="modal-edit-company" tabindex="-1" role="dialog" aria-labelledby="customModal" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel2">Edit Data Perusahaan</h5>
            </div>
            <form method="POST" autocomplete="off" role="form" id="formEdit" enctype="multipart/form-data">
            @method('PUT')
            @csrf
                <div class="modal-body">
                    <div class="card-body">
                        
                        <input name="id" type="hidden" class="form-control" id="id" placeholder="id">

                        <div class="form-group">
                            <label for="nameEdit">Nama Perusahaan</label>
                            <input name="name" type="text" class="form-control" id="nameEdit" placeholder="CV Udang Makmur" autocomplete="off" required>
                            
                        </div>
                        <div class="form-group">
                            <label for="addressEdit">Alamat</label>
                            <input name="address" type="text" class="form-control" id="addressEdit" placeholder="Gebang Wetan 23 B - Sukolilo, Surabaya, Jawa Timur" autocomplete="off" required>
                            
                        </div>
                        <div class="form-group">
                            <label for="emailEdit">Email</label>
                            <input name="email" type="text" class="form-control" id="emailEdit" placeholder="udangmakmur@gmail.com" autocomplete="off" required>
                            
                        </div>
                        <div class="form-group">
                            <label for="phoneEdit">Nomor Telepon</label>
                            <input name="phone" type="text" class="form-control" id="phoneEdit" placeholder="085111222333" autocomplete="off" required>
                        </div>

                        <div class="form-group">
                            <label for="prefixNotaEdit">Prefix Nota</label>
                            <input name="prefix" type="text" class="form-control" id="prefixNotaEdit" placeholder="TRX" autocomplete="off" required>
                        </div>

                        <!-- <div class="form-group">
                            <label for="companyLogoImg">Logo Perusahaan</label>
                            <div class="card mb-3">
                                    <img id="companyLogoImg" class="img-fluid" data-toggle="magnify" src="https://via.placeholder.com/600x350" alt="Sample Image">
                                </div>
                            <div class=" mt-2">
                                <input type="file" name="companyLogo[]" id="companyLogoEdit" multiple="multiple">
                            </div>
                            <h6 id="change" class="text-center" style="cursor:pointer">Tampilkan Logo Lama</h6>
                        </div>                           -->
                        
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