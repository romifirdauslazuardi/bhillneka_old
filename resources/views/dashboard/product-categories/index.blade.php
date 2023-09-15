@extends('dashboard.layouts.main')

@section('title', 'Product Categories')

@section('css')
@endsection

@section('breadcumb')
    <div class="row">
        <div class="col-sm-12">
            <h3 class="page-title">Produk Kategori</h3>
            <ul class="breadcrumb">
                <li class="breadcrumb-item"><a href="#">Produk Kategori</a></li>
                <li class="breadcrumb-item active">Daftar Produk Kategori</li>
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
                        <a href="{{ route('dashboard.product-categories.create') }}" class="btn btn-success btn-sm"><span><i
                                    data-feather="plus" style="width: 20px;height: 19px;"></i>Tambah</span></a>
                        <a href="{{ route('dashboard.product-categories.index') }}"
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
                                        <th>Nama</th>
                                        <th>Image</th>
                                        <th>Dibuat Pada</th>
                                    </thead>
                                    <tbody>
                                        @forelse ($data as $index => $row)
                                            <tr>
                                                <td>
                                                    <div class="dropdown-primary me-2 mt-2">
                                                        <button type="button" class="btn btn-primary btn-sm"
                                                            data-bs-toggle="dropdown" aria-haspopup="true"
                                                            aria-expanded="false">
                                                            <i class="fa fa-bars"></i>
                                                        </button>
                                                        <div class="dropdown-menu">
                                                            <a href="{{ route('dashboard.product-categories.show', $row->id) }}"
                                                                class="dropdown-item"><i class="fa fa-eye"></i> Show</a>
                                                            @if (!empty(Auth::user()->business_id))
                                                                <a href="{{ route('dashboard.product-categories.edit', $row->id) }}"
                                                                    class="dropdown-item"><i class="fa fa-edit"></i>
                                                                    Edit</a>
                                                                <a href="#" class="dropdown-item btn-delete"
                                                                    data-id="{{ $row->id }}"><i
                                                                        class="fa fa-trash"></i> Hapus</a>
                                                            @endif
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>{{ $data->firstItem() + $index }}</td>
                                                <td>{{ $row->name }}</td>
                                                <td>
                                                    <img src="{{ $row->image() }}" class="img-fluid" width="200px"
                                                            alt="{{ $row->name }}" onerror="this.src='{{ asset('assets/placeholder-image.webp') }}'">
                                                </td>
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
                        {!! $data->links() !!}
                    </div>
                </div>
            </div>
        </div>
    </div>

    @include('dashboard.product-categories.modal.index')
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

            $('button[type="submit"]').attr("disabled", false);

            $(document).on("click", ".btn-filter", function(e) {
                e.preventDefault();

                $("#modalFilter").modal("show");
            });
            $(document).on("click", ".btn-delete", function() {
                let id = $(this).data("id");
                if (confirm("Apakah anda yakin ingin menghapus data ini ?")) {
                    $("#frmDelete").attr("action",
                        "{{ route('dashboard.product-categories.destroy', '_id_') }}"
                        .replace("_id_", id));
                    $("#frmDelete").find('input[name="id"]').val(id);
                    $("#frmDelete").submit();
                }
            })
        })
    </script>
@endsection
