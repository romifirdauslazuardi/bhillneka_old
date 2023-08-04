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
                            <div class="row mb-3">
                                <div class="col-lg-12">
                                    <div class="form-group row mb-3">
                                        <label class="col-md-3 col-form-label">
                                            Foto
                                            @if(in_array($result->business->category->name,[\App\Enums\BusinessCategoryEnum::BARANG,\App\Enums\BusinessCategoryEnum::FNB]))
                                                {{" Produk "}}
                                            @elseif(in_array($result->business->category->name,[\App\Enums\BusinessCategoryEnum::JASA]))
                                                {{" Jasa "}}
                                            @elseif(in_array($result->business->category->name,[\App\Enums\BusinessCategoryEnum::MIKROTIK]))
                                                {{" Mikrotik "}}
                                            @endif
                                        </label>
                                        <div class="col-md-9">
                                            <input type="file" class="form-control" name="image" accept="image/*">
                                            <p class="text-info" style="margin-top: 0px;margin-bottom: 0px;padding-top: 0px;padding-bottom: 0px;"><small><i>Kosongkan jika tidak diubah</i></small></p>
                                        </div>
                                    </div>
                                    <div class="form-group row mb-3">
                                        <label class="col-md-3 col-form-label">
                                            Kode 
                                            @if(in_array($result->business->category->name,[\App\Enums\BusinessCategoryEnum::BARANG,\App\Enums\BusinessCategoryEnum::FNB]))
                                                {{" Produk "}}
                                            @elseif(in_array($result->business->category->name,[\App\Enums\BusinessCategoryEnum::JASA]))
                                                {{" Jasa "}}
                                            @elseif(in_array($result->business->category->name,[\App\Enums\BusinessCategoryEnum::MIKROTIK]))
                                                {{" Mikrotik "}}
                                            @endif
                                            <span class="text-danger">*</span>
                                        </label>
                                        <div class="col-md-9">
                                            <input type="text" class="form-control" name="code" placeholder="Kode Produk" value="{{old('code',$result->code)}}" >
                                        </div>
                                    </div>
                                    <div class="form-group row mb-3">
                                        <label class="col-md-3 col-form-label">
                                            Nama 
                                            @if(in_array($result->business->category->name,[\App\Enums\BusinessCategoryEnum::BARANG,\App\Enums\BusinessCategoryEnum::FNB]))
                                                {{" Produk "}}
                                            @elseif(in_array($result->business->category->name,[\App\Enums\BusinessCategoryEnum::JASA]))
                                                {{" Jasa "}}
                                            @elseif(in_array($result->business->category->name,[\App\Enums\BusinessCategoryEnum::MIKROTIK]))
                                                {{" Mikrotik "}}
                                            @endif
                                            <span class="text-danger">*</span>
                                        </label>
                                        <div class="col-md-9">
                                            <input type="text" class="form-control" name="name" placeholder="Nama Produk" value="{{old('name',$result->name)}}" >
                                        </div>
                                    </div>
                                    <div class="form-group row mb-3">
                                        <label class="col-md-3 col-form-label">
                                            Harga 
                                            @if(in_array($result->business->category->name,[\App\Enums\BusinessCategoryEnum::BARANG,\App\Enums\BusinessCategoryEnum::FNB]))
                                                {{" Produk "}}
                                            @elseif(in_array($result->business->category->name,[\App\Enums\BusinessCategoryEnum::JASA]))
                                                {{" Jasa "}}
                                            @elseif(in_array($result->business->category->name,[\App\Enums\BusinessCategoryEnum::MIKROTIK]))
                                                {{" Mikrotik "}}
                                            @endif
                                            <span class="text-danger">*</span>
                                        </label>
                                        <div class="col-md-9">
                                            <input type="number" class="form-control" name="price" placeholder="Harga Produk" value="{{old('price',$result->price)}}" >
                                        </div>
                                    </div>
                                    <div class="form-group row mb-3">
                                        <label class="col-md-3 col-form-label">
                                            Deskripsi 
                                            @if(in_array($result->business->category->name,[\App\Enums\BusinessCategoryEnum::BARANG,\App\Enums\BusinessCategoryEnum::FNB]))
                                                {{" Produk "}}
                                            @elseif(in_array($result->business->category->name,[\App\Enums\BusinessCategoryEnum::JASA]))
                                                {{" Jasa "}}
                                            @elseif(in_array($result->business->category->name,[\App\Enums\BusinessCategoryEnum::MIKROTIK]))
                                                {{" Mikrotik "}}
                                            @endif
                                        </label>
                                        <div class="col-md-9">
                                            <textarea class="form-control" rows="5" name="description">{{old('description',$result->description)}}</textarea>
                                        </div>
                                    </div>
                                    @if(in_array($result->business->category->name,[\App\Enums\BusinessCategoryEnum::MIKROTIK]))
                                    <div class="form-group row mb-3">
                                        <label class="col-md-3 col-form-label">Tipe Mikrotik<span class="text-danger">*</span></label>
                                        <div class="col-md-9">
                                            <select class="form-control select2" name="mikrotik" >
                                                <option value="">==Pilih Tipe Mikrotik==</option>
                                                @foreach ($mikrotik as $index => $row)
                                                <option value="{{$index}}" @if($index == $result->mikrotik) selected @endif>{{$row}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    @endif
                                    @if(in_array($result->business->category->name,[\App\Enums\BusinessCategoryEnum::BARANG]))
                                    <div class="form-group row mb-3">
                                        <label class="col-md-3 col-form-label">
                                            Berat Produk
                                        </label>
                                        <div class="col-md-9">
                                            <div class="input-group">
                                                <input type="number" class="form-control" placeholder="Berat" name="weight" value="{{old('weight',$result->weight)}}">
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
                                                <option value="{{$index}}" @if($index == $result->status) selected @endif>{{$row}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    @if(in_array($result->business->category->name,[\App\Enums\BusinessCategoryEnum::BARANG,\App\Enums\BusinessCategoryEnum::FNB,\App\Enums\BusinessCategoryEnum::MIKROTIK,\App\Enums\BusinessCategoryEnum::MIKROTIK]))
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
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                    @if(in_array(Auth::user()->business->category->name,[\App\Enums\BusinessCategoryEnum::MIKROTIK]))
                    <div class="tab-pane container fade" id="configuration">
                        <div class="row mt-3">
                            <div class="col-12">
                                <div class="form-group row mb-3 @if(!in_array($result->mikrotik,[\App\Enums\ProductEnum::MIKROTIK_PPPOE])) d-none @endif display-service">
                                    <label class="col-md-3 col-form-label">Service</label>
                                    <div class="col-md-9">
                                        <select class="form-control service select-service" style="width:100%" name="service">
                                            <option value="pppoe">PPPOE</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group row mb-3 @if(!in_array($result->mikrotik,[\App\Enums\ProductEnum::MIKROTIK_HOTSPOT])) d-none @endif   display-server">
                                    <label class="col-md-3 col-form-label">Server</label>
                                    <div class="col-md-9">
                                        <select class="form-control server select-server" style="width:100%" name="server">
                                            <option value="">==Pilih Server==</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group row mb-3 @if(!in_array($result->mikrotik,[\App\Enums\ProductEnum::MIKROTIK_PPPOE,\App\Enums\ProductEnum::MIKROTIK_HOTSPOT])) d-none @endif  display-profile">
                                    <label class="col-md-3 col-form-label">Profile</label>
                                    <div class="col-md-9">
                                        <select class="form-control profile select-profile" style="width:100%" name="profile">
                                            <option value="">==Pilih Profile==</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group row mb-3 @if(!in_array($result->mikrotik,[\App\Enums\ProductEnum::MIKROTIK_HOTSPOT])) d-none @endif display-address">
                                    <label class="col-md-3 col-form-label">Address</label>
                                    <div class="col-md-9">
                                        <input type="text" class="form-control address" name="address" placeholder="Address" value="{{$result->address}}">
                                    </div>
                                </div>
                                <div class="form-group row mb-3 @if(!in_array($result->mikrotik,[\App\Enums\ProductEnum::MIKROTIK_HOTSPOT])) d-none @endif display-mac-address">
                                    <label class="col-md-3 col-form-label">Mac Address</label>
                                    <div class="col-md-9">
                                        <input type="text" class="form-control mac-address" name="mac_address" placeholder="Mac Address" value="{{$result->mac_address}}">
                                    </div>
                                </div>
                                <div class="form-group row mb-3 @if(!in_array($result->mikrotik,[\App\Enums\ProductEnum::MIKROTIK_HOTSPOT])) d-none @endif display-time-limit">
                                    <label class="col-md-3 col-form-label">Time Limit</label>
                                    <div class="col-md-9">
                                        <input type="text" class="form-control time-limit" name="time_limit" placeholder="Contoh : 1d4h30m20s" value="{{$result->time_limit}}">
                                    </div>
                                </div>
                                <div class="form-group row mb-3 @if(!in_array($result->mikrotik,[\App\Enums\ProductEnum::MIKROTIK_PPPOE])) d-none @endif display-local-address">
                                    <label class="col-md-3 col-form-label">Local Address</label>
                                    <div class="col-md-9">
                                        <input type="text" class="form-control local-address" name="local_address" placeholder="Local Address" value="{{$result->local_address}}">
                                    </div>
                                </div>
                                <div class="form-group row mb-3 @if(!in_array($result->mikrotik,[\App\Enums\ProductEnum::MIKROTIK_PPPOE])) d-none @endif display-remote-address">
                                    <label class="col-md-3 col-form-label">Remote Address</label>
                                    <div class="col-md-9">
                                        <input type="text" class="form-control remote-address" name="remote_address" placeholder="Remote Address" value="{{$result->remote_address}}">
                                    </div>
                                </div>
                                <div class="form-group row mb-3 @if(!in_array($result->mikrotik,[\App\Enums\ProductEnum::MIKROTIK_PPPOE])) d-none @endif display-expired-date">
                                    <label class="col-md-3 col-form-label">Berlaku Sampai Tanggal</label>
                                    <div class="col-md-9">
                                        <input type="date" class="form-control expired-date" name="expired_date" placeholder="Berlaku Sampai Tanggal" value="{{$result->expired_date}}">
                                    </div>
                                </div>
                                <div class="form-group row mb-3 @if(!in_array($result->mikrotik,[\App\Enums\ProductEnum::MIKROTIK_PPPOE,\App\Enums\ProductEnum::MIKROTIK_HOTSPOT])) d-none @endif display-comment">
                                    <label class="col-md-3 col-form-label">Comment</label>
                                    <div class="col-md-9">
                                        <input type="text" class="form-control comment" name="comment" placeholder="Comment" value="{{$result->comment}}">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif
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

        @if(in_array($result->mikrotik,[\App\Enums\ProductEnum::MIKROTIK_PPPOE]))
            getProfilePppoe(".select-profile",'{{$result->profile}}');
        @endif

        @if(in_array($result->mikrotik,[\App\Enums\ProductEnum::MIKROTIK_HOTSPOT]))
            getServerHotspot(".select-server",'{{$result->server}}');
            getProfileHotspot(".select-profile",'{{$result->profile}}');
        @endif

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