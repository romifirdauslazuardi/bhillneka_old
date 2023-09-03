@extends('dashboard.layouts.main')

@section('title', 'Pengaturan Dashboard')

@section('css')
@endsection

@section('breadcumb')
    <div class="row">
        <div class="col-sm-12">
            <h3 class="page-title">Pengaturan</h3>
            <ul class="breadcrumb">
                <li class="breadcrumb-item"><a href="#">Pengaturan</a></li>
                <li class="breadcrumb-item active">Pengaturan Landing Page</li>
            </ul>
        </div>
    </div>
@endsection

@section('content')
    <div class="row">
        <div class="col-12 mt-4">
            <div class="card border-0 rounded shadow p-4">
                <form action="{{ route('dashboard.settings.landing-page.update') }}" id="frmUpdate" autocomplete="off">
                    @csrf
                    @method('PUT')
                    <div class="row mb-3">
                        <div class="col-lg-12">
                            <div class="form-group row mb-3">
                                <label class="col-md-3 col-form-label">Nama Website <span
                                        class="text-danger">*</span></label>
                                <div class="col-md-9">
                                    <input type="text" class="form-control" name="title" placeholder="Nama Website"
                                        value="{{ old('title', $result->title) }}">
                                </div>
                            </div>
                            <div class="form-group row mb-3">
                                <label class="col-md-3 col-form-label">Deskripsi <span class="text-danger">*</span></label>
                                <div class="col-md-9">
                                    <textarea class="form-control" placeholder="Deskripsi" name="description">{{ $result->description }}</textarea>
                                </div>
                            </div>
                            <div class="form-group row mb-3">
                                <label class="col-md-3 col-form-label">Kata Kunci <span class="text-danger">*</span></label>
                                <div class="col-md-9">
                                    <input type="text" class="form-control" name="keyword" placeholder="Kata Kunci"
                                        value="{{ old('keyword', $result->keyword) }}">
                                </div>
                            </div>
                            <div class="form-group row mb-3">
                                <label class="col-md-3 col-form-label">Email <span class="text-danger">*</span></label>
                                <div class="col-md-9">
                                    <input type="email" class="form-control" name="email" placeholder="Email"
                                        value="{{ old('email', $result->email) }}">
                                </div>
                            </div>
                            <div class="form-group row mb-3">
                                <label class="col-md-3 col-form-label">Phone <span class="text-danger">*</span></label>
                                <div class="col-md-9">
                                    <input type="text" class="form-control" name="phone" placeholder="Phone"
                                        value="{{ old('phone', $result->phone) }}">
                                </div>
                            </div>
                            <div class="form-group row mb-3">
                                <label class="col-md-3 col-form-label">Lokasi <span class="text-danger">*</span></label>
                                <div class="col-md-9">
                                    <textarea class="form-control" placeholder="Lokasi" name="location">{{ $result->location }}</textarea>
                                </div>
                            </div>
                            <div class="form-group row mb-3">
                                <label class="col-md-3 col-form-label">Instagram</label>
                                <div class="col-md-9">
                                    <input type="text" class="form-control" name="instagram" placeholder="Instagram"
                                        value="{{ old('instagram', $result->instagram) }}">
                                </div>
                            </div>
                            <div class="form-group row mb-3">
                                <label class="col-md-3 col-form-label">Facebook</label>
                                <div class="col-md-9">
                                    <input type="text" class="form-control" name="facebook" placeholder="Facebook"
                                        value="{{ old('facebook', $result->facebook) }}">
                                </div>
                            </div>
                            <div class="form-group row mb-3">
                                <label class="col-md-3 col-form-label">Twitter</label>
                                <div class="col-md-9">
                                    <input type="text" class="form-control" name="twitter" placeholder="Twitter"
                                        value="{{ old('twitter', $result->twitter) }}">
                                </div>
                            </div>
                            <div class="form-group row mb-3">
                                <label class="col-md-3 col-form-label">Logo (Light)</label>
                                <div class="col-md-9">
                                    @if (!empty($result->logo))
                                        <div class="mb-2">
                                            <img src="{{ asset($result->logo) }}" style="width: 100px;height: 100px;">
                                        </div>
                                    @endif
                                    <input type="file" class="form-control" name="logo">
                                    <p class="text-success"
                                        style="margin-top: 0px;margin-bottom: 0px;padding-top: 0px;padding-bottom: 0px;">
                                        <small><i>Ukuran direkomendasikan 134px x 24px</i></small></p>
                                    <p class="text-info"
                                        style="margin-top: 0px;margin-bottom: 0px;padding-top: 0px;padding-bottom: 0px;">
                                        <small><i>Kosongkan jika tidak diubah</i></small></p>
                                </div>
                            </div>
                            <div class="form-group row mb-3">
                                <label class="col-md-3 col-form-label">Logo (Dark)</label>
                                <div class="col-md-9">
                                    @if (!empty($result->logo_dark))
                                        <div class="mb-2">
                                            <img src="{{ asset($result->logo_dark) }}"
                                                style="width: 100px;height: 100px;">
                                        </div>
                                    @endif
                                    <input type="file" class="form-control" name="logo_dark">
                                    <p class="text-success"
                                        style="margin-top: 0px;margin-bottom: 0px;padding-top: 0px;padding-bottom: 0px;">
                                        <small><i>Ukuran direkomendasikan 134px x 24px</i></small></p>
                                    <p class="text-info"
                                        style="margin-top: 0px;margin-bottom: 0px;padding-top: 0px;padding-bottom: 0px;">
                                        <small><i>Kosongkan jika tidak diubah</i></small></p>
                                </div>
                            </div>
                            <div class="form-group row mb-3">
                                <label class="col-md-3 col-form-label">Favicon</label>
                                <div class="col-md-9">
                                    @if (!empty($result->favicon))
                                        <div class="mb-2">
                                            <img src="{{ asset($result->favicon) }}" style="width: 100px;height: 100px;">
                                        </div>
                                    @endif
                                    <input type="file" class="form-control" name="favicon">
                                    <p class="text-success"
                                        style="margin-top: 0px;margin-bottom: 0px;padding-top: 0px;padding-bottom: 0px;">
                                        <small><i>Ukuran direkomendasikan 64px x 64px</i></small></p>
                                    <p class="text-info"
                                        style="margin-top: 0px;margin-bottom: 0px;padding-top: 0px;padding-bottom: 0px;">
                                        <small><i>Kosongkan jika tidak diubah</i></small></p>
                                </div>
                            </div>
                            <div class="form-group row mb-3">
                                <label class="col-md-3 col-form-label">Footer <span class="text-danger">*</span></label>
                                <div class="col-md-9">
                                    <input type="text" class="form-control" name="footer" placeholder="Footer"
                                        value="{{ old('footer', $result->footer) }}">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-12">
                            <a href="{{ route('dashboard.index') }}" class="btn btn-warning btn-sm"><i
                                    class="fa fa-arrow-left"></i> Kembali</a>
                            <button type="submit" class="btn btn-primary btn-sm" disabled><i class="fa fa-save"></i>
                                Simpan</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @include('dashboard.components.loader')

@endsection

@section('script')
    <script>
        $(function() {

            $('button[type="submit"]').attr("disabled", false);

            $(document).on('submit', '#frmUpdate', function(e) {
                e.preventDefault();
                if (confirm("Apakah anda yakin ingin menyimpan data ini ?")) {
                    $.ajax({
                        url: $("#frmUpdate").attr("action"),
                        method: "POST",
                        data: new FormData($('#frmUpdate')[0]),
                        contentType: false,
                        cache: false,
                        processData: false,
                        dataType: "JSON",
                        beforeSend: function() {
                            return openLoader();
                        },
                        success: function(resp) {
                            if (resp.success == false) {
                                responseFailed(resp.message);
                            } else {
                                responseSuccess(resp.message,
                                    "{{ route('dashboard.settings.landing-page.index') }}");
                            }
                        },
                        error: function(request, status, error) {
                            if (request.status == 422) {
                                responseFailed(request.responseJSON.message);
                            } else {
                                responseInternalServerError();
                            }
                        },
                        complete: function() {
                            return closeLoader();
                        }
                    })
                }
            })
        })
    </script>
@endsection
