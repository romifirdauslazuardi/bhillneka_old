@extends("dashboard.layouts.main")

@section("title","Customer Fee")

@section("css")
@endsection

@section("breadcumb")
<div class="d-md-flex justify-content-between align-items-center">
    <h5 class="mb-0">Customer Fee</h5>

    <nav aria-label="breadcrumb" class="d-inline-block mt-2 mt-sm-0">
        <ul class="breadcrumb bg-transparent rounded mb-0 p-0">
            <li class="breadcrumb-item text-capitalize"><a href="#">Customer Fee</a></li>
            <li class="breadcrumb-item text-capitalize active" aria-current="page">Edit</li>
        </ul>
    </nav>
</div>
@endsection

@section("content")
<div class="row">
    <div class="col-12 mt-4">
        <div class="card border-0 rounded shadow p-4">
            <form action="{{route('dashboard.settings.customer-fee.update',$result->id)}}" id="frmUpdate" autocomplete="off">
                @csrf
                @method("PUT")
                <div class="row mb-3">
                    <div class="col-lg-12">
                        <div class="form-group row mb-2">
                            <label class="col-md-3 col-form-label mb-1">Kurang Dari / Lebih Dari<span class="text-danger">*</span></label>
                            <div class="col-md-5 mb-1">
                                <select class="form-control select2" name="mark" >
                                    <option value="">==Pilih Kurang Dari / Lebih Dari==</option>
                                    @foreach ($mark as $index => $row)
                                    <option value="{{$index}}" @if($result->mark == $index) selected @endif>{{$row}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-4 mb-1">
                                <input type="text" class="form-control formatRupiah" name="limit" placeholder="Nominal" value="{{old('limit',number_format($result->limit,0,',','.'))}}">
                            </div>
                        </div>
                        <div class="form-group row mb-2">
                            <label class="col-md-3 col-form-label mb-1">Percentage / Fixed<span class="text-danger">*</span></label>
                            <div class="col-md-5 mb-1">
                                <select class="form-control select2" name="type" >
                                    <option value="">==Pilih Percentage / Fixed==</option>
                                    @foreach ($type as $index => $row)
                                    <option value="{{$index}}" @if($result->type == $index) selected @endif>{{$row}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-4 mb-1mb-1">
                                <input type="text" class="form-control" name="value" placeholder="Nilai" value="{{old('value',$result->value)}}">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-12">
                        <a href="{{route('dashboard.settings.customer-fee.index')}}" class="btn btn-warning btn-sm"><i class="fa fa-arrow-left"></i> Kembali</a>
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

        $(document).on("keyup",".formatRupiah",function(e){
            e.preventDefault();

            let val = $(this).val();

            $(this).val(formatRupiah(val,undefined));
            
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
                            responseSuccess(resp.message,"{{route('dashboard.settings.customer-fee.index')}}");
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