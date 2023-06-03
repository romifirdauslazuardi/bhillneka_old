@extends("dashboard.layouts.main")

@section("title","Produk")

@section("css")
<!-- Datatables -->
<link href="{{URL::to('/')}}/templates/dashboard/assets/libs/datatables/dataTables.bootstrap4.min.css" rel="stylesheet" type="text/css" />
<link href="{{URL::to('/')}}/templates/dashboard/assets/libs/datatables/buttons.bootstrap4.min.css" rel="stylesheet" type="text/css" />
<!-- Responsive datatable examples -->
<link href="{{URL::to('/')}}/templates/dashboard/assets/libs/datatables/responsive.bootstrap4.min.css" rel="stylesheet" type="text/css" />
@endsection

@section("breadcumb")
<div class="d-md-flex justify-content-between align-items-center">
    <h5 class="mb-0">Produk</h5>

    <nav aria-label="breadcrumb" class="d-inline-block mt-2 mt-sm-0">
        <ul class="breadcrumb bg-transparent rounded mb-0 p-0">
            <li class="breadcrumb-item text-capitalize"><a href="#">Produk</a></li>
            <li class="breadcrumb-item text-capitalize active" aria-current="page">Edit</li>
        </ul>
    </nav>
</div>
@endsection

@section("content")
<div class="row pb-2">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title mb-3">Informasi Data Produk</h5>
                <div class="row">
                    <div class="col-12">

                        <div class="row mb-2">
                            <div class="col-md-3">
                                Kode Produk
                            </div>
                            <div class="col-md-8">
                                : {{$result->code}}
                            </div>
                        </div>

                        <div class="row mb-2">
                            <div class="col-md-3">
                                Nama Produk
                            </div>
                            <div class="col-md-8">
                                : {{$result->name}}
                            </div>
                        </div>

                        <div class="row mb-2">
                            <div class="col-md-3">
                                Harga Produk
                            </div>
                            <div class="col-md-8">
                                : {{number_format($result->price,0,',','.')}}
                            </div>
                        </div>

                        <div class="row mb-2">
                            <div class="col-md-3">
                                Deskripsi
                            </div>
                            <div class="col-md-8">
                                : {{$result->description}}
                            </div>
                        </div>

                        <div class="row mb-2">
                            <div class="col-md-3">
                                Apakah Menggunakan Stock
                            </div>
                            <div class="col-md-8">
                                : <span class="badge bg-{{$result->is_using_stock()->class ?? null}}">{{$result->is_using_stock()->msg ?? null}}</span>
                            </div>
                        </div>

                        <div class="row mb-2">
                            <div class="col-md-3">
                                Status
                            </div>
                            <div class="col-md-8">
                                : <span class="badge bg-{{$result->status()->class ?? null}}">{{$result->status()->msg ?? null}}</span>
                            </div>
                        </div>

                        <div class="row mb-2">
                            <div class="col-md-3">
                                Author
                            </div>
                            <div class="col-md-8">
                                : {{$result->author->name ?? null}}
                            </div>
                        </div>

                        <div class="row mb-2">
                            <div class="col-md-3">
                                Tanggal Dibuat
                            </div>
                            <div class="col-md-8">
                                : {{ date('d-m-Y H:i:s',strtotime($result->created_at)) }}
                            </div>
                        </div>

                        <div class="row mb-2">
                            <div class="col-md-3">
                                Tanggal Diperbarui
                            </div>
                            <div class="col-md-8">
                                : {{ date('d-m-Y H:i:s',strtotime($result->updated_at)) }}
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12">
                        <div class="d-flex justify-content-start mt-3">
                            <a href="{{route('dashboard.products.index')}}" class="btn btn-warning btn-sm" style="margin-right: 10px;"><i class="fa fa-arrow-left"></i> Kembali</a>
                            <a href="{{route('dashboard.products.edit',$result->id)}}" class="btn btn-primary btn-sm" style="margin-right: 10px;"><i class="fa fa-edit"></i> Edit</a>
                            <a href="#" class="btn btn-danger btn-sm btn-delete" data-id="{{$result->id}}"><i class="fa fa-trash"></i> Hapus</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@if($result->is_using_stock == \App\Enums\ProductEnum::IS_USING_STOCK_TRUE)
@include("dashboard.products.stocks.index")
@include("dashboard.products.stocks.modal.index")
@endif

<form id="frmDelete" method="POST">
    @csrf
    @method('DELETE')
    <input type="hidden" name="id"/>
</form>

<form id="frmDeleteStock" method="POST">
    @csrf
    @method('DELETE')
    <input type="hidden" name="id"/>
</form>

@include("dashboard.components.loader")

@endsection

@section("script")
<!-- Datatables -->
<script src="{{URL::to('/')}}/templates/dashboard/assets/libs/parsleyjs/parsley.min.js"></script>
<script src="{{URL::to('/')}}/templates/dashboard/assets/libs/datatables/jquery.dataTables.min.js"></script>
<script src="{{URL::to('/')}}/templates/dashboard/assets/libs/datatables/dataTables.bootstrap4.min.js"></script>
<script src="{{URL::to('/')}}/assets/pages/datatables.init.js"></script>
<script src="{{URL::to('/')}}/templates/dashboard/assets/libs/datatables/dataTables.responsive.min.js"></script>
<script src="{{URL::to('/')}}/templates/dashboard/assets/libs/datatables/responsive.bootstrap4.min.js"></script>
<script>
    $(function(){
        $('.datatables').DataTable();

        $(document).on("click", ".btn-add-stock", function(e) {
            e.preventDefault();

            $('#modalStoreStock').modal("show");
        });

        $(document).on("click", ".btn-edit-stock", function(e) {
            e.preventDefault();

            let id = $(this).data("id");
            let qty = $(this).data("qty");
            let note = $(this).data("note");

            $("#frmUpdateStock").attr("action", "{{ route('dashboard.products.stocks.update', '_id_') }}".replace("_id_", id));
            $('#frmUpdateStock').find('input[name="qty"]').val(qty);
            $('#frmUpdateStock').find('textarea[name="note"]').val(note);
            $('#modalUpdateStock').modal("show");
        });

        $(document).on("click", ".btn-delete-stock", function() {
            let id = $(this).data("id");
            if (confirm("Apakah anda yakin ingin menghapus data ini ?")) {
                $("#frmDeleteStock").attr("action", "{{ route('dashboard.products.stocks.destroy', '_id_') }}".replace("_id_", id));
                $("#frmDeleteStock").find('input[name="id"]').val(id);
                $("#frmDeleteStock").submit();
            }
        })

        $(document).on("click", ".btn-delete", function(e) {
            e.preventDefault();
            let id = $(this).data("id");
            if (confirm("Apakah anda yakin ingin menghapus data ini ?")) {
                $("#frmDelete").attr("action", "{{ route('dashboard.products.destroy', '_id_') }}".replace("_id_", id));
                $("#frmDelete").find('input[name="id"]').val(id);
                $("#frmDelete").submit();
            }
        })

        $(document).on('submit','#frmStoreStock',function(e){
            e.preventDefault();
            if(confirm("Apakah anda yakin ingin menyimpan data ini ?")){
                $.ajax({
                    url : $("#frmStoreStock").attr("action"),
                    method : "POST",
                    data : new FormData($('#frmStoreStock')[0]),
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
                            responseSuccess(resp.message,"{{route('dashboard.products.show',$result->id)}}");
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

        $(document).on('submit','#frmUpdateStock',function(e){
            e.preventDefault();
            if(confirm("Apakah anda yakin ingin menyimpan data ini ?")){
                $.ajax({
                    url : $("#frmUpdateStock").attr("action"),
                    method : "POST",
                    data : new FormData($('#frmUpdateStock')[0]),
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
                            responseSuccess(resp.message,"{{route('dashboard.products.show',$result->id)}}");
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