@extends('dashboard.layouts.main')

@section('title', 'Halaman')

@section('css')
@endsection

@section('breadcumb')
    <div class="row">
        <div class="col-sm-12">
            <h3 class="page-title">Halaman</h3>
            <ul class="breadcrumb">
                <li class="breadcrumb-item"><a href="#">Halaman</a></li>
                <li class="breadcrumb-item active">Halaman Edit</li>
            </ul>
        </div>
    </div>
@endsection

@section('content')
    @trixassets
    <div class="row">
        <div class="col-12 mt-4">
            <div class="card border-0 rounded shadow p-4">
                <form
                    action="{{ route(Auth::user()->hasRole([\App\Enums\RoleEnum::OWNER]) ? 'dashboard.landing-page.pages.update' : 'dashboard.landing-page-agen.pages.update', $result->id) }}"
                    id="frmUpdate" autocomplete="off">
                    @csrf
                    @method('PUT')
                    <div class="row mb-3">
                        <div class="col-lg-12">
                            <div class="form-group row mb-3">
                                <label class="col-md-3 col-form-label">Nama Halaman <span
                                        class="text-danger">*</span></label>
                                <div class="col-md-9">
                                    <input type="text" class="form-control" name="name" placeholder="Nama Halaman"
                                        value="{{ old('name', $result->name) }}">
                                </div>
                            </div>
                            <div class="form-group row mb-3">
                                <label class="col-md-3 col-form-label">Konten <span class="text-danger">*</span></label>
                                <div class="col-md-9">
                                    @trix($result, 'content')
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-12">
                            <a href="{{ route(Auth::user()->hasRole([\App\Enums\RoleEnum::OWNER]) ? 'dashboard.landing-page.pages.index' : 'dashboard.landing-page-agen.pages.index') }}"
                                class="btn btn-warning btn-sm"><i class="fa fa-arrow-left"></i> Kembali</a>
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
                                    "{{ route(Auth::user()->hasRole([\App\Enums\RoleEnum::OWNER]) ? 'dashboard.landing-page.pages.index' : 'dashboard.landing-page-agen.pages.index') }}"
                                    );
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
