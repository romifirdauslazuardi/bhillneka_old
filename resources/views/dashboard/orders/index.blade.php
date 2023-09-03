@extends("dashboard.layouts.main")

@section("title","Penjualan")

@section("css")
@endsection

@section("breadcumb")
<div class="row">
    <div class="col-sm-12">
        <h3 class="page-title">Penjualan</h3>
        <ul class="breadcrumb">
            <li class="breadcrumb-item"><a href="#">Penjualan</a></li>
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
                <div class="col-lg-12 d-flex">
                    @if(Auth::user()->hasRole([\App\Enums\RoleEnum::OWNER,\App\Enums\RoleEnum::AGEN,\App\Enums\RoleEnum::ADMIN_AGEN]))
                        @if(!empty(Auth::user()->business_id))
                            <a href="{{route('dashboard.orders.create')}}" class="btn btn-primary btn-sm btn-add" style="margin-right: 5px;"><i class="fa fa-plus"></i> Tambah</a>
                        @endif
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
                                    <th>Kode Pesanan</th>
                                    <th>Total</th>
                                    <th>Customer</th>
                                    <th>Kategori Bisnis</th>
                                    <th>Jenis Pesanan</th>
                                    <th>Metode Pembayaran</th>
                                    @if(Auth::user()->hasRole([\App\Enums\RoleEnum::OWNER]))
                                    <th>Pemilik Usaha</th>
                                    @endif
                                    <th>Progress Pengerjaan</th>
                                    <th>Status Pembayaran</th>
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
                                                        @if(!empty(Auth::user()->business_id))
                                                            <a href="{{route('dashboard.orders.edit',$row->id)}}" class="dropdown-item"><i class="fa fa-edit"></i> Edit</a>
                                                            <a href="#" class="dropdown-item btn-delete" data-id="{{$row->id}}"><i class="fa fa-trash"></i> Hapus</a>
                                                        @endif
                                                    @endif
                                                    @if(Auth::user()->hasRole([\App\Enums\RoleEnum::OWNER,\App\Enums\RoleEnum::AGEN,\App\Enums\RoleEnum::ADMIN_AGEN]))
                                                        @if(!empty(Auth::user()->business_id))
                                                        <a href="#" class="dropdown-item btn-status" data-id="{{$row->id}}" data-status="{{$row->status}}"><i class="fa fa-edit"></i> Edit Status Pembayaran</a>
                                                        <a href="#" class="dropdown-item btn-progress" data-id="{{$row->id}}" data-progress="{{$row->progress}}"><i class="fa fa-edit"></i> Edit Progress Pesanan</a>
                                                        @endif
                                                        <a href="{{route('dashboard.orders.print',$row->id)}}" class="dropdown-item"><i class="fa fa-print"></i> Print</a>
                                                    @endif
                                                </div>
                                            </div>
                                        </td>
                                        <td>{{$table->firstItem() + $index}}</td>
                                        <td>{{$row->code}}</td>
                                        <td>{{number_format($row->totalNeto(),0,',','.')}}</td>
                                        <td>
                                            @if(!empty($row->customer))
                                            {{$row->customer->name ?? null}}
                                            <br>
                                            {{$row->customer->phone ?? null}}
                                            @else
                                            {{$row->customer_name}}
                                            <br>
                                            {{$row->customer_phone}}
                                            @endif
                                        </td>
                                        <td>{{$row->business->category->name ?? null}}</td>
                                        <td>
                                            {{$row->type() ?? null}}
                                        </td>
                                        <td>{{$row->provider->name ?? null}}</td>
                                        @if(Auth::user()->hasRole([\App\Enums\RoleEnum::OWNER]))
                                        <td>{{$row->user->name ?? null}}</td>
                                        @endif
                                        <td>
                                            <span class="badge bg-{{$row->progress()->class ?? null}}">{{$row->progress()->msg ?? null}}</span>
                                        </td>
                                        <td>
                                            <span class="badge bg-{{$row->status()->class ?? null}}">{{$row->status()->msg ?? null}}</span>
                                        </td>
                                        <td>{{date('d-m-Y H:i:s',strtotime($row->created_at))}}</td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="12" class="text-center">Data tidak ditemukan</td>
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
                $("#frmDelete").attr("action", "{{ route('dashboard.orders.destroy', '_id_') }}".replace("_id_", id));
                $("#frmDelete").find('input[name="id"]').val(id);
                $("#frmDelete").submit();
            }
        })

        $(document).on("click", ".btn-progress", function() {
            let id = $(this).data("id");
            let progress = $(this).data("progress");

            $("#frmUpdateProgress").attr("action", "{{ route('dashboard.orders.updateProgress', '_id_') }}".replace("_id_", id));
            $("#frmUpdateProgress").find('select[name="progress"]').val(progress).trigger("change");
            $("#modalUpdateProgress").modal("show");
        })

        $(document).on("click", ".btn-status", function() {
            let id = $(this).data("id");
            let status = $(this).data("status");

            $("#frmUpdateStatus").attr("action", "{{ route('dashboard.orders.updateStatus', '_id_') }}".replace("_id_", id));
            $("#frmUpdateStatus").find('select[name="status"]').val(status).trigger("change");
            $("#modalUpdateStatus").modal("show");
        })

        $(document).on("click", ".btn-export-excel", function(e) {
            e.preventDefault();

            $('#modalExportExcel').find('.export-title').html("Excel");
            $("#frmExportExcel").attr("action", "{{ route('dashboard.orders.exportExcel') }}");
            $("#modalExportExcel").modal("show");
        });

        $(document).on('submit','#frmUpdateProgress',function(e){
            e.preventDefault();
            if(confirm("Apakah anda yakin ingin menyimpan data ini ?")){
                $.ajax({
                    url : $("#frmUpdateProgress").attr("action"),
                    method : "POST",
                    data : new FormData($('#frmUpdateProgress')[0]),
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
                            responseSuccess(resp.message,"{{route('dashboard.orders.index')}}");
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

        $(document).on('submit','#frmUpdateStatus',function(e){
            e.preventDefault();
            if(confirm("Apakah anda yakin ingin menyimpan data ini ?")){
                $.ajax({
                    url : $("#frmUpdateStatus").attr("action"),
                    method : "POST",
                    data : new FormData($('#frmUpdateStatus')[0]),
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
                            responseSuccess(resp.message,"{{route('dashboard.orders.index')}}");
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
