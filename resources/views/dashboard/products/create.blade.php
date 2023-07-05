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
            <li class="breadcrumb-item text-capitalize active" aria-current="page">Create</li>
        </ul>
    </nav>
</div>
@endsection

@section("content")
<div class="row">
    <div class="col-12 mt-4">
        <div class="card border-0 rounded shadow p-4">
            <form action="{{route('dashboard.products.store')}}" id="frmStore" autocomplete="off">
                @csrf
                <div class="row mb-3">
                    <div class="col-lg-12">
                        @if(in_array(Auth::user()->business->category->name,[\App\Enums\BusinessCategoryEnum::FNB]))
                        <div class="form-group row mb-3">
                            <label class="col-md-3 col-form-label">Foto Produk</label>
                            <div class="col-md-9">
                                <input type="file" class="form-control" name="image" accept="image/*">
                            </div>
                        </div>
                        @endif
                        <div class="form-group row mb-3">
                            <label class="col-md-3 col-form-label">
                                Kode 
                                @if(in_array(Auth::user()->business->category->name,[\App\Enums\BusinessCategoryEnum::BARANG,\App\Enums\BusinessCategoryEnum::FNB]))
                                    {{" Produk "}}
                                @elseif(in_array(Auth::user()->business->category->name,[\App\Enums\BusinessCategoryEnum::JASA]))
                                    {{" Jasa "}}
                                @elseif(in_array(Auth::user()->business->category->name,[\App\Enums\BusinessCategoryEnum::MIKROTIK]))
                                    {{" Mikrotik "}}
                                @endif
                                <span class="text-danger">*</span>
                            </label>
                            <div class="col-md-9">
                                <input type="text" class="form-control" name="code" placeholder="Kode Produk" value="{{old('code')}}" >
                            </div>
                        </div>
                        <div class="form-group row mb-3">
                            <label class="col-md-3 col-form-label">
                                Nama 
                                @if(in_array(Auth::user()->business->category->name,[\App\Enums\BusinessCategoryEnum::BARANG,\App\Enums\BusinessCategoryEnum::FNB]))
                                    {{" Produk "}}
                                @elseif(in_array(Auth::user()->business->category->name,[\App\Enums\BusinessCategoryEnum::JASA]))
                                    {{" Jasa "}}
                                @elseif(in_array(Auth::user()->business->category->name,[\App\Enums\BusinessCategoryEnum::MIKROTIK]))
                                    {{" Mikrotik "}}
                                @endif
                                <span class="text-danger">*</span>
                            </label>
                            <div class="col-md-9">
                                <input type="text" class="form-control" name="name" placeholder="Nama Produk" value="{{old('name')}}" >
                            </div>
                        </div>
                        <div class="form-group row mb-3">
                            <label class="col-md-3 col-form-label">
                                Harga 
                                @if(in_array(Auth::user()->business->category->name,[\App\Enums\BusinessCategoryEnum::BARANG,\App\Enums\BusinessCategoryEnum::FNB]))
                                    {{" Produk "}}
                                @elseif(in_array(Auth::user()->business->category->name,[\App\Enums\BusinessCategoryEnum::JASA]))
                                    {{" Jasa "}}
                                @elseif(in_array(Auth::user()->business->category->name,[\App\Enums\BusinessCategoryEnum::MIKROTIK]))
                                    {{" Mikrotik "}}
                                @endif
                                <span class="text-danger">*</span>
                            </label>
                            <div class="col-md-9">
                                <input type="number" class="form-control" name="price" placeholder="Harga Produk" value="{{old('price')}}" >
                            </div>
                        </div>
                        <div class="form-group row mb-3">
                            <label class="col-md-3 col-form-label">
                                Deskripsi 
                                @if(in_array(Auth::user()->business->category->name,[\App\Enums\BusinessCategoryEnum::BARANG,\App\Enums\BusinessCategoryEnum::FNB]))
                                    {{" Produk "}}
                                @elseif(in_array(Auth::user()->business->category->name,[\App\Enums\BusinessCategoryEnum::JASA]))
                                    {{" Jasa "}}
                                @elseif(in_array(Auth::user()->business->category->name,[\App\Enums\BusinessCategoryEnum::MIKROTIK]))
                                    {{" Mikrotik "}}
                                @endif
                            </label>
                            <div class="col-md-9">
                                <textarea class="form-control" rows="5" name="description">{{old('description')}}</textarea>
                            </div>
                        </div>
                        @if(in_array(Auth::user()->business->category->name,[\App\Enums\BusinessCategoryEnum::MIKROTIK]))
                        <div class="form-group row mb-3">
                            <label class="col-md-3 col-form-label">Tipe Mikrotik<span class="text-danger">*</span></label>
                            <div class="col-md-9">
                                <select class="form-control select2" name="mikrotik" >
                                    <option value="">==Pilih Tipe Mikrotik==</option>
                                    @foreach ($mikrotik as $index => $row)
                                    <option value="{{$index}}">{{$row}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        @endif
                        @if(in_array(Auth::user()->business->category->name,[\App\Enums\BusinessCategoryEnum::BARANG]))
                        <div class="form-group row mb-3">
                            <label class="col-md-3 col-form-label">
                                Berat Produk
                            </label>
                            <div class="col-md-9">
                                <div class="input-group">
                                    <input type="number" class="form-control" placeholder="Berat" name="weight" value="{{old('weight')}}">
                                    <div class="input-group-append">
                                        <div class="d-flex">
                                            <span class="input-group-text">GRAM</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endif
                        <div class="form-group row mb-3">
                            <label class="col-md-3 col-form-label">Status<span class="text-danger">*</span></label>
                            <div class="col-md-9">
                                <select class="form-control select2" name="status" >
                                    <option value="">==Pilih Status==</option>
                                    @foreach ($status as $index => $row)
                                    <option value="{{$index}}">{{$row}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        @if(in_array(Auth::user()->business->category->name,[\App\Enums\BusinessCategoryEnum::BARANG,\App\Enums\BusinessCategoryEnum::FNB,\App\Enums\BusinessCategoryEnum::MIKROTIK]))
                        <div class="form-group row mb-3">
                            <label class="col-md-3 col-form-label">Apakah Produk Stok ? <span class="text-danger">*</span></label>
                            <div class="col-md-9">
                                <select class="form-control select2" name="is_using_stock" >
                                    <option value="">==Pilih Dengan Stock / Tanpa Stock==</option>
                                    @foreach ($is_using_stock as $index => $row)
                                    <option value="{{$index}}">{{$row}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        @endif
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

        $(document).on('submit','#frmStore',function(e){
            e.preventDefault();
            if(confirm("Apakah anda yakin ingin menyimpan data ini ?")){
                $.ajax({
                    url : $("#frmStore").attr("action"),
                    method : "POST",
                    data : new FormData($('#frmStore')[0]),
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