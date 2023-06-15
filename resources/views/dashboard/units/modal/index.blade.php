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
                        <input type="text" class="form-control" placeholder="Search (Nama Unit)" value="{{request()->get('search')}}" name="search">
                    </div>
                    @if(Auth::user()->hasRole([\App\Enums\RoleEnum::OWNER]))
                    <div class="form-group mb-3">
                        <label>Pengguna</label>
                        <select class="form-control select2" name="user_id" style="width:100%;">
                            <option value="">==Semua Pengguna==</option>
                            @foreach($users as $index => $row)
                            <option value="{{$row->id}}">{{$row->name}}</option>
                            @endforeach
                        </select>
                    </div>
                    @endif
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                    <button type="submit" class="btn btn-primary">Submit</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="modalStore" aria-labelledby="modalStore-title" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content rounded shadow border-0">
            <div class="modal-header border-bottom">
                <h5 class="modal-title" id="modalStore-title">Tambah Unit</h5>
                <button type="button" class="btn btn-icon btn-close" data-bs-dismiss="modal" id="close-modal"><i class="uil uil-times fs-4 text-dark"></i></button>
            </div>
            <form method="POST" action="{{route('dashboard.units.store')}}" id="frmStore" autocomplete="off">
                @csrf
                <div class="modal-body">
                    @if(Auth::user()->hasRole([\App\Enums\RoleEnum::OWNER]))
                    <div class="form-group mb-3">
                        <label>Pengguna <span class="text-danger">*</span></i></label>
                        <select class="form-control select2" name="user_id" style="width:100%;">
                            <option value="">==Semua Pengguna==</option>
                            @foreach($users as $index => $row)
                            <option value="{{$row->id}}">{{$row->name}}</option>
                            @endforeach
                        </select>
                    </div>
                    @endif
                    <div class="form-group mb-3">
                        <label>Nama Unit <span class="text-danger">*</span></i></label>
                        <input type="text" class="form-control" placeholder="Nama Unit" name="name">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                    <button type="submit" class="btn btn-primary" disabled>Submit</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="modalUpdate" aria-labelledby="modalUpdate-title" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content rounded shadow border-0">
            <div class="modal-header border-bottom">
                <h5 class="modal-title" id="modalUpdate-title">Edit Unit</h5>
                <button type="button" class="btn btn-icon btn-close" data-bs-dismiss="modal" id="close-modal"><i class="uil uil-times fs-4 text-dark"></i></button>
            </div>
            <form method="POST" id="frmUpdate" autocomplete="off">
                @csrf
                @method("PUT")
                <div class="modal-body">
                    @if(Auth::user()->hasRole([\App\Enums\RoleEnum::OWNER]))
                    <div class="form-group mb-3">
                        <label>Pengguna <span class="text-danger">*</span></i></label>
                        <select class="form-control select2" name="user_id" style="width:100%;">
                            <option value="">==Semua Pengguna==</option>
                            @foreach($users as $index => $row)
                            <option value="{{$row->id}}">{{$row->name}}</option>
                            @endforeach
                        </select>
                    </div>
                    @endif
                    <div class="form-group mb-3">
                        <label>Nama Unit <span class="text-danger">*</span></i></label>
                        <input type="text" class="form-control" placeholder="Nama Unit" name="name">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                    <button type="submit" class="btn btn-primary" disabled>Submit</button>
                </div>
            </form>
        </div>
    </div>
</div>