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
                        <input type="text" class="form-control" placeholder="Search (Kode Produk , Nama Produk , Harga Produk)" value="{{request()->get('search')}}" name="search">
                    </div>
                    @if(Auth::user()->hasRole([\App\Enums\RoleEnum::OWNER]))
                        @if(empty(Auth::user()->business_id))
                        <div class="form-group mb-3">
                            <label>Pengguna</label>
                            <select class="form-control select2 select-user" name="user_id" style="width:100%;">
                                <option value="">==Semua Pengguna==</option>
                                @foreach($users as $index => $row)
                                <option value="{{$row->id}}">{{$row->name}}</option>
                                @endforeach
                            </select>
                        </div>
                        @endif
                    @endif
                    @if(empty(Auth::user()->business_id))
                    <div class="form-group mb-3">
                        <label>Bisnis</label>
                        <select class="form-control select2 select-business" name="business_id" style="width:100%;">
                            <option value="">==Semua Bisnis==</option>
                        </select>
                    </div>
                    @endif
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Tutup</button>
                    <button type="submit" class="btn btn-primary btn-sm">Submit</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="modalExportExcel" aria-labelledby="modalExportExcel-title" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content rounded shadow border-0">
            <div class="modal-header border-bottom">
                <h5 class="modal-title" id="modalExportExcel-title">Export Excel</h5>
                <button type="button" class="btn btn-icon btn-close" data-bs-dismiss="modal" id="close-modal"><i class="uil uil-times fs-4 text-dark"></i></button>
            </div>
            <form method="get" action="{{route('dashboard.product-stocks.exportExcel')}}" autocomplete="off" id="frmExportExcel">
                <div class="modal-body">
                    <div class="form-group mb-3">
                        <label>Search</label>
                        <input type="text" class="form-control" placeholder="Search (Kode Produk , Nama Produk , Harga Produk)" value="{{request()->get('search')}}" name="search">
                    </div>
                    @if(Auth::user()->hasRole([\App\Enums\RoleEnum::OWNER]))
                        @if(empty(Auth::user()->business_id))
                        <div class="form-group mb-3">
                            <label>Pengguna</label>
                            <select class="form-control select2 select-user" name="user_id" style="width:100%;">
                                <option value="">==Semua Pengguna==</option>
                                @foreach($users as $index => $row)
                                <option value="{{$row->id}}">{{$row->name}}</option>
                                @endforeach
                            </select>
                        </div>
                        @endif
                    @endif
                    @if(empty(Auth::user()->business_id))
                    <div class="form-group mb-3">
                        <label>Bisnis</label>
                        <select class="form-control select2 select-business" name="business_id" style="width:100%;">
                            <option value="">==Semua Bisnis==</option>
                        </select>
                    </div>
                    @endif
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Tutup</button>
                    <button type="submit" class="btn btn-primary btn-sm">Submit</button>
                </div>
            </form>
        </div>
    </div>
</div>