<div class="modal fade" id="modalStoreStock" aria-labelledby="modalStoreStock-title" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content rounded shadow border-0">
            <div class="modal-header border-bottom">
                <h5 class="modal-title" id="modalStoreStock-title">Tambah Stock</h5>
                <button type="button" class="btn btn-icon btn-close" data-bs-dismiss="modal" id="close-modal"><i class="uil uil-times fs-4 text-dark"></i></button>
            </div>
            <form method="POST" action="{{route('dashboard.products.stocks.store')}}" id="frmStoreStock" autocomplete="off">
                @csrf
                <input type="hidden" name="product_id" value="{{$result->id}}">
                <div class="modal-body">
                    <div class="form-group mb-3">
                        <label>Quantity<span class="text-danger">*</span></i></label>
                        <input type="number" class="form-control" placeholder="Quantity" name="qty">
                    </div>
                    <div class="form-group mb-3">
                        <label>Keterangan</label>
                        <textarea name="note" class="form-control" rows="5" placeholder="Keterangan"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                    <button type="submit" class="btn btn-primary">Submit</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="modalUpdateStock" aria-labelledby="modalUpdateStock-title" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content rounded shadow border-0">
            <div class="modal-header border-bottom">
                <h5 class="modal-title" id="modalUpdateStock-title">Edit Stock</h5>
                <button type="button" class="btn btn-icon btn-close" data-bs-dismiss="modal" id="close-modal"><i class="uil uil-times fs-4 text-dark"></i></button>
            </div>
            <form method="POST" action="#" id="frmUpdateStock" autocomplete="off">
                @csrf
                @method("PUT")
                <div class="modal-body">
                    <div class="form-group mb-3">
                        <label>Quantity<span class="text-danger">*</span></i></label>
                        <input type="number" class="form-control" placeholder="Quantity" name="qty">
                    </div>
                    <div class="form-group mb-3">
                        <label>Keterangan</label>
                        <textarea name="note" class="form-control" rows="5" placeholder="Keterangan"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                    <button type="submit" class="btn btn-primary">Submit</button>
                </div>
            </form>
        </div>
    </div>
</div>