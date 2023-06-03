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
                    <div class="form-group mb-3">
                        <label>Kategori Produk</label>
                        <select class="form-control select2 select-category" name="category_id" style="width:100%;">
                            <option value="">==Semua Kategori Produk==</option>
                        </select>
                    </div>
                    <div class="form-group mb-3">
                        <label>Unit</label>
                        <select class="form-control select2 select-unit" name="unit_id" style="width:100%;">
                            <option value="">==Semua Unit==</option>
                        </select>
                    </div>
                    <div class="form-group mb-3">
                        <label>Status</label>
                        <select class="form-control select2" name="status" style="width:100%;">
                            <option value="">==Semua Status==</option>
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