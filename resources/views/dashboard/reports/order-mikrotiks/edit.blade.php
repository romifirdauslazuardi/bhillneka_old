@extends("dashboard.layouts.main")

@section("title","Order Mikrotik")

@section("css")
@endsection

@section("breadcumb")
<div class="d-md-flex justify-content-between align-items-center">
    <h5 class="mb-0">Order Mikrotik</h5>

    <nav aria-label="breadcrumb" class="d-inline-block mt-2 mt-sm-0">
        <ul class="breadcrumb bg-transparent rounded mb-0 p-0">
            <li class="breadcrumb-item text-capitalize"><a href="#">Order Mikrotik</a></li>
            <li class="breadcrumb-item text-capitalize active" aria-current="page">Create</li>
        </ul>
    </nav>
</div>
@endsection

@section("content")
<div class="row">
    <div class="col-12 mt-4">
        <div class="card border-0 rounded shadow p-4">
            <form action="{{route('dashboard.reports.order-mikrotiks.update',$result->id)}}" id="frmUpdate" autocomplete="off">
                @csrf
                @method("PUT")
                <div class="row mb-3">
                    <div class="col-lg-12">
                        <div class="form-group row mb-3">
                            <label class="col-md-3 col-form-label">Username <span class="text-danger">*</span></label>
                            <div class="col-md-9">
                                <input type="text" class="form-control" placeholder="Username" value="{{$result->username}}" name="username">
                            </div>
                        </div>
                        <div class="form-group row mb-3">
                            <label class="col-md-3 col-form-label">Password</label>
                            <div class="col-md-9">
                                <input type="text" class="form-control" name="password" placeholder="Password" value="{{old('password',$result->password)}}" >
                            </div>
                        </div>
                        @if($result->type == \App\Enums\OrderMikrotikEnum::TYPE_PPPOE)
                        <div class="form-group row mb-3">
                            <label class="col-md-3 col-form-label">Service<span class="text-danger">*</span></label>
                            <div class="col-md-9">
                                <select class="form-control" name="service" >
                                    <option value="pppoe">PPPOE</option>
                                </select>
                            </div>
                        </div>
                        @endif
                        @if($result->type == \App\Enums\OrderMikrotikEnum::TYPE_HOTSPOT)
                        <div class="form-group row mb-3">
                            <label class="col-md-3 col-form-label">Server<span class="text-danger">*</span></label>
                            <div class="col-md-9">
                                <select class="form-control select-server" name="server" >
                                    <option value="">==Pilih Server==</option>
                                </select>
                            </div>
                        </div>
                        @endif
                        <div class="form-group row mb-3">
                            <label class="col-md-3 col-form-label">Profile<span class="text-danger">*</span></label>
                            <div class="col-md-9">
                                <select class="form-control select-profile" name="profile" >
                                    <option value="">==Pilih Profile==</option>
                                </select>
                            </div>
                        </div>
                        @if($result->type == \App\Enums\OrderMikrotikEnum::TYPE_PPPOE)
                            <div class="form-group row mb-3">
                                <label class="col-md-3 col-form-label">Local Address<span class="text-danger">*</span></label>
                                <div class="col-md-9">
                                    <input type="text" class="form-control" name="local_address" placeholder="Local Address" value="{{old('local_address',$result->local_address)}}" >
                                </div>
                            </div>
                            <div class="form-group row mb-3">
                                <label class="col-md-3 col-form-label">Remote Address<span class="text-danger">*</span></label>
                                <div class="col-md-9">
                                    <input type="text" class="form-control" name="remote_address" placeholder="Remote Address" value="{{old('remote_address',$result->remote_address)}}" >
                                </div>
                            </div>
                            @if($result->order_item->order->type == \App\Enums\OrderEnum::TYPE_ON_TIME_PAY)
                            <div class="form-group row mb-3">
                                <label class="col-md-3 col-form-label">Berakhir Pada</label>
                                <div class="col-md-9">
                                    <input type="date" class="form-control" name="expired_date" placeholder="Berakhir Pada" value="{{old('expired_date',$result->expired_date)}}" >
                                </div>
                            </div>
                            @endif
                        @endif
                        @if($result->type == \App\Enums\OrderMikrotikEnum::TYPE_HOTSPOT)
                        <div class="form-group row mb-3">
                            <label class="col-md-3 col-form-label">Address</label>
                            <div class="col-md-9">
                                <input type="text" class="form-control" name="address" placeholder="Address" value="{{old('address',$result->address)}}" >
                            </div>
                        </div>
                        <div class="form-group row mb-3">
                            <label class="col-md-3 col-form-label">Mac Address</label>
                            <div class="col-md-9">
                                <input type="text" class="form-control" name="mac_address" placeholder="Mac Address" value="{{old('mac_address',$result->mac_address)}}" >
                            </div>
                        </div>
                        <div class="form-group row mb-3">
                            <label class="col-md-3 col-form-label">Time Limit<span class="text-danger">*</span></label>
                            <div class="col-md-9">
                                <input type="text" class="form-control" name="time_limit" placeholder="Contoh : 1d4h30m20s" value="{{old('time_limit',$result->time_limit)}}" >
                            </div>
                        </div>
                        @endif
                        <div class="form-group row mb-3">
                            <label class="col-md-3 col-form-label">Comment</label>
                            <div class="col-md-9">
                                <input type="text" class="form-control" name="comment" placeholder="Comment" value="{{old('comment',$result->comment)}}" >
                            </div>
                        </div>
                        <div class="form-group row mb-3">
                            <label class="col-md-3 col-form-label">Disabled<span class="text-danger">*</span></label>
                            <div class="col-md-9">
                                <select class="form-control disabled" name="disabled" style="width:100%">
                                    <option value="yes" @if($result->disabled == "yes") selected @endif>Disabled</option>
                                    <option value="no" @if($result->disabled == "no") selected @endif>Enabled</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-12">
                        <a href="{{route('dashboard.reports.order-mikrotiks.index')}}" class="btn btn-warning btn-sm"><i class="fa fa-arrow-left"></i> Kembali</a>
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

        @if($result->type == \App\Enums\OrderMikrotikEnum::TYPE_PPPOE)
            getProfilePppoe('.select-profile','{{$result->profile}}');
        @else
            getProfileHotspot('.select-profile','{{$result->profile}}');
            getServerHotspot('.select-server','{{$result->server}}');
        @endif

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
                            responseSuccess(resp.message,"{{route('dashboard.reports.order-mikrotiks.index')}}");
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