@extends("dashboard.layouts.main")

@section("title","News")

@section("css")
@endsection

@section("breadcumb")
<div class="row">
    <div class="col-sm-12">
        <h3 class="page-title">News</h3>
        <ul class="breadcrumb">
            <li class="breadcrumb-item"><a href="#">News</a></li>
            <li class="breadcrumb-item active">Create</li>
        </ul>
    </div>
</div>
@endsection

@section("content")
<div class="row">
    <div class="col-12 mt-4">
        <div class="card border-0 rounded shadow p-4">
            <form action="{{route('dashboard.news.store')}}" id="frmStore" autocomplete="off">
                @csrf
                <div class="row mb-3">
                    <div class="col-lg-12">
                        <div class="form-group row mb-3">
                            <label class="col-md-3 col-form-label">Judul <span class="text-danger">*</span></label>
                            <div class="col-md-9">
                                <input type="text" class="form-control" name="title" placeholder="Judul" value="{{old('title')}}" >
                            </div>
                        </div>
                        <div class="form-group row mb-3">
                            <label class="col-md-3 col-form-label">Pesan <span class="text-danger">*</span></label>
                            <div class="col-md-9">
                                <textarea class="form-control" name="note" rows="5" placeholder="Pesan">{{old('note')}}</textarea>
                            </div>
                        </div>
                        <div class="form-group row mb-3">
                            <label class="col-md-3 col-form-label">Customer<span class="text-danger">*</span></label>
                            <div class="col-md-9">
                                <select class="form-control select2 select-customer" name="customer_id[]" multiple>
                                    @foreach($users as $index => $row)
                                    <option value="{{$row->id}}">{{$row->name}} - {{$row->phone}}</option>
                                    @endforeach
                                </select>
                                <p style="margin-top: 10px;margin-bottom: 0px;padding-top: 0px;padding-bottom: 0px;"><input type="checkbox" class="checkbox-customer"> Pilih Semua</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-12">
                        <a href="{{ route('dashboard.news.index') }}" class="btn btn-cancel btn-sm"><i
                                class="fa fa-arrow-left"></i> Kembali</a>
                        <button type="submit" class="btn btn-submit btn-sm" disabled><i class="fa fa-save"></i>
                            Simpan</button>
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
                            responseSuccess(resp.message,"{{route('dashboard.news.index')}}");
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
