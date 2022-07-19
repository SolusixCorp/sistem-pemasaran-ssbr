<!-- Modal -->
<div class="modal fade custom-modal" id="modal-edit-user{{ $user->id }}" tabindex="-1" role="dialog" aria-labelledby="customModal" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel2">Edit User</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <form method="POST" action="{{ route('user.update', ['id' => $user->id]) }}" autocomplete="off">
                @csrf
                <div class="modal-body">
                    <div class="card-body">
                            <div class="form-group">
                                <label for="role">Role</label>
                                <select id="role" name="role" class="form-control js-example-basic-single">
                                    @if ($user->role == 'depo')
                                    <option value="depo" >Depo</option>
                                    <option value="ho" >Head Office</option>
                                    @else 
                                    <option value="ho" >Head Office</option>
                                    <option value="depo" >Depo</option>
                                    @endif
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="name">Nama</label>
                                <input type="text" name="name" value="{{ $user->name }}" class="form-control" id="name" required placeholder="Admin Depo">
                            </div>
                            <div class="form-group">
                                <label for="email">Email</label>
                                <input type="text" name="email" value="{{ $user->email }}" class="form-control" id="email" required placeholder="admin-depo@gmail.com" autocomplete="off">
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