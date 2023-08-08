@extends("dashboard.layouts.main")

@section("title","News")

@section("css")
@endsection

@section("breadcumb")
<div class="d-md-flex justify-content-between align-items-center">
    <h5 class="mb-0">News</h5>

    <nav aria-label="breadcrumb" class="d-inline-block mt-2 mt-sm-0">
        <ul class="breadcrumb bg-transparent rounded mb-0 p-0">
            <li class="breadcrumb-item text-capitalize"><a href="#">News</a></li>
            <li class="breadcrumb-item text-capitalize active" aria-current="page">Edit</li>
        </ul>
    </nav>
</div>
@endsection

@section("content")
<div class="row">
    <div class="col-12 mt-4">
        <div class="card border-0 rounded shadow p-4">
            <form action="{{route('dashboard.news.update',$result->id)}}" id="frmUpdate" autocomplete="off">
                @csrf
                @method("PUT")
                <div class="row mb-3">
                    <div class="col-lg-12">
                        <div class="form-group row mb-3">
                            <label class="col-md-3 col-form-label">Judul <span class="text-danger">*</span></label>
                            <div class="col-md-9">
                                <input type="text" class="form-control" name="title" placeholder="Judul" value="{{old('title',$result->title)}}" >
                            </div>
                        </div>
                        <div class="form-group row mb-3">
                            <label class="col-md-3 col-form-label">Pesan <span class="text-danger">*</span></label>
                            <div class="col-md-9">
                                <textarea class="form-control" name="note" rows="5" placeholder="Pesan">{{old('note',$result->note)}}</textarea>
                            </div>
                        </div>
                        <div class="form-group row mb-3">
                            <label class="col-md-3 col-form-label">Customer<span class="text-danger">*</span></label>
                            <div class="col-md-9">
                                <select class="form-control select2 select-customer" name="customer_id[]" multiple>
                                    @foreach($users as $index => $row)
                                    <option value="{{$row->id}}" @if(in_array($row->id,$result->news_recipient()->get()->pluck("user_id")->toArray())) selected @endif>{{$row->name}} - {{$row->phone}}</option>
                                    @endforeach
                                </select>
                                <p style="margin-top: 10px;margin-bottom: 0px;padding-top: 0px;padding-bottom: 0px;"><input type="checkbox" class="checkbox-customer"> Pilih Semua</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-12">
                        <a href="{{route('dashboard.news.index')}}" class="btn btn-warning btn-sm"><i class="fa fa-arrow-left"></i> Kembali</a>
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

        $(document).on("change",".checkbox-customer",function(e){
            if($(this).is(":checked")){
                $(".select-customer > option").prop("selected", false).trigger("change");
                $(".select-customer > option").prop("selected", true).trigger("change");
            }
            else{
                $(".select-customer > option").prop("selected", false).trigger("change");
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
                            return responseFailed(resp.message);
                        }
                        else{
                            return responseSuccess(resp.message,"{{route('dashboard.news.index')}}");
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
