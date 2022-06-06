<!-- Modal -->
<div class="modal fade custom-modal" id="modal-add-product" tabindex="-1" role="dialog" aria-labelledby="customModal" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel2">Tambah Product Baru</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form method="POST" autocomplete="off">
            @csrf
                <div class="modal-body">
                    <div class="card-body">
                        
                        <div class="form-group">
                            <label for="inProduct">Produk</label>
                            <select id="inProduct" name="inProduct" class="form-control">
                                @foreach ($products as $product)
                                <option value="{{ $product->product_id }}" >{{ $product->product_name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="inDepo">Depo</label>
                            <select id="inDepo" name="inDepo" class="form-control">
                                @foreach ($depos as $depo)
                                <option value="{{ $depo->depo_id }}" >{{ $depo->depo_name }}</option>
                                @endforeach
                            </select>
                        </div>

                        @if (Auth::user()->role == 'freelance')
                        <div class="form-group">
                            <label for="inDepoPrice">Harga Depo</label>
                            <input type="number" min="0" name="inDepoPrice" class="form-control" id="inDepoPrice" required placeholder="50000">
                        </div>
                        @endif
                        
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