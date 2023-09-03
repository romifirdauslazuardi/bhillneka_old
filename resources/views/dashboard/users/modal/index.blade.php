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
                        <input type="text" class="form-control" placeholder="Search (Nama,Email,Phone)" value="{{request()->get('search')}}" name="search">
                    </div>
                    @if(Auth::user()->hasRole([\App\Enums\RoleEnum::OWNER,\App\Enums\RoleEnum::AGEN]))
                    <div class="form-group mb-3">
                        <label>Role</label>
                        <select class="form-control select2" name="role" style="width:100%;">
                            <option value="">==Semua Role==</option>
                            @foreach($roles as $index => $row)
                            <option value="{{$row}}">{{$row}}</option>
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