@extends('dashboard.layouts.main')

@section('title', 'Product Categories')

@section('css')
@endsection

@section('breadcumb')
    <div class="row">
        <div class="col-sm-12">
            <h3 class="page-title">Produk Kategori</h3>
            <ul class="breadcrumb">
                <li class="breadcrumb-item"><a href="#">Produk Kategori</a></li>
                <li class="breadcrumb-item active">Daftar Produk Kategori</li>
            </ul>
        </div>
    </div>
@endsection

@section('content')
    <div class="row">
        <div class="col-12 mt-4">
            <div class="card border-0 rounded shadow p-4">
                <form action="{{ route('dashboard.product-categories.store') }}" id="frmStore" autocomplete="off">
                    @csrf
                    <div class="row mb-3">
                        <div class="col-12">
                            <div class="form-group">
                                <label>Category Name<span class="text-danger">*</span></label>
                                <input name="name" placeholder="Category Name" value="{{ old('name') }}" type="text"
                                    class="form-control">
                            </div>
                        </div>
                        <div class="col-lg-12">
                            <div class="form-group">
                                <label> Product Image</label>
                                <div class="image-upload">
                                    <input type="file" name="image">
                                    <div class="image-uploads">
                                        <img src="{{ asset('assets/dreampos/assets/img/icons/upload.svg') }}"
                                            alt="img">
                                        <h4>Drag and drop a file to upload</h4>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-12">
                            <a href="{{ route('dashboard.product-categories.index') }}" class="btn btn-cancel btn-sm"><i
                                    class="fa fa-arrow-left"></i> Kembali</a>
                            <button type="submit" class="btn btn-submit btn-sm" disabled><i class="fa fa-save"></i>
                                Simpan</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>


    @include('dashboard.product-categories.modal.index')
    @include('dashboard.components.loader')
@endsection
@section('script')
    <script>
        $('button[type="submit"]').attr("disabled", false);
        $(document).on('submit', '#frmStore', function(e) {
            e.preventDefault();
            if (confirm("Apakah anda yakin ingin menyimpan data ini ?")) {
                $.ajax({
                    url: $("#frmStore").attr("action"),
                    method: "POST",
                    data: new FormData($('#frmStore')[0]),
                    contentType: false,
                    cache: false,
                    processData: false,
                    dataType: "JSON",
                    beforeSend: function() {
                        return openLoader();
                    },
                    success: function(resp) {
                        if (resp.success == false) {
                            responseFailed(resp.message);
                        } else {
                            responseSuccess(resp.message,
                                "{{ route('dashboard.product-categories.index') }}");
                        }
                    },
                    error: function(request, status, error) {
                        if (request.status == 422) {
                            responseFailed(request.responseJSON.message);
                        } else {
                            responseInternalServerError();
                        }
                    },
                    complete: function() {
                        return closeLoader();
                    }
                })
            }
        })
    </script>
@endsection
