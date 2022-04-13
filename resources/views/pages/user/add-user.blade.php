<!-- Modal -->
<div class="modal fade custom-modal" id="modal-add-user" tabindex="-1" role="dialog" aria-labelledby="customModal" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel2">Tambah User Baru</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <form method="POST" action="{{ route('user.store') }}" autocomplete="off">
                @csrf
                <div class="modal-body">
                    <div class="card-body">
                            <div class="form-group">
                                <label for="name">Nama</label>
                                <input type="text" name="name" class="form-control" id="name" required placeholder="Ali Hasan">
                            </div>
                            <div class="form-group">
                                <label for="username">Username</label>
                                <input type="text" name="username" class="form-control" id="username" required placeholder="ali_hasan">
                            </div>
                            <!-- <div class="form-group">
                                <label for="email">Email</label>
                                <input type="text" name="email" class="form-control" id="email" required placeholder="alihasan@gmail.com" autocomplete="off">
                            </div> -->
                            <div class="form-group">
                                <label for="password">Password</label>
                                <input type="password" name="password" class="form-control" id="password" required placeholder="Password">
                            </div>
                            <div class="form-group">
                                <label for="password_confirmation">Password Konfirmasi</label>
                                <input type="password" name="password_confirmation" class="form-control" required id="password_confirmation" placeholder="Password Konfirmasi">
                            </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <input type="submit" value="Simpan" class="btn btn-primary"/>
                </div>
            </form>
        </div>
    </div>
</div>