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
            <li class="breadcrumb-item text-capitalize active" aria-current="page">Show</li>
        </ul>
    </nav>
</div>
@endsection

@section("content")
<div class="row pb-2">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title mb-3">Informasi Data Inventaris</h5>
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
                                Stok
                            </div>
                            <div class="col-md-8">
                                : {{$result->stock}}
                            </div>
                        </div>

                        <div class="row mb-2">
                            <div class="col-md-3">
                                Status Produk
                            </div>
                            <div class="col-md-8">
                                : <span class="badge bg-{{$result->status()->class ?? null}}">{{$result->status()->msg ?? null}}</span>
                            </div>
                        </div>

                        <div class="row mb-2">
                            <div class="col-md-3">
                                Bisnis
                            </div>
                            <div class="col-md-8">
                                : {{$result->business->name ?? null}}
                            </div>
                        </div>

                        <div class="row mb-2">
                            <div class="col-md-3">
                                Pemilik Usaha
                            </div>
                            <div class="col-md-8">
                                : {{$result->business->user->name ?? null}}
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
                            <a href="{{route('dashboard.product-stocks.index')}}" class="btn btn-warning btn-sm" style="margin-right: 10px;"><i class="fa fa-arrow-left"></i> Kembali</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@include("dashboard.product-stocks.stocks.index")
@include("dashboard.product-stocks.stocks.modal")

<form id="frmDelete" method="POST">
    @csrf
    @method('DELETE')
    <input type="hidden" name="id"/>
</form>

@include("dashboard.components.loader")

@endsection

@section("script")
<script>
    $(function(){
        $(document).on("click", ".btn-delete", function() {
            let id = $(this).data("id");
            if (confirm("Apakah anda yakin ingin menghapus data ini ?")) {
                $("#frmDelete").attr("action", "{{ route('dashboard.product-stocks.destroy', '_id_') }}".replace("_id_", id));
                $("#frmDelete").find('input[name="id"]').val(id);
                $("#frmDelete").submit();
            }
        })

        $(document).on("click", ".btn-edit", function(e) {
            e.preventDefault();

            let id = $(this).data("id");
            let type = $(this).data("type");
            let date = $(this).data("date");
            let qty = $(this).data("qty");
            let note = $(this).data("note");

            $("#frmUpdate").attr("action", "{{ route('dashboard.product-stocks.update', '_id_') }}".replace("_id_", id));
            $('#frmUpdate').find('select[name="type"]').val(type).trigger("change");
            $('#frmUpdate').find('input[name="date"]').val(date);
            $('#frmUpdate').find('input[name="qty"]').val(qty);
            $('#frmUpdate').find('textarea[name="note"]').val(note);
            $('#modalUpdate').modal("show");
        });

        $(document).on("click", ".btn-add-stock", function(e) {
            e.preventDefault();

            let id = $(this).attr("data-id");
            let productName = $(this).attr("data-product-name");

            $("#frmAddStock").find('input[name="product_id"]').val(id);
            $("#frmAddStock").find('.product-name').val(productName);
            $("#modalAddStock").modal("show");
        });

        $(document).on('submit','#frmUpdate',function(e){
            e.preventDefault();
            if(confirm("Apakah anda yakin ingin menyimpan data ini ?")){
                $.ajax({
                    url : $("#frmUpdate").attr("action"),
                    method : "POST",
                    data : new FormData($('#frmUpdate')[0]),
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
                            responseSuccess(resp.message,"{{route('dashboard.product-stocks.show',$result->id)}}");
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
                            responseFailed(resp.message);                   
                        }
                        else{
                            responseSuccess(resp.message,"{{route('dashboard.product-stocks.index')}}");
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