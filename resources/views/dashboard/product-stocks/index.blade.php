@extends("dashboard.layouts.main")

@section("title","Inventaris")

@section("css")
@endsection

@section("breadcumb")
<div class="d-md-flex justify-content-between align-items-center">
    <h5 class="mb-0">Inventaris</h5>

    <nav aria-label="breadcrumb" class="d-inline-block mt-2 mt-sm-0">
        <ul class="breadcrumb bg-transparent rounded mb-0 p-0">
            <li class="breadcrumb-item text-capitalize"><a href="#">Inventaris</a></li>
            <li class="breadcrumb-item text-capitalize active" aria-current="page">Daftar Inventaris</li>
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
                    <a href="{{route('dashboard.product-stocks.create')}}" class="btn btn-primary btn-sm btn-add" style="margin-right: 5px;"><i class="fa fa-plus"></i> Tambah</a>
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
                    <a href="{{route('dashboard.product-stocks.index')}}" class="btn @if(!empty(request()->all())) btn-warning @else btn-secondary @endif btn-sm"><i class="fa fa-refresh"></i> Refresh</a>
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
                                    <th>Kode Produk</th>
                                    <th>Nama Produk</th>
                                    <th>Stok</th>
                                    <th>Kategori Bisnis</th>
                                    @if(Auth::user()->hasRole([\App\Enums\RoleEnum::OWNER]))
                                    <th>Nama Bisnis</th>
                                    <th>Pemilik Usaha</th>
                                    @endif
                                    <th>Author</th>
                                    <th>Status Produk</th>
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
                                                    @if(Auth::user()->hasRole([\App\Enums\RoleEnum::OWNER,\App\Enums\RoleEnum::AGEN,\App\Enums\RoleEnum::ADMIN_AGEN]))
                                                        @if(!empty(Auth::user()->business_id))
                                                        <a href="#" class="dropdown-item btn-add-stock" data-id="{{$row->id}}" data-product-name="{{$row->name}}"><i class="fa fa-plus"></i> Tambah Stok</a>
                                                        @endif
                                                    @endif
                                                    <a href="{{route('dashboard.product-stocks.show',$row->id)}}" class="dropdown-item"><i class="fa fa-eye"></i> Show</a>
                                                </div>
                                            </div>
                                        </td>
                                        <td>{{$table->firstItem() + $index}}</td>
                                        <td>{{$row->name}}</td>
                                        <td>{{$row->code}}</td>
                                        <td>{{$row->stock}}</td>
                                        <td>{{$row->business->category->name ?? null}}</td>
                                        @if(Auth::user()->hasRole([\App\Enums\RoleEnum::OWNER]))
                                        <td>{{$row->business->name ?? null}}</td>
                                        <td>{{$row->business->user->name ?? null}}</td>
                                        @endif
                                        <td>{{$row->author->name ?? null}}</td>
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

@include("dashboard.product-stocks.modal.index")
@include("dashboard.components.loader")

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
            $("#frmExportExcel").attr("action", "{{ route('dashboard.product-stocks.exportExcel') }}");
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

        $(document).on("click", ".btn-add-stock", function(e) {
            e.preventDefault();

            let id = $(this).attr("data-id");
            let productName = $(this).attr("data-product-name");

            $("#frmAddStock").find('input[name="product_id"]').val(id);
            $("#frmAddStock").find('.product-name').val(productName);
            $("#modalAddStock").modal("show");
        });

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
                            return responseFailed(resp.message);
                        }
                        else{
                            return responseSuccess(resp.message,"{{route('dashboard.product-stocks.index')}}");
                        }
                    },
                    error: function (request, status, error) {
                        if(request.status == 422){
                            return responseFailed(request.responseJSON.message);
                        }
                        else if(request.status == 419){
                            return sessionTimeOut();
                        }
                        else{
                            return responseInternalServerError();
                        }
                    },
                    complete :function(){
                        return closeLoader();
                    }
                })
            }
        })

        $(document).on('submit','#frmAddStock',function(e){
            e.preventDefault();
            if(confirm("Apakah anda yakin ingin menyimpan data ini ?")){
                $.ajax({
                    url : $("#frmAddStock").attr("action"),
                    method : "POST",
                    data : new FormData($('#frmAddStock')[0]),
                    contentType:false,
                    cache:false,
                    processData:false,
                    dataType : "JSON",
                    beforeSend : function(){
                        return openLoader();
                    },
                    success : function(resp){
                        if(resp.success == false){
                            return responseFailed(resp.message);
                        }
                        else{
                            return responseSuccess(resp.message,"{{route('dashboard.product-stocks.index')}}");
                        }
                    },
                    error: function (request, status, error) {
                        if(request.status == 422){
                            return responseFailed(request.responseJSON.message);
                        }
                        else if(request.status == 419){
                            return sessionTimeOut();
                        }
                        else{
                            return responseInternalServerError();
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
