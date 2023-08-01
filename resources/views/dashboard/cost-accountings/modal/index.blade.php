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
                        <input type="text" class="form-control" placeholder="Search (Judul,Pesan)" value="{{request()->get('search')}}" name="search">
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
                    <div class="form-group mb-3">
                        <label>Jenis Akuntansi</label>
                        <select class="form-control select2" name="type" style="width:100%;">
                            <option value="">==Semua Jenis Akuntansi==</option>
                            @foreach($type as $index => $row)
                            <option value="{{$index}}">{{$row}}</option>
                            @endforeach
                        </select>
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

<div class="modal fade" id="modalExportExcel" aria-labelledby="modalExportExcel-title" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content rounded shadow border-0">
            <div class="modal-header border-bottom">
                <h5 class="modal-title" id="modalExportExcel-title">Export Excel</h5>
                <button type="button" class="btn btn-icon btn-close" data-bs-dismiss="modal" id="close-modal"><i class="uil uil-times fs-4 text-dark"></i></button>
            </div>
            <form method="get" action="" autocomplete="off" id="frmExportExcel">
                <div class="modal-body">
                    <div class="form-group mb-3">
                        <label>Search</label>
                        <input type="text" class="form-control" placeholder="Search (Nama,Deskripsi,Nominal)" value="{{request()->get('search')}}" name="search">
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
                    <div class="form-group mb-3">
                        <label>Jenis Akuntansi</label>
                        <select class="form-control select2" name="type" style="width:100%;">
                            <option value="">==Semua Jenis Akuntansi==</option>
                            @foreach($type as $index => $row)
                            <option value="{{$index}}">{{$row}}</option>
                            @endforeach
                        </select>
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

<div class="modal fade" id="modalImport" aria-labelledby="modalImport-title" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content rounded shadow border-0">
            <div class="modal-header border-bottom">
                <h5 class="modal-title" id="modalImport-title">Import Excel</h5>
                <button type="button" class="btn btn-icon btn-close" data-bs-dismiss="modal" id="close-modal"><i class="uil uil-times fs-4 text-dark"></i></button>
            </div>
            <form method="post" action="{{route('dashboard.cost-accountings.importExcel')}}" autocomplete="off" id="frmImport">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <label>File<span class="text-danger">*</span></label>
                        <input type="file" class="form-control" name="file" required/>
                        <p class="mt-2">
                            <small>
                                <i>Template import sudah disediakan. Gunakan format template berikut agar tidak eror <a href="{{URL::to('/')}}/import/cost-accountings.xlsx"><i class="fa fa-download"></i> Download</a></i>
                            </small>
                        </p>
                        <p style="margin-top: 10px;margin-bottom:0px;padding-top: 0px;padding-bottom:0px;">Kolom Yang Harus Diisi : </p>
                        <p style="margin-top: 0px;margin-bottom:0px;padding-top: 0px;padding-bottom:0px;">1. Type<span class="text-danger">*</span></p>
                        <p style="margin-top: 0px;margin-bottom:0px;padding-top: 0px;padding-bottom:0px;">2. Date<span class="text-danger">*</span></p>
                        <p style="margin-top: 0px;margin-bottom:0px;padding-top: 0px;padding-bottom:0px;">3. Name<span class="text-danger">*</span></p>
                        <p style="margin-top: 0px;margin-bottom:0px;padding-top: 0px;padding-bottom:0px;">4. Nominal<span class="text-danger">*</span></p>
                        
                        <p style="margin-top: 10px;margin-bottom:0px;padding-top: 0px;padding-bottom:0px;">Catatan Kolom Pada Excel : </p>
                        <p style="margin-top: 0px;margin-bottom:0px;padding-top: 0px;padding-bottom:0px;">1. Date = mm/dd/YYYY (Contoh : 12/31/2001)</p>
                        <p style="margin-top: 0px;margin-bottom:0px;padding-top: 0px;padding-bottom:0px;">3. Type = 1 (Pemasukan) / 2 (Pengeluaran)</p>
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