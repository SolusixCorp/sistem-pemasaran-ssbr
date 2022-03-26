<!-- Modal -->
<div class="modal fade custom-modal" id="modal-add-expense" tabindex="-1" role="dialog" aria-labelledby="customModal" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel2">Tambah Kategori Baru</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form method="POST" data-toggle="validator" autocomplete="off" role="form">
            @csrf
                <div class="modal-body">
                    <div class="card-body">
                        <div class="form-group">
                            <label for="expenseDate">Tanggal</label>
                            <input name="expenseDate" type="text" class="form-control" id="expenseDate" placeholder="2021-01-01 12:12:12" autocomplete="off" required>
                        </div>
                        <div class="form-group">
                            <label for="expenseType">Jenis Pengeluaran</label>
                            <input name="expenseType" type="text" class="form-control" id="expenseType" placeholder="Bayar Listrik" autocomplete="off" required>
                        </div>
                        <div class="form-group">
                            <label for="expenseNominal">Nominal</label>
                            <input name="expenseNominal" type="number" class="form-control" id="expenseNominal" placeholder="5000000" autocomplete="off" required>
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