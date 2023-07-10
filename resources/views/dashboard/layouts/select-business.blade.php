<div class="modal fade" id="modalBusinessPage" tabindex="-1" aria-labelledby="modalBusinessPage-title" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-md">
        <div class="modal-content rounded shadow border-0">
            <div class="modal-header border-bottom">
                <h5 class="modal-title" id="modalBusinessPage-title">Pilih Halaman Bisnis</h5>
                <button type="button" class="btn btn-icon btn-close" data-bs-dismiss="modal" id="close-modal"><i class="uil uil-times fs-4 text-dark"></i></button>
            </div>
            <form method="PUT" action="{{route('dashboard.profile.updateBusinessPage')}}" autocomplete="off" id="frmUpdateBusinessPage">
                @csrf
                @method("PUT")
                <div class="modal-body">
                    @if(Auth::user()->hasRole([\App\Enums\RoleEnum::OWNER]))
                    <div class="form-group mb-3">
                        <label>Agen</label>
                        <select class="form-control select2 select-user-setting" name="user_id" style="width:100%;">
                            <option value="">==Semua Agen==</option>
                            @foreach(\SettingHelper::userAgen() as $index => $row)
                            <option value="{{$row->id}}" @if(!empty(Auth::user()->business_id) && Auth::user()->business->user_id == $row->id) selected @endif>{{$row->name}}</option>
                            @endforeach
                        </select>
                    </div>
                    @endif
                    <div class="form-group mb-1">
                        <label>Bisnis</label>
                        <select class="form-control select2 select-business-setting" name="business_id" style="width:100%;">
                            <option value="">==Semua Bisnis==</option>
                        </select>
                    </div>
                    <div>
                        <a href="#" class="btn-add-business-setting"><small><i>(Klik untuk menambahkan bisnis baru)</i></small></a>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Tutup</button>
                    <button type="submit" class="btn btn-primary btn-sm">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="modalStoreBusinessSetting" aria-labelledby="modalStoreBusinessSetting-title" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content rounded shadow border-0">
            <div class="modal-header border-bottom">
                <h5 class="modal-title" id="modalStoreBusinessSetting-title">Tambah Bisnis</h5>
                <button type="button" class="btn btn-icon btn-close" data-bs-dismiss="modal" id="close-modal"><i class="uil uil-times fs-4 text-dark"></i></button>
            </div>
            <form method="POST" action="{{route('dashboard.business.store')}}" id="frmStoreBusinessSetting" autocomplete="off">
                @csrf
                <div class="modal-body">
                    @if(Auth::user()->hasRole([\App\Enums\RoleEnum::OWNER]))
                    <div class="form-group mb-3">
                        <label>Agen<span class="text-danger">*</span></label>
                        <select class="form-control select2" name="user_id" >
                            <option value="">==Pilih Agen==</option>
                            @foreach (\SettingHelper::userAgen() as $index => $row)
                            <option value="{{$row->id}}" @if($row->id == old('user_id')) selected @endif>{{$row->name}} - {{$row->phone}}</option>
                            @endforeach
                        </select>
                    </div>
                    @endif
                    <div class="form-group mb-3">
                        <label>Nama Bisnis <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="name" placeholder="Nama Bisnis" value="{{old('name')}}" >
                    </div>
                    <div class="form-group mb-3">
                        <label>Kategori<span class="text-danger">*</span></label>
                        <select class="form-control select2" name="category_id" >
                            <option value="">==Pilih Kategori==</option>
                            @foreach (\SettingHelper::businessCategories() as $index => $row)
                            <option value="{{$row->id}}" @if($row->id == old('category_id')) selected @endif>{{$row->name}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group mb-3">
                        <label>Provinsi<span class="text-danger">*</span></label>
                        <select class="form-control select2 select-province-business-setting" style="width: 100%;">
                            <option value="">==Pilih Provinsi==</option>
                        </select>
                    </div>
                    <div class="form-group mb-3">
                        <label>Kota/Kabupaten<span class="text-danger">*</span></label>
                        <select class="form-control select2 select-city-business-setting" style="width: 100%;">
                            <option value="">==Pilih Kota/Kabupaten==</option>
                        </select>
                    </div>
                    <div class="form-group mb-3">
                        <label>Kecamatan<span class="text-danger">*</span></label>
                        <select class="form-control select2 select-district-business-setting" style="width: 100%;">
                            <option value="">==Pilih Kecamatan==</option>
                        </select>
                    </div>
                    <div class="form-group mb-3">
                        <label>Desa<span class="text-danger">*</span></label>
                        <select class="form-control select2 select-village-business-setting" name="village_code" style="width: 100%;">
                            <option value="">==Pilih Desa==</option>
                        </select>
                    </div>
                    <div class="form-group mb-3">
                        <label>Alamat Lengkap<span class="text-danger">*</span></label>
                        <textarea class="form-control" name="location" rows="3" placeholder="Alamat Lengkap">{{old('location')}}</textarea>
                    </div>
                    <div class="form-group mb-3">
                        <label>Deskripsi</label>
                        <textarea class="form-control" name="description" rows="3" placeholder="Deskripsi">{{old('description')}}</textarea>
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