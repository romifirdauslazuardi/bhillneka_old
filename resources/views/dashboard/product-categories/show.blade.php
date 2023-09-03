@extends('dashboard.layouts.main')

@section('title', 'Kategori Produk')

@section('css')
    <!-- Datatables -->
    <link href="{{ URL::to('/') }}/templates/dashboard/assets/libs/datatables/dataTables.bootstrap4.min.css" rel="stylesheet"
        type="text/css" />
    <link href="{{ URL::to('/') }}/templates/dashboard/assets/libs/datatables/buttons.bootstrap4.min.css" rel="stylesheet"
        type="text/css" />
    <!-- Responsive datatable examples -->
    <link href="{{ URL::to('/') }}/templates/dashboard/assets/libs/datatables/responsive.bootstrap4.min.css" rel="stylesheet"
        type="text/css" />
@endsection

@section('breadcumb')
    <div class="row">
        <div class="col-sm-12">
            <h3 class="page-title">Kategori Produk</h3>
            <ul class="breadcrumb">
                <li class="breadcrumb-item"><a href="#">Kategori Produk</a></li>
                <li class="breadcrumb-item active">Show Kategori Produk</li>
            </ul>
        </div>
    </div>
@endsection

@section('content')
    <div class="row pb-2">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title mb-3">Informasi Data Kategori Produk</h5>
                    <ul class="nav nav-tabs">
                        <li class="nav-item">
                            <a class="nav-link active" data-bs-toggle="tab" href="#product">Data Kategori Produk</a>
                        </li>
                    </ul>
                    <!-- Tab panes -->
                    <div class="tab-content pt-3">
                        <div class="tab-pane container active" id="product">
                            <div class="row">
                                <div class="col-12">
                                    <div class="row mb-2">
                                        <div class="col-md-3">
                                            Nama Kategori
                                        </div>
                                        <div class="col-md-8">
                                            : {{ $result->name }}
                                        </div>
                                    </div>

                                    <div class="row mb-2">
                                        <div class="col-md-3">
                                            Image/Thumbnail
                                        </div>
                                        <div class="col-md-8">
                                            @if ($result->image)
                                                : <img src="{{ asset($result->image) }}" alt="{{ $result->name }}" style="max-height: 100px">
                                            @else
                                                : <span>Gambar Kosong</span>
                                            @endif
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
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <div class="d-flex justify-content-start mt-3">
                                <a href="{{ route('dashboard.product-categories.index') }}" class="btn btn-warning btn-sm"
                                    style="margin-right: 10px;"><i class="fa fa-arrow-left"></i> Kembali</a>
                                @if (!empty($result->business_id))
                                    <a href="{{ route('dashboard.product-categories.edit', $result->id) }}"
                                        class="btn btn-primary btn-sm" style="margin-right: 10px;"><i
                                            class="fa fa-edit"></i> Edit</a>
                                    <a href="#" class="btn btn-danger btn-sm btn-delete"
                                        data-id="{{ $result->id }}"><i class="fa fa-trash"></i> Hapus</a>
                                @endif
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
    <!-- Datatables -->
    <script src="{{ URL::to('/') }}/templates/dashboard/assets/libs/parsleyjs/parsley.min.js"></script>
    <script src="{{ URL::to('/') }}/templates/dashboard/assets/libs/datatables/jquery.dataTables.min.js"></script>
    <script src="{{ URL::to('/') }}/templates/dashboard/assets/libs/datatables/dataTables.bootstrap4.min.js"></script>
    <script src="{{ URL::to('/') }}/assets/pages/datatables.init.js"></script>
    <script src="{{ URL::to('/') }}/templates/dashboard/assets/libs/datatables/dataTables.responsive.min.js"></script>
    <script src="{{ URL::to('/') }}/templates/dashboard/assets/libs/datatables/responsive.bootstrap4.min.js"></script>
    <script>
        $(function() {
            $('.datatables').DataTable();

            $(document).on("click", ".btn-add-stock", function(e) {
                e.preventDefault();

                $('#modalStoreStock').modal("show");
            });

            $(document).on("click", ".btn-delete", function(e) {
                e.preventDefault();
                let id = $(this).data("id");
                if (confirm("Apakah anda yakin ingin menghapus data ini ?")) {
                    $("#frmDelete").attr("action", "{{ route('dashboard.product-categories.destroy', '_id_') }}"
                        .replace("_id_", id));
                    $("#frmDelete").find('input[name="id"]').val(id);
                    $("#frmDelete").submit();
                }
            })

        })
    </script>
@endsection
