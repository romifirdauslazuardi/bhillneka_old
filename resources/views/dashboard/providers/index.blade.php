@extends("dashboard.layouts.main")

@section("title","Metode Pembayaran")

@section("css")
@endsection

@section("breadcumb")
<div class="row">
    <div class="col-sm-12">
        <h3 class="page-title">Metode Pembayaran</h3>
        <ul class="breadcrumb">
            <li class="breadcrumb-item"><a href="#">Metode Pembayaran</a></li>
            <li class="breadcrumb-item active">Daftar Metode Pembayaran</li>
        </ul>
    </div>
</div>
@endsection

@section("content")
<div class="row">
    <div class="col-12 mt-4">
        <div class="card border-0 rounded shadow p-4">
            <div class="row mb-3">
                <div class="col-lg-12">
                    <a href="{{route('dashboard.providers.create')}}" class="btn btn-primary btn-sm btn-add"><i class="fa fa-plus"></i> Tambah</a>
                    <a href="#" class="btn btn-success btn-sm btn-filter"><i class="fa fa-filter"></i> Filter</a>
                    <a href="{{route('dashboard.providers.index')}}" class="btn @if(!empty(request()->all())) btn-warning @else btn-secondary @endif btn-sm"><i class="fa fa-refresh"></i> Refresh</a>
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
                                    <th>Tipe</th>
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
                                                    <a href="{{route('dashboard.providers.show',$row->id)}}" class="dropdown-item"><i class="fa fa-eye"></i> Show</a>
                                                    <a href="{{route('dashboard.providers.edit',$row->id)}}" class="dropdown-item"><i class="fa fa-edit"></i> Edit</a>
                                                    <a href="#" class="dropdown-item btn-delete" data-id="{{$row->id}}"><i class="fa fa-trash"></i> Hapus</a>
                                                </div>
                                            </div>
                                        </td>
                                        <td>{{$table->firstItem() + $index}}</td>
                                        <td>{{$row->name}}</td>
                                        <td>{{$row->type() ?? null}}</td>
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

@include("dashboard.providers.modal.index")
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
                $("#frmDelete").attr("action", "{{ route('dashboard.providers.destroy', '_id_') }}".replace("_id_", id));
                $("#frmDelete").find('input[name="id"]').val(id);
                $("#frmDelete").submit();
            }
        })
    })
</script>
@endsection
