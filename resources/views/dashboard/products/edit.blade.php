@extends("dashboard.layouts.main")

@section("title","Produk")

@section("css")
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
<div class="row">
    <div class="col-12 mt-4">
        <div class="card border-0 rounded shadow p-4">
            <form action="{{route('dashboard.products.update',$result->id)}}" id="frmUpdate" autocomplete="off">
                @csrf
                @method("PUT")
                <div class="row mb-3">
                    <div class="col-lg-12">
                        @if(Auth::user()->hasRole([\App\Enums\RoleEnum::OWNER]))
                        <div class="form-group row mb-3">
                            <label class="col-md-3 col-form-label">Pengguna<span class="text-danger">*</span></label>
                            <div class="col-md-9">
                                <select class="form-control select2 select-user" name="user_id" >
                                    <option value="">==Pilih Pengguna==</option>
                                    @foreach ($users as $index => $row)
                                    <option value="{{$row->id}}" @if($row->id == old('user_id',$result->user_id)) selected @endif>{{$row->name}} - {{$row->phone}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        @endif
                        <div class="form-group row mb-3">
                            <label class="col-md-3 col-form-label">Kode Produk <span class="text-danger">*</span></label>
                            <div class="col-md-9">
                                <input type="text" class="form-control" name="code" placeholder="Kode Produk" value="{{old('code',$result->code)}}" >
                            </div>
                        </div>
                        <div class="form-group row mb-3">
                            <label class="col-md-3 col-form-label">Nama Produk <span class="text-danger">*</span></label>
                            <div class="col-md-9">
                                <input type="text" class="form-control" name="name" placeholder="Nama Produk" value="{{old('name',$result->name)}}" >
                            </div>
                        </div>
                        <div class="form-group row mb-3">
                            <label class="col-md-3 col-form-label">Harga Produk <span class="text-danger">*</span></label>
                            <div class="col-md-9">
                                <input type="number" class="form-control" name="price" placeholder="Harga Produk" value="{{old('price',$result->price)}}" >
                            </div>
                        </div>
                        <div class="form-group row mb-3">
                            <label class="col-md-3 col-form-label">Deskripsi Produk <span class="text-danger">*</span></label>
                            <div class="col-md-9">
                                <textarea class="form-control" rows="5" name="description">{{old('description',$result->description)}}</textarea>
                            </div>
                        </div>
                        <div class="form-group row mb-3">
                            <label class="col-md-3 col-form-label">Kategori<span class="text-danger">*</span></label>
                            <div class="col-md-9">
                                <select class="form-control select2 select-category" name="category_id" >
                                    <option value="">==Pilih Kategori==</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group row mb-3">
                            <label class="col-md-3 col-form-label">Satuan<span class="text-danger">*</span></label>
                            <div class="col-md-9">
                                <select class="form-control select2 select-unit" name="unit_id" >
                                    <option value="">==Pilih Satuan==</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group row mb-3">
                            <label class="col-md-3 col-form-label">Status<span class="text-danger">*</span></label>
                            <div class="col-md-9">
                                <select class="form-control select2" name="status" >
                                    <option value="">==Pilih Status Produk==</option>
                                    @foreach ($status as $index => $row)
                                    <option value="{{$index}}" @if($index == $result->status) selected @endif>{{$row}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-group row mb-3">
                            <label class="col-md-3 col-form-label">Apakah Produk Stok ? <span class="text-danger">*</span></label>
                            <div class="col-md-9">
                                <select class="form-control select2" name="is_using_stock" >
                                    <option value="">==Pilih Dengan Stock / Tanpa Stock==</option>
                                    @foreach ($is_using_stock as $index => $row)
                                    <option value="{{$index}}" @if($index == $result->is_using_stock) selected @endif>{{$row}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-12">
                        <a href="{{route('dashboard.products.index')}}" class="btn btn-warning btn-sm"><i class="fa fa-arrow-left"></i> Kembali</a>
                        <button type="submit" class="btn btn-primary btn-sm" disabled><i class="fa fa-save"></i> Simpan</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

@include("dashboard.components.loader")
@endsection

@section("script")
<script>
    $(function(){

        $('button[type="submit"]').attr("disabled",false);

        @if(!empty($result->category_id))
            getProductCategory('.select-category','{{$result->user_id}}','{{$result->category_id}}');
        @endif

        @if(!empty($result->unit_id))
            getUnit('.select-unit','{{$result->user_id}}','{{$result->unit_id}}');
        @endif

        $(document).on("change", ".select-user", function(e) {
            e.preventDefault();
            let val = $(this).val();

            $('.select-category').html('<option value="">==Pilih Kategori==</option>');
            $('.select-unit').html('<option value="">==Pilih Unit==</option>');

            if(val != "" && val != undefined && val != null){
                getProductCategory('.select-category',val,null);
                getUnit('.select-unit',val,null);
            }
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
                            responseSuccess(resp.message,"{{route('dashboard.products.index')}}");
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