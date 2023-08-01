<div class="modal fade" id="modalUpdateProvider" aria-labelledby="modalUpdateProvider-title" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content rounded shadow border-0">
            <div class="modal-header border-bottom">
                <h5 class="modal-title" id="modalUpdateProvider-title">Ubah Metode Pembayaran</h5>
                <button type="button" class="btn btn-icon btn-close" data-bs-dismiss="modal" id="close-modal"><i class="uil uil-times fs-4 text-dark"></i></button>
            </div>
            <form method="post" action="#" autocomplete="off" id="frmUpdateProvider">
                @csrf
                @method("PUT")
                <div class="modal-body">
                    <div class="form-group mb-3">
                        <label>Metode Pembayaran</label>
                        <select class="form-control select2" name="provider_id" style="width:100%;">
                            @foreach($providers as $index => $row)
                            <option value="{{$row->id}}">{{$row->name}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Tutup</button>
                    <button type="submit" class="btn btn-primary btn-sm">Ubah Pembayaran</button>
                </div>
            </form>
        </div>
    </div>
</div>