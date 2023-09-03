<div class="modal fade" id="modalAddStock" aria-labelledby="modalAddStock-title" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content rounded shadow border-0">
            <div class="modal-header border-bottom">
                <h5 class="modal-title" id="modalAddStock-title">Tambah Stok</h5>
                <button type="button" class="btn btn-icon btn-close" data-bs-dismiss="modal" id="close-modal"><i class="uil uil-times fs-4 text-dark"></i></button>
            </div>
            <form method="POST" action="{{route('dashboard.product-stocks.store')}}" id="frmAddStock" autocomplete="off">
                @csrf
                <input type="hidden" name="product_id">
                <div class="modal-body">
                    <div class="form-group mb-3">
                        <label>Produk <span class="text-danger">*</span></label>
                        <input type="text" class="form-control product-name" placeholder="Produk" readonly disabled>
                    </div>
                    <div class="form-group mb-3">
                        <label>Jenis Inventaris<span class="text-danger">*</span></label>
                        <select class="form-control select2" name="type">
                            <option value="">==Pilih Jenis Inventaris==</option>
                            @foreach($type as $index => $row)
                            <option value="{{$index}}">{{$row}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group mb-3">
                        <label>Tanggal <span class="text-danger">*</span></label>
                        <input type="date" class="form-control" name="date" placeholder="Tanggal" value="{{old('date')}}">
                    </div>
                    <div class="form-group mb-3">
                        <label>Qty <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="qty" placeholder="Qty" value="{{old('qty')}}" >
                    </div>
                    <div class="form-group mb-3">
                        <label>Catatan</label>
                        <textarea class="form-control" name="note" rows="5" placeholder="Catatan">{{old('note')}}</textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Tutup</button>
                    <button type="submit" class="btn btn-primary btn-sm">Submit</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="modalUpdate" aria-labelledby="modalUpdate-title" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content rounded shadow border-0">
            <div class="modal-header border-bottom">
                <h5 class="modal-title" id="modalUpdate-title">Edit Stock</h5>
                <button type="button" class="btn btn-icon btn-close" data-bs-dismiss="modal" id="close-modal"><i class="uil uil-times fs-4 text-dark"></i></button>
            </div>
            <form method="POST" action="#" id="frmUpdate" autocomplete="off">
                @csrf
                @method("PUT")
                <div class="modal-body">
                    <div class="form-group mb-3">
                        <label>Jenis Inventaris<span class="text-danger">*</span></i></label>
                        <select class="form-control select2" name="type">
                            <option value="">==Pilih Jenis Inventaris==</option>
                            @foreach($type as $index => $row)
                            <option value="{{$index}}">{{$row}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group mb-3">
                        <label>Tanggal<span class="text-danger">*</span></i></label>
                        <input type="date" class="form-control" placeholder="Tanggal" name="date">
                    </div>
                    <div class="form-group mb-3">
                        <label>Qty<span class="text-danger">*</span></i></label>
                        <input type="text" class="form-control" placeholder="Qty" name="qty">
                    </div>
                    <div class="form-group mb-3">
                        <label>Catatan</label>
                        <textarea name="note" class="form-control" rows="5" placeholder="Catatan"></textarea>
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