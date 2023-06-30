<div class="modal fade" id="modalBusinessPage" tabindex="-1" aria-labelledby="modalBusinessPage-title" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
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
                        <label>Pengguna</label>
                        <select class="form-control select2 select-user-setting" name="user_id" style="width:100%;">
                            <option value="">==Semua Pengguna==</option>
                            @foreach(\SettingHelper::userAgen() as $index => $row)
                            <option value="{{$row->id}}" @if(!empty(Auth::user()->business_id) && Auth::user()->business->user_id == $row->id) selected @endif>{{$row->name}}</option>
                            @endforeach
                        </select>
                    </div>
                    @endif
                    <div class="form-group mb-3">
                        <label>Bisnis</label>
                        <select class="form-control select2 select-business-setting" name="business_id" style="width:100%;">
                            <option value="">==Semua Bisnis==</option>
                        </select>
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