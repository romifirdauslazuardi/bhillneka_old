@extends("dashboard.layouts.main")

@section("title","Transaksi")

@section("css")
@endsection

@section("breadcumb")
<div class="d-md-flex justify-content-between align-items-center">
    <h5 class="mb-0">Transaksi</h5>

    <nav aria-label="breadcrumb" class="d-inline-block mt-2 mt-sm-0">
        <ul class="breadcrumb bg-transparent rounded mb-0 p-0">
            <li class="breadcrumb-item text-capitalize"><a href="#">Transaksi</a></li>
            <li class="breadcrumb-item text-capitalize active" aria-current="page">Index</li>
        </ul>
    </nav>
</div>
@endsection

@section("content")
<div class="row">
    <div class="col-12 mt-4">
        <div class="card border-0 rounded shadow p-4">
            <div class="row mb-3">
                <div class="col-lg-12 d-flex">
                    @if(Auth::user()->hasRole([\App\Enums\RoleEnum::OWNER,\App\Enums\RoleEnum::AGEN,\App\Enums\RoleEnum::ADMIN_AGEN]))
                    <a href="{{route('dashboard.orders.create')}}" class="btn btn-primary btn-sm btn-add" style="margin-right: 5px;"><i class="fa fa-plus"></i> Tambah</a>
                    @endif
                    <a href="#" class="btn btn-success btn-sm btn-filter" style="margin-right: 5px;"><i class="fa fa-filter"></i> Filter</a>
                    <div class="dropdown-primary" style="margin-right: 5px;">
                        <button type="button" class="btn btn-info btn-sm" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            Export <i class="fa fa-file"></i>
                        </button>
                        <div class="dropdown-menu">
                            <a href="#" class="dropdown-item btn-export-excel">Export Excel</a>
                        </div>
                    </div>
                    <a href="{{route('dashboard.orders.index')}}" class="btn @if(!empty(request()->all())) btn-warning @else btn-secondary @endif btn-sm"><i class="fa fa-refresh"></i> Refresh</a>
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
                                    <th>Kode Transaksi</th>
                                    <th>Total</th>
                                    <th>Metode Pembayaran</th>
                                    @if(Auth::user()->hasRole([\App\Enums\RoleEnum::OWNER]))
                                    <th>Pengguna</th>
                                    @endif
                                    <th>Pelanggan</th>
                                    <th>Status</th>
                                    <th>Dibuat Pada</th>
                                </thead>
                                <tbody>
                                    @forelse ($table as $index => $row)
                                    <tr>
                                        <td>
                                            <div class="dropdown-primary me-2 mt-2">
                                                <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                    <i class="fa fa-bars"></i>
                                                </button>
                                                <div class="dropdown-menu">
                                                    <a href="{{route('dashboard.orders.show',$row->id)}}" class="dropdown-item"><i class="fa fa-eye"></i> Show</a>
                                                    @if(Auth::user()->hasRole([\App\Enums\RoleEnum::OWNER]))
                                                    <a href="{{route('dashboard.orders.edit',$row->id)}}" class="dropdown-item"><i class="fa fa-edit"></i> Edit</a>
                                                    <a href="#" class="dropdown-item btn-delete" data-id="{{$row->id}}"><i class="fa fa-trash"></i> Hapus</a>
                                                    @endif
                                                </div>
                                            </div>
                                        </td>
                                        <td>{{$table->firstItem() + $index}}</td>
                                        <td>{{$row->code}}</td>
                                        <td>{{number_format($row->totalNeto(),0,',','.')}}</td>
                                        <td>{{$row->provider->name ?? null}}</td>
                                        @if(Auth::user()->hasRole([\App\Enums\RoleEnum::OWNER]))
                                        <td>{{$row->user->name ?? null}}</td>
                                        @endif
                                        <td>
                                            @if(!empty($row->customer))
                                            {{$row->customer->name ?? null}}
                                            @else
                                            Umum
                                            @endif
                                        </td>
                                        <td>
                                            <span class="badge bg-{{$row->status()->class ?? null}}">{{$row->status()->msg ?? null}}</span>
                                        </td>
                                        <td>{{date('d-m-Y H:i:s',strtotime($row->created_at))}}</td>
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
                    {!!$table->links()!!}
                </div>
            </div>
        </div>
    </div>
</div>

@include("dashboard.orders.modal.index")
@include("dashboard.components.loader")

<form id="frmDelete" method="POST">
    @csrf
    @method('DELETE')
    <input type="hidden" name="id" />
</form>

@endsection

@section("script")
<script>
    $(function() {
        $(document).on("click", ".btn-filter", function(e) {
            e.preventDefault();

            $("#modalFilter").modal("show");
        });

        $(document).on("click", ".btn-delete", function() {
            let id = $(this).data("id");
            if (confirm("Apakah anda yakin ingin menghapus data ini ?")) {
                $("#frmDelete").attr("action", "{{ route('dashboard.orders.destroy', '_id_') }}".replace("_id_", id));
                $("#frmDelete").find('input[name="id"]').val(id);
                $("#frmDelete").submit();
            }
        })

        $(document).on("click", ".btn-export-excel", function(e) {
            e.preventDefault();

            $('#modalExport').find('.export-title').html("Excel");
            $("#frmExport").attr("action", "{{ route('dashboard.orders.exportExcel') }}");
            $("#modalExport").modal("show");
        });
    })
</script>
@endsection