<div class="modal fade" id="modalFilter" aria-labelledby="modalFilter-title" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content rounded shadow border-0">
            <div class="modal-header border-bottom">
                <h5 class="modal-title" id="modalFilter-title">Pencarian</h5>
                <button type="button" class="btn btn-icon btn-close" data-bs-dismiss="modal" id="close-modal"><i class="uil uil-times fs-4 text-dark"></i></button>
            </div>
            <form method="get" action="" autocomplete="off">
                <div class="modal-body">
                    <div class="form-group mb-3">
                        <label>Search</label>
                        <input type="text" class="form-control" placeholder="Search (Kode Transaksi)" value="{{request()->get('search')}}" name="search">
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label>Dari Tanggal</label>
                                <input type="date" class="form-control" placeholder="Dari Tanggal" value="{{request()->get('from_date')}}" name="from_date">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label>Sampai Tanggal</label>
                                <input type="date" class="form-control" placeholder="Sampai Tanggal" value="{{request()->get('to_date')}}" name="to_date">
                            </div>
                        </div>
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

<div class="modal fade" id="modalExport" aria-labelledby="modalExport-title" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content rounded shadow border-0">
            <div class="modal-header border-bottom">
                <h5 class="modal-title" id="modalExport-title">Export <span class="export-title"></span></h5>
                <button type="button" class="btn btn-icon btn-close" data-bs-dismiss="modal" id="close-modal"><i class="uil uil-times fs-4 text-dark"></i></button>
            </div>
            <form method="get" action="#" autocomplete="off" id="frmExport">
                <div class="modal-body">
                    <div class="form-group mb-3">
                        <label>Search</label>
                        <input type="text" class="form-control" placeholder="Search (Kode Transaksi)" value="{{request()->get('search')}}" name="search">
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label>Dari Tanggal</label>
                                <input type="date" class="form-control" placeholder="Dari Tanggal" value="{{request()->get('from_date')}}" name="from_date">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label>Sampai Tanggal</label>
                                <input type="date" class="form-control" placeholder="Sampai Tanggal" value="{{request()->get('to_date')}}" name="to_date">
                            </div>
                        </div>
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