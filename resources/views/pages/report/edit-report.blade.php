<!-- Modal -->
<div class="modal fade custom-modal" id="modal-edit-report" tabindex="-1" role="dialog" aria-labelledby="customModal" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel2">Tambah Kategori Baru</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form method="POST" id="formEdit" autocomplete="off">
            @method('PUT')
            @csrf
                <div class="modal-body">
                    <div class="card-body">
                        <input name="id" type="hidden" class="form-control" id="id" placeholder="id">
                        <div class="form-group">
                            <label for="reportDateEdit">Tanggal</label>
                            <input name="reportDate" type="text" class="form-control" id="reportDateEdit" placeholder="2021-01-01 12:12:12" autocomplete="off" required>
                        </div>
                        <div class="form-group">
                            <label for="reportTypeEdit">Jenis Pengeluaran</label>
                            <input name="reportType" type="text" class="form-control" id="reportTypeEdit" placeholder="Bayar Listrik" autocomplete="off" required>
                        </div>
                        <div class="form-group">
                            <label for="reportNominalEdit">Nominal</label>
                            <input name="reportNominal" type="number" class="form-control" id="reportNominalEdit" placeholder="5000000" autocomplete="off" required>
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