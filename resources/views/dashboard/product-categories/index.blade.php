@extends("dashboard.layouts.main")

@section("title","Kategori Produk")

@section("css")
@endsection

@section("breadcumb")
<div class="d-md-flex justify-content-between align-items-center">
    <h5 class="mb-0">Kategori Produk</h5>

    <nav aria-label="breadcrumb" class="d-inline-block mt-2 mt-sm-0">
        <ul class="breadcrumb bg-transparent rounded mb-0 p-0">
            <li class="breadcrumb-item text-capitalize"><a href="#">Kategori Produk</a></li>
            <li class="breadcrumb-item text-capitalize active" aria-current="page">Daftar Kategori Produk</li>
        </ul>
    </nav>
</div>
@endsection

@section("content")
<div class="row">
    <div class="col-12 mt-4">
        <div class="card border-0 rounded shadow p-4">
            <div class="row mb-3">
                <div class="col-lg-12">
                    @if(!empty(Auth::user()->business_id))
                    <a href="{{route('dashboard.product-categories.create')}}" class="btn btn-primary btn-sm"><i class="fa fa-plus"></i> Tambah</a>
                    @endif
                    <a href="#" class="btn btn-success btn-sm btn-filter"><i class="fa fa-filter"></i> Filter</a>
                    <a href="{{route('dashboard.product-categories.index')}}" class="btn @if(!empty(request()->all())) btn-warning @else btn-secondary @endif btn-sm"><i class="fa fa-refresh"></i> Refresh</a>
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
                                    <th>Bisnis</th>
                                    @if(Auth::user()->hasRole([\App\Enums\RoleEnum::OWNER]))
                                    <th>Pemilik Usaha</th>
                                    @endif
                                    <th>Author</th>
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
                                                    @if(!empty(Auth::user()->business_id))
                                                    <a href="{{route('dashboard.product-categories.edit',$row->id)}}" class="dropdown-item btn-edit"><i class="fa fa-edit"></i> Edit</a>
                                                    <a href="#" class="dropdown-item btn-delete" data-id="{{$row->id}}"><i class="fa fa-trash"></i> Hapus</a>
                                                    @endif
                                                </div>
                                            </div>
                                        </td>
                                        <td>{{$table->firstItem() + $index}}</td>
                                        <td>{{$row->name}}</td>
                                        <td>{{$row->business->name ?? null}}</td>
                                        @if(Auth::user()->hasRole([\App\Enums\RoleEnum::OWNER]))
                                        <td>{{$row->user->name ?? null}}</td>
                                        @endif
                                        <td>{{$row->author->name ?? null}}</td>
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

@include("dashboard.product-categories.modal.index")
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

        $('button[type="submit"]').attr("disabled",false);

        @if(!empty(Auth::user()->business_id))
            getBusiness('.select-business','{{Auth::user()->business->user_id ?? null}}',null);
        @else
            @if(Auth::user()->hasRole([\App\Enums\RoleEnum::AGEN]))
                getBusiness('.select-business','{{Auth::user()->id}}',null);
            @elseif(Auth::user()->hasRole([\App\Enums\RoleEnum::ADMIN_AGEN]))
                getBusiness('.select-business','{{Auth::user()->user_id}}',null);
            @endif
        @endif
        
        $(document).on("click", ".btn-filter", function(e) {
            e.preventDefault();

            $("#modalFilter").modal("show");
        });

        $(document).on("click", ".btn-add", function(e) {
            e.preventDefault();

            $('#modalStore').modal("show");
        });

        $(document).on("change",".select-user",function(e){
            e.preventDefault();
            let val = $(this).val();

            $('.select-business').html('<option value="">==Semua Bisnis==</option>');

            if(val != null && val != undefined && val != ""){
                getBusiness(".select-business",val,null);
            }
        });

        $(document).on("click", ".btn-delete", function() {
            let id = $(this).data("id");
            if (confirm("Apakah anda yakin ingin menghapus data ini ?")) {
                $("#frmDelete").attr("action", "{{ route('dashboard.product-categories.destroy', '_id_') }}".replace("_id_", id));
                $("#frmDelete").find('input[name="id"]').val(id);
                $("#frmDelete").submit();
            }
        })
    })
</script>
@endsection