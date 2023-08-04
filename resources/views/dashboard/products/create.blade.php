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
                    <div class="col-12">
                        <!-- Nav tabs -->
                        <ul class="nav nav-tabs">
                            <li class="nav-item">
                                <a class="nav-link active" data-bs-toggle="tab" href="#product">Data Produk</a>
                            </li>

                            @if(in_array(Auth::user()->business->category->name,[\App\Enums\BusinessCategoryEnum::MIKROTIK]))
                            <li class="nav-item">
                                <a class="nav-link" data-bs-toggle="tab" href="#configuration">Konfigurasi User Mikrotik</a>
                            </li>
                            @endif
                        </ul>

                        <!-- Tab panes -->
                        <div class="tab-content">
                            <div class="tab-pane container active" id="product">
                                <div class="row mt-3">
                                    <div class="col-lg-12">
                                        <div class="form-group row mb-3">
                                            <label class="col-md-3 col-form-label">
                                                Foto
                                                @if(in_array(Auth::user()->business->category->name,[\App\Enums\BusinessCategoryEnum::BARANG,\App\Enums\BusinessCategoryEnum::FNB]))
                                                    {{" Produk "}}
                                                @elseif(in_array(Auth::user()->business->category->name,[\App\Enums\BusinessCategoryEnum::JASA]))
                                                    {{" Jasa "}}
                                                @elseif(in_array(Auth::user()->business->category->name,[\App\Enums\BusinessCategoryEnum::MIKROTIK]))
                                                    {{" Mikrotik "}}
                                                @endif
                                            </label>
                                            <div class="col-md-9">
                                                <input type="file" class="form-control" name="image" accept="image/*">
                                            </div>
                                        </div>
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
                                                <select class="form-control select2 select-mikrotik" name="mikrotik" >
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
                                                <select class="form-control select2 select-is-using-stock" name="is_using_stock" >
                                                    <option value="">==Pilih Dengan Stock / Tanpa Stock==</option>
                                                    @foreach ($is_using_stock as $index => $row)
                                                    <option value="{{$index}}">{{$row}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="form-group row mb-3 display-initial-stock d-none">
                                            <label class="col-md-3 col-form-label">
                                                Stok Awal <span class="text-danger">*</span>
                                            </label>
                                            <div class="col-md-9">
                                                <input type="number" class="form-control" name="qty" placeholder="Stok Awal" value="{{old('qty')}}" >
                                            </div>
                                        </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            @if(in_array(Auth::user()->business->category->name,[\App\Enums\BusinessCategoryEnum::MIKROTIK]))
                            <div class="tab-pane container fade" id="configuration">
                                <div class="row mt-3">
                                    <div class="col-12">
                                        <div class="form-group row mb-3 d-none display-service">
                                            <label class="col-md-3 col-form-label">Service</label>
                                            <div class="col-md-9">
                                                <select class="form-control service select-service" style="width:100%" name="service">
                                                    <option value="pppoe">PPPOE</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="form-group row mb-3 d-none display-server">
                                            <label class="col-md-3 col-form-label">Server</label>
                                            <div class="col-md-9">
                                                <select class="form-control server select-server" style="width:100%" name="server">
                                                    <option value="">==Pilih Server==</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="form-group row mb-3 d-none display-profile">
                                            <label class="col-md-3 col-form-label">Profile</label>
                                            <div class="col-md-9">
                                                <select class="form-control profile select-profile" style="width:100%" name="profile">
                                                    <option value="">==Pilih Profile==</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="form-group row mb-3 d-none display-address">
                                            <label class="col-md-3 col-form-label">Address</label>
                                            <div class="col-md-9">
                                                <input type="text" class="form-control address" name="address" placeholder="Address">
                                            </div>
                                        </div>
                                        <div class="form-group row mb-3 d-none display-mac-address">
                                            <label class="col-md-3 col-form-label">Mac Address</label>
                                            <div class="col-md-9">
                                                <input type="text" class="form-control mac-address" name="mac_address" placeholder="Mac Address">
                                            </div>
                                        </div>
                                        <div class="form-group row mb-3 d-none display-time-limit">
                                            <label class="col-md-3 col-form-label">Time Limit</label>
                                            <div class="col-md-9">
                                                <input type="text" class="form-control time-limit" name="time_limit" placeholder="Contoh : 1d4h30m20s">
                                            </div>
                                        </div>
                                        <div class="form-group row mb-3 d-none display-local-address">
                                            <label class="col-md-3 col-form-label">Local Address</label>
                                            <div class="col-md-9">
                                                <input type="text" class="form-control local-address" name="local_address" placeholder="Local Address">
                                            </div>
                                        </div>
                                        <div class="form-group row mb-3 d-none display-remote-address">
                                            <label class="col-md-3 col-form-label">Remote Address</label>
                                            <div class="col-md-9">
                                                <input type="text" class="form-control remote-address" name="remote_address" placeholder="Remote Address">
                                            </div>
                                        </div>
                                        <div class="form-group row mb-3 d-none display-expired-date">
                                            <label class="col-md-3 col-form-label">Berlaku Sampai Tanggal</label>
                                            <div class="col-md-9">
                                                <input type="date" class="form-control expired-date" name="expired_date" placeholder="Berlaku Sampai Tanggal">
                                            </div>
                                        </div>
                                        <div class="form-group row mb-3 d-none display-comment">
                                            <label class="col-md-3 col-form-label">Comment</label>
                                            <div class="col-md-9">
                                                <input type="text" class="form-control comment" name="comment" placeholder="Comment">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endif
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

        $(document).on("change",".select-mikrotik",function(e){
            e.preventDefault();

            let val = $(this).val();

            if(val != "" && val != undefined && val != null){
                if(val == '{{App\Enums\ProductEnum::MIKROTIK_PPPOE}}'){
                    $('.display-server').removeClass("d-none").addClass("d-none");
                    $('.display-address').removeClass("d-none").addClass("d-none");
                    $('.display-mac-address').removeClass("d-none").addClass("d-none");
                    $('.display-time-limit').removeClass("d-none").addClass("d-none");
                    $('.display-profile').removeClass("d-none").addClass("d-none");
                    $('.display-comment').removeClass("d-none").addClass("d-none");

                    $('.display-service').removeClass("d-none");
                    $('.display-profile').removeClass("d-none");
                    $('.display-local-address').removeClass("d-none");
                    $('.display-remote-address').removeClass("d-none");
                    $('.display-expired-date').removeClass("d-none");
                    $('.display-comment').removeClass("d-none");

                    $('.select-server').html('<option value="">==Pilih Server==</option>');
                    $('.select-profile').html('<option value="">==Pilih Profile==</option>');

                    getProfilePppoe('.select-profile');
                }else{
                    $('.display-service').removeClass("d-none").addClass("d-none");
                    $('.display-profile').removeClass("d-none").addClass("d-none");
                    $('.display-local-address').removeClass("d-none").addClass("d-none");
                    $('.display-remote-address').removeClass("d-none").addClass("d-none");
                    $('.display-expired-date').removeClass("d-none").addClass("d-none");
                    $('.display-comment').removeClass("d-none").addClass("d-none");

                    $('.display-server').removeClass("d-none");
                    $('.display-profile').removeClass("d-none");
                    $('.display-address').removeClass("d-none");
                    $('.display-mac-address').removeClass("d-none");
                    $('.display-time-limit').removeClass("d-none");
                    $('.display-comment').removeClass("d-none");

                    $('.select-server').html('<option value="">==Pilih Server==</option>');
                    $('.select-profile').html('<option value="">==Pilih Profile==</option>');

                    getServerHotspot('.select-server');
                    getProfileHotspot('.select-profile');
                }
            }
            else{
                $('.display-server').removeClass("d-none").addClass("d-none");
                $('.display-address').removeClass("d-none").addClass("d-none");
                $('.display-mac-address').removeClass("d-none").addClass("d-none");
                $('.display-time-limit').removeClass("d-none").addClass("d-none");
                $('.display-profile').removeClass("d-none").addClass("d-none");
                $('.display-comment').removeClass("d-none").addClass("d-none");
                $('.display-service').removeClass("d-none").addClass("d-none");
                $('.display-local-address').removeClass("d-none").addClass("d-none");
                $('.display-remote-address').removeClass("d-none").addClass("d-none");
                $('.display-expired-date').removeClass("d-none").addClass("d-none");
            }
        });

        $(document).on("change",".select-is-using-stock",function(e){
            e.preventDefault();

            let val = $(this).val();

            if(val != null && val != "" && val != undefined){
                if(val == '{{\App\Enums\ProductEnum::IS_USING_STOCK_TRUE}}'){
                    $('.display-initial-stock').removeClass("d-none");
                }
                else{
                    $('.display-initial-stock').removeClass("d-none").addClass("d-none");
                }
            }
            else{
                $('.display-initial-stock').removeClass("d-none").addClass("d-none");
            }
        });

        $(document).on("change",".select-profile",function(e){
            e.preventDefault();

            let $this = $(this);
            let val = $this.val();

            if($('.select-mikrotik').val() == '{{App\Enums\ProductEnum::MIKROTIK_PPPOE}}')
            {
                if(val != "" && val != null && val != undefined){
                    $.ajax({
                        url : '{{route("base.mikrotik-configs.detailProfilePppoe","_name_")}}'.replace("_name_", val),
                        method : "GET",
                        dataType : "JSON",
                        beforeSend : function(){
                            return openLoader();
                        },
                        success : function(resp){
                            if(resp.success == false){
                                responseFailed(resp.message);         
                            }
                            else{
                                $this.parent().parent().parent().find(".local-address").val(resp.data.local_address);
                                $this.parent().parent().parent().find(".remote-address").val(resp.data.remote_address);
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
            }
        });

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