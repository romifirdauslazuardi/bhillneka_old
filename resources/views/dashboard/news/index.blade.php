@extends("dashboard.layouts.main")

@section("title","News")

@section("css")
@endsection

@section("breadcumb")
<div class="row">
    <div class="col-sm-12">
        <h3 class="page-title">News</h3>
        <ul class="breadcrumb">
            <li class="breadcrumb-item"><a href="#">News</a></li>
            <li class="breadcrumb-item active">Data</li>
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
                    @if(!empty(Auth::user()->business_id))
                    <a href="{{route('dashboard.news.create')}}" class="btn btn-primary btn-sm btn-add"><i class="fa fa-plus"></i> Tambah</a>
                    @endif
                    <a href="#" class="btn btn-success btn-sm btn-filter"><i class="fa fa-filter"></i> Filter</a>
                    <a href="{{route('dashboard.news.index')}}" class="btn @if(!empty(request()->all())) btn-warning @else btn-secondary @endif btn-sm"><i class="fa fa-refresh"></i> Refresh</a>
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
                                    <th>Judul</th>
                                    <th>Deskripsi</th>
                                    <th>Nama Pelanggan</th>
                                    <th>No.Hp Pelanggan</th>
                                    @if(Auth::user()->hasRole([\App\Enums\RoleEnum::OWNER]))
                                    <th>Nama Bisnis</th>
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
                                                    <a href="{{route('dashboard.news.show',$row->id)}}" class="dropdown-item"><i class="fa fa-eye"></i> Show</a>
                                                    @if(Auth::user()->hasRole([\App\Enums\RoleEnum::OWNER]))
                                                        @if(!empty(Auth::user()->business_id))
                                                            <a href="{{route('dashboard.news.edit',$row->id)}}" class="dropdown-item"><i class="fa fa-edit"></i> Edit</a>
                                                            <a href="#" class="dropdown-item btn-delete" data-id="{{$row->id}}"><i class="fa fa-trash"></i> Hapus</a>
                                                            <a href="#" class="dropdown-item btn-status" data-id="{{$row->id}}" data-status="{{$row->status}}"><i class="fa fa-edit"></i> Edit Status Pembayaran</a>
                                                        @endif
                                                    @endif
                                                </div>
                                            </div>
                                        </td>
                                        <td>{{$table->firstItem() + $index}}</td>
                                        <td>{{$row->title}}</td>
                                        <td>{{$row->note}}</td>
                                        <td>
                                            @foreach($row->news_recipient as $i => $v)
                                            {{$v->user->name ?? null}}
                                            <br>
                                            @endforeach
                                        </td>
                                        <td>
                                            @foreach($row->news_recipient as $i => $v)
                                            {{$v->user->phone ?? null}}
                                            <br>
                                            @endforeach
                                        </td>
                                        @if(Auth::user()->hasRole([\App\Enums\RoleEnum::OWNER]))
                                        <td>{{$row->business->name ?? null}}</td>
                                        <td>{{$row->business->user->name ?? null}}</td>
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

@include("dashboard.news.modal.index")
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
        @endif

        $(document).on("click", ".btn-filter", function(e) {
            e.preventDefault();

            $("#modalFilter").modal("show");
        });

        $(document).on("change", ".select-user", function(e) {
            e.preventDefault();
            let val = $(this).val();

            $('.select-business').html('<option value="">==Semua Bisnis==</option>');

            if(val != "" && val != undefined && val != null){
                getBusiness('.select-business',val,null);
            }
        });

        $(document).on("click", ".btn-delete", function() {
            let id = $(this).data("id");
            if (confirm("Apakah anda yakin ingin menghapus data ini ?")) {
                $("#frmDelete").attr("action", "{{ route('dashboard.news.destroy', '_id_') }}".replace("_id_", id));
                $("#frmDelete").find('input[name="id"]').val(id);
                $("#frmDelete").submit();
            }
        })
    })
</script>
@endsection
