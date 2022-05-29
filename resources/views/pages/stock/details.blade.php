<!-- Modal -->
<div class="modal fade" id="modal-details" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Details Penjualan</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="widget-messages" id="data-detail-modal" style="margin-top: 0px;">
                    
                    <!-- <div class="message-item">
                        <h6 class="message-item-user">Kode Transaksi</h6>
                        <h6 id="vKodeTransaksi" class="message-item-date"></h6>
                    </div> -->

                    <div class="message-item">
                        <h6 class="message-item-user">Tanggal Transaksi</h6>
                        <h6 id="vTanggalTransaksi" class="message-item-date"></h6>
                    </div>

                    <div class="message-item">
                        <h6 class="message-item-user">Nama Depo</h6>
                        <h6 id="vDepoNama" class="message-item-date"></h6>
                    </div>

                    <div class="message-item">
                        <h6 class="message-item-user">Tipe Stok</h6>
                        <h6 id="vStockType" class="message-item-date">IN</h6>
                    </div>

                    <div class="message-item">
                        <h6 class="message-item-user">Kategori</h6>
                        <h6 id="vDesc" class="message-item-date"></h6>
                    </div>

                    <div class="message-item">
                        <h6 class="message-item-user">Dikirim ?</h6>
                        <h6 id="vDelivered" class="message-item-date">Ya</h6>
                    </div>

                    <table class="table table-responsive-xl table-bordered">
                        <thead>
                            <tr>
                                <th scope="col" width="5%">#</th>
                                <th scope="col" width="50%">Produk</th>
                                <th scope="col" width="5%">Qty</th>
                                <th scope="col" width="5%">Remaining Stock</th>
                            </tr>
                        </thead>
                        <tbody id="details_product">
                           
                        </tbody>
                    </table>

                </div>
            </div>
        </div>
    </div>
</div>
