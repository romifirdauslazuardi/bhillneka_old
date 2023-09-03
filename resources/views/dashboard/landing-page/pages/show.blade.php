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
    <div class="row pb-2">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title mb-3">Informasi Data Halaman</h5>
                    <div class="row">
                        <div class="col-12">

                            <div class="row mb-2">
                                <div class="col-md-3">
                                    Nama Halaman
                                </div>
                                <div class="col-md-8">
                                    : {{ $result->name }}
                                </div>
                            </div>

                            <div class="row mb-2">
                                <div class="col-md-3">
                                    Slug
                                </div>
                                <div class="col-md-8">
                                    : {{ $result->slug }}
                                </div>
                            </div>

                            <div class="row mb-2">
                                <div class="col-md-3">
                                    Konten
                                </div>
                                <div class="col-md-8">
                                    : {!! $result->trixRender('content') !!}
                                </div>
                            </div>

                            <div class="row mb-2">
                                <div class="col-md-3">
                                    Author
                                </div>
                                <div class="col-md-8">
                                    : {{ $result->author->name ?? null }}
                                </div>
                            </div>

                            <div class="row mb-2">
                                <div class="col-md-3">
                                    Tanggal Dibuat
                                </div>
                                <div class="col-md-8">
                                    : {{ date('d-m-Y H:i:s', strtotime($result->created_at)) }}
                                </div>
                            </div>

                            <div class="row mb-2">
                                <div class="col-md-3">
                                    Tanggal Diperbarui
                                </div>
                                <div class="col-md-8">
                                    : {{ date('d-m-Y H:i:s', strtotime($result->updated_at)) }}
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <div class="d-flex justify-content-start mt-3">
                                <a href="{{ route(Auth::user()->hasRole([\App\Enums\RoleEnum::OWNER]) ? 'dashboard.landing-page.pages.index' : 'dashboard.landing-page-agen.pages.index') }}"
                                    class="btn btn-warning btn-sm" style="margin-right: 10px;"><i
                                        class="fa fa-arrow-left"></i> Kembali</a>
                                <a href="{{ route(Auth::user()->hasRole([\App\Enums\RoleEnum::OWNER]) ? 'dashboard.landing-page.pages.edit' : 'dashboard.landing-page-agen.pages.edit', $result->id) }}"
                                    class="btn btn-primary btn-sm" style="margin-right: 10px;"><i class="fa fa-edit"></i>
                                    Edit</a>
                                <a href="#" class="btn btn-danger btn-sm btn-delete" data-id="{{ $result->id }}"><i
                                        class="fa fa-trash"></i> Hapus</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <form id="frmDelete" method="POST">
        @csrf
        @method('DELETE')
        <input type="hidden" name="id" />
    </form>

    @include('dashboard.components.loader')

@endsection

@section('script')
    <script>
        $(function() {
            $(document).on("click", ".btn-delete", function() {
                let id = $(this).data("id");
                if (confirm("Apakah anda yakin ingin menghapus data ini ?")) {
                    $("#frmDelete").attr("action",
                        "{{ route(Auth::user()->hasRole([\App\Enums\RoleEnum::OWNER]) ? 'dashboard.landing-page.pages.destroy' : 'dashboard.landing-page-agen.pages.destroy', '_id_') }}"
                        .replace("_id_",
                            id));
                    $("#frmDelete").find('input[name="id"]').val(id);
                    $("#frmDelete").submit();
                }
            })
        })
    </script>
@endsection
