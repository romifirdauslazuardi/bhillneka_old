@extends("dashboard.layouts.main")

@section("title","Akuntansi")

@section("css")
@endsection

@section("breadcumb")
<div class="d-md-flex justify-content-between align-items-center">
    <h5 class="mb-0">Akuntansi</h5>

    <nav aria-label="breadcrumb" class="d-inline-block mt-2 mt-sm-0">
        <ul class="breadcrumb bg-transparent rounded mb-0 p-0">
            <li class="breadcrumb-item text-capitalize"><a href="#">Akuntansi</a></li>
            <li class="breadcrumb-item text-capitalize active" aria-current="page">Daftar Akuntansi</li>
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
                    @if(!empty(Auth::user()->business_id))
                    <a href="{{route('dashboard.cost-accountings.create')}}" class="btn btn-primary btn-sm btn-add" style="margin-right: 5px;"><i class="fa fa-plus"></i> Tambah</a>
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
                    <div class="dropdown-primary" style="margin-right: 5px;">
                        <button type="button" class="btn btn-info btn-sm" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            Import <i class="fa fa-file"></i>
                        </button>
                        <div class="dropdown-menu">
                            <a href="#" class="dropdown-item btn-import-excel">Import Excel</a>
                        </div>
                    </div>
                    <a href="{{route('dashboard.cost-accountings.index')}}" class="btn @if(!empty(request()->all())) btn-warning @else btn-secondary @endif btn-sm"><i class="fa fa-refresh"></i> Refresh</a>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-12">
                    <div class="d-flex justify-content-between">
                        <h5 class="my-3">Total Pemasukan : <b>{{number_format($totalIn,0,',','.')}}</b></h5>
                        <h5 class="my-3">Total Pengeluaran : <b>{{number_format($totalOut,0,',','.')}}</b></h5>
                        <h5 class="my-3">Total Pendapatan : <b>{{number_format($totalIncome,0,',','.')}}</b></h5>
                    </div>
                    <div class="table-responsive">
                        <div class="table">
                            <table class="table table-striped table-bordered">
                                <thead>
                                    <th>Aksi</th>
                                    <th>No</th>
                                    <th>Nama Kegiatan</th>
                                    <th>Deskripsi</th>
                                    <th>Pemasukan</th>
                                    <th>Pengeluaran</th>
                                    @if(Auth::user()->hasRole([\App\Enums\RoleEnum::OWNER]))
                                    <th>Nama Bisnis</th>
                                    <th>Pemilik Usaha</th>
                                    @endif
                                    <th>Author</th>
                                    <th>Dibuat Pada</th>
                                </thead>
                                <tbody>
                                    @forelse ($table as $index => $row)
                                    <tr class="@if($row->type == \App\Enums\CostAccountingEnum::TYPE_PEMASUKAN) table-success @else table-danger @endif">
                                        <td>
                                            <div class="dropdown-primary me-2 mt-2">
                                                <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                    <i class="fa fa-bars"></i>
                                                </button>
                                                <div class="dropdown-menu">
                                                    <a href="{{route('dashboard.cost-accountings.show',$row->id)}}" class="dropdown-item"><i class="fa fa-eye"></i> Show</a>
                                                    <a href="{{route('dashboard.cost-accountings.edit',$row->id)}}" class="dropdown-item"><i class="fa fa-edit"></i> Edit</a>
                                                    <a href="#" class="dropdown-item btn-delete" data-id="{{$row->id}}"><i class="fa fa-trash"></i> Hapus</a>
                                                </div>
                                            </div>
                                        </td>
                                        <td>{{$table->firstItem() + $index}}</td>
                                        <td>{{$row->name}}</td>
                                        <td>{{$row->description}}</td>
                                        <td>
                                            @if($row->type == \App\Enums\CostAccountingEnum::TYPE_PEMASUKAN)
                                            {{number_format($row->nominal,0,',','.')}}
                                            @endif
                                        </td>
                                        <td>
                                            @if($row->type == \App\Enums\CostAccountingEnum::TYPE_PENGELUARAN)
                                            {{number_format($row->nominal,0,',','.')}}
                                            @endif
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

@include("dashboard.cost-accountings.modal.index")
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

        $(document).on("click", ".btn-export-excel", function(e) {
            e.preventDefault();

            $('#modalExportExcel').find('.export-title').html("Excel");
            $("#frmExportExcel").attr("action", "{{ route('dashboard.cost-accountings.exportExcel') }}");
            $("#modalExportExcel").modal("show");
        });

        $(document).on("click", ".btn-import-excel", function(e) {
            e.preventDefault();

            $("#modalImport").modal("show");
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
                $("#frmDelete").attr("action", "{{ route('dashboard.cost-accountings.destroy', '_id_') }}".replace("_id_", id));
                $("#frmDelete").find('input[name="id"]').val(id);
                $("#frmDelete").submit();
            }
        })

        $(document).on('submit','#frmImport',function(e){
            e.preventDefault();
            if(confirm("Apakah anda yakin ingin menyimpan data ini ?")){
                $.ajax({
                    url : $("#frmImport").attr("action"),
                    method : "POST",
                    data : new FormData($('#frmImport')[0]),
                    contentType:false,
                    cache:false,
                    processData:false,
                    dataType : "JSON",
                    beforeSend : function(){
                        return openLoader();
                    },
                    success : function(resp){
                        if(resp.success == false){
                            responseFailed(resp.message);                   
                        }
                        else{
                            responseSuccess(resp.message,"{{route('dashboard.cost-accountings.index')}}");
                        }
                    },
                    error: function (request, status, error) {
                        if(request.status == 422){
                            responseFailed(request.responseJSON.message);
                        }
                        else{
                            responseInternalServerError();
                        }
                    },
                    complete :function(){
                        return closeLoader();
                    }
                })
            }
        })
    })
</script>
@endsection