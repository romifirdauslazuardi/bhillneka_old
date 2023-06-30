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
                    @if(Auth::user()->hasRole([\App\Enums\RoleEnum::OWNER]))
                    <div class="form-group mb-3">
                        <label>Pengguna</label>
                        <select class="form-control select2 select-user" name="user_id" style="width:100%;">
                            <option value="">==Semua Pengguna==</option>
                            @foreach($users as $index => $row)
                            <option value="{{$row->id}}">{{$row->name}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group mb-3">
                        <label>Provider</label>
                        <select class="form-control select2" name="provider_d" style="width:100%;">
                            <option value="">==Semua Provider==</option>
                            @foreach($providers as $index => $row)
                            <option value="{{$row->id}}">{{$row->name}}</option>
                            @endforeach
                        </select>
                    </div>
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
                        <label>Progress Pengerjaan</label>
                        <select class="form-control select2" name="progress" style="width:100%;">
                            <option value="">==Semua Progress==</option>
                            @foreach($progress as $index => $row)
                            <option value="{{$index}}">{{$row}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group mb-3">
                        <label>Status Pembayaran</label>
                        <select class="form-control select2" name="status" style="width:100%;">
                            <option value="">==Semua Status==</option>
                            @foreach($status as $index => $row)
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
                    @if(Auth::user()->hasRole([\App\Enums\RoleEnum::OWNER]))
                    <div class="form-group mb-3">
                        <label>Pengguna</label>
                        <select class="form-control select2 select-user" name="user_id" style="width:100%;">
                            <option value="">==Semua Pengguna==</option>
                            @foreach($users as $index => $row)
                            <option value="{{$row->id}}">{{$row->name}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group mb-3">
                        <label>Provider</label>
                        <select class="form-control select2" name="provider_d" style="width:100%;">
                            <option value="">==Semua Provider==</option>
                            @foreach($providers as $index => $row)
                            <option value="{{$row->id}}">{{$row->name}}</option>
                            @endforeach
                        </select>
                    </div>
                    @endif
                    <div class="form-group mb-3">
                        <label>Progress Pengerjaan</label>
                        <select class="form-control select2" name="progress" style="width:100%;">
                            <option value="">==Semua Progress==</option>
                            @foreach($progress as $index => $row)
                            <option value="{{$index}}">{{$row}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group mb-3">
                        <label>Status Pembayaran</label>
                        <select class="form-control select2" name="status" style="width:100%;">
                            <option value="">==Semua Status==</option>
                            @foreach($status as $index => $row)
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

<div class="modal fade" id="modalUpdateProgress" aria-labelledby="modalUpdateProgress-title" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content rounded shadow border-0">
            <div class="modal-header border-bottom">
                <h5 class="modal-title" id="modalUpdateProgress-title">Update Progress</h5>
                <button type="button" class="btn btn-icon btn-close" data-bs-dismiss="modal" id="close-modal"><i class="uil uil-times fs-4 text-dark"></i></button>
            </div>
            <form method="POST" action="" autocomplete="off" id="frmUpdateProgress">
                @csrf
                @method("PUT")
                <div class="modal-body">
                    <div class="form-group mb-3">
                        <label>Progress Pengerjaan</label>
                        <select class="form-control select2" name="progress" style="width:100%;">
                            <option value="">==Pilih Progress==</option>
                            @foreach($progress as $index => $row)
                            <option value="{{$index}}">{{$row}}</option>
                            @endforeach
                        </select>
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

<div class="modal fade" id="modalUpdateStatus" aria-labelledby="modalUpdateStatus-title" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content rounded shadow border-0">
            <div class="modal-header border-bottom">
                <h5 class="modal-title" id="modalUpdateStatus-title">Update Status Pembayaran</h5>
                <button type="button" class="btn btn-icon btn-close" data-bs-dismiss="modal" id="close-modal"><i class="uil uil-times fs-4 text-dark"></i></button>
            </div>
            <form method="POST" action="" autocomplete="off" id="frmUpdateStatus">
                @csrf
                @method("PUT")
                <div class="modal-body">
                    <div class="form-group mb-3">
                        <label>Status Pembayaran</label>
                        <select class="form-control select2" name="status" style="width:100%;">
                            <option value="">==Pilih Status Pembayaran==</option>
                            @foreach($status as $index => $row)
                            <option value="{{$index}}">{{$row}}</option>
                            @endforeach
                        </select>
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