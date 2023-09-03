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
                <li class="breadcrumb-item active">List</li>
            </ul>
        </div>
    </div>
@endsection

@section('content')
    <div class="row">
        <div class="col-12 mt-4">
            <div class="card border-0 rounded shadow p-4">
                <div class="row mb-3">
                    <div class="col-lg-12">
                        <a href="{{ route(Auth::user()->hasRole([\App\Enums\RoleEnum::OWNER]) ? 'dashboard.landing-page.pages.create' : 'dashboard.landing-page-agen.pages.create') }}"
                            class="btn btn-primary btn-sm btn-add"><i class="fa fa-plus"></i> Tambah</a>
                        <a href="#" class="btn btn-success btn-sm btn-filter"><i class="fa fa-filter"></i> Filter</a>
                        <a href="{{ route(Auth::user()->hasRole([\App\Enums\RoleEnum::OWNER]) ? 'dashboard.landing-page.pages.index' : 'dashboard.landing-page-agen.pages.index') }}"
                            class="btn @if (!empty(request()->all())) btn-warning @else btn-secondary @endif btn-sm"><i
                                class="fa fa-refresh"></i> Refresh</a>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-12">
                        <div class="table-responsive">
                            <div class="table">
                                <table class="table table-striped table-bordered">
                                    <thead>
                                        <th>Aksi</th>
                                        <th>No</th>
                                        <th>Nama Halaman</th>
                                        <th>Slug</th>
                                        @role(\App\Enums\RoleEnum::AGEN)
                                            <th>Business Name</th>
                                        @endrole
                                        <th>Dibuat Pada</th>
                                    </thead>
                                    <tbody>
                                        @forelse ($table as $index => $row)
                                            <tr>
                                                <td>
                                                    <div class="dropdown-primary me-2 mt-2">
                                                        <button type="button" class="btn btn-primary btn-sm"
                                                            data-bs-toggle="dropdown" aria-haspopup="true"
                                                            aria-expanded="false">
                                                            <i class="fa fa-bars"></i>
                                                        </button>
                                                        <div class="dropdown-menu">
                                                            <a href="{{ route(Auth::user()->hasRole([\App\Enums\RoleEnum::OWNER]) ? 'dashboard.landing-page.pages.show' : 'dashboard.landing-page-agen.pages.show', $row->id) }}"
                                                                class="dropdown-item"><i class="fa fa-eye"></i> Show</a>
                                                            <a href="{{ route(Auth::user()->hasRole([\App\Enums\RoleEnum::OWNER]) ? 'dashboard.landing-page.pages.edit' : 'dashboard.landing-page-agen.pages.edit', $row->id) }}"
                                                                class="dropdown-item"><i class="fa fa-edit"></i> Edit</a>
                                                            <a href="#" class="dropdown-item btn-delete"
                                                                data-id="{{ $row->id }}"><i class="fa fa-trash"></i>
                                                                Hapus</a>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>{{ $table->firstItem() + $index }}</td>
                                                <td>{{ $row->name }}</td>
                                                <td>{{ $row->slug }}</td>
                                                @role(\App\Enums\RoleEnum::AGEN)
                                                    <td>{{ $row->business?->name }}</td>
                                                @endrole
                                                <td>{{ date('d-m-Y H:i:s', strtotime($row->created_at)) }}</td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="10" class="text-center">Data tidak ditemukan</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        {!! $table->links() !!}
                    </div>
                </div>
            </div>
        </div>
    </div>

    @include('dashboard.landing-page.pages.modal.index')
    @include('dashboard.components.loader')

    <form id="frmDelete" method="POST">
        @csrf
        @method('DELETE')
        <input type="hidden" name="id" />
    </form>

@endsection

@section('script')
    <script>
        $(function() {
            $(document).on("click", ".btn-filter", function(e) {
                e.preventDefault();

                $("#modalFilter").modal("show");
            });

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
