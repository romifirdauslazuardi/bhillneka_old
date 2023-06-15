@extends("dashboard.layouts.main")

@section("title","Transaksi")

@section("css")
@endsection

@section("breadcumb")
<div class="d-md-flex justify-content-between align-items-center">
    <h5 class="mb-0">Transaksi</h5>

    <nav aria-label="breadcrumb" class="d-inline-block mt-2 mt-sm-0">
        <ul class="breadcrumb bg-transparent rounded mb-0 p-0">
            <li class="breadcrumb-item text-capitalize"><a href="#">Transaksi</a></li>
            <li class="breadcrumb-item text-capitalize active" aria-current="page">Show</li>
        </ul>
    </nav>
</div>
@endsection

@section("content")
<div class="row">
    <div class="col-12 mt-4">
            <form action="#" id="frmUpdate" autocomplete="off">
                <div class="row mb-3">
                    <div class="col-md-12 mb-3">
                        <div class="card border-0 rounded shadow p-4">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group row">
                                        <label class="col-md-3 col-form-label">Fee Owner</label>
                                        <div class="col-md-9">
                                            <div class="input-group">
                                                <input type="text" class="form-control" placeholder="Fee Owner" value="{{$result->owner_fee}}" readonly disabled>
                                                <button class="input-group-text btn btn-secondary" type="button" disabled>%</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group row">
                                        <label class="col-md-3 col-form-label">Fee Agen</label>
                                        <div class="col-md-9">
                                            <div class="input-group">
                                                <input type="text" class="form-control" placeholder="Fee Owner" value="{{$result->agen_fee}}" readonly disabled>
                                                <button class="input-group-text btn btn-secondary" type="button" disabled>%</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <div class="card border-0 rounded shadow p-4">
                            <div class="form-group row mb-3">
                                <label class="col-md-5 col-form-label">Kode Transaksi </label>
                                <div class="col-md-7">
                                    <input type="text" class="form-control" placeholder="Kode Transaksi" value="{{$result->code}}" readonly disabled>
                                </div>
                            </div>
                            <div class="form-group row mb-3">
                                <label class="col-md-5 col-form-label">Tanggal </label>
                                <div class="col-md-7">
                                    <input type="text" class="form-control" placeholder="Tanggal" value="{{$result->created_at}}" readonly disabled>
                                </div>
                            </div>
                            <div class="form-group row mb-3">
                                <label class="col-md-5 col-form-label">Customer</label>
                                <div class="col-md-7">
                                    <input type="text" class="form-control" value="{{!empty($result->customer->name) ? $result->customer->name : 'Umum'}}" readonly disabled>
                                </div>
                            </div>
                            <div class="form-group row mb-3">
                                <label class="col-md-5 col-form-label">Pengguna</label>
                                <div class="col-md-7">
                                    <input type="text" class="form-control" value="{{$result->user->name ?? null}}" readonly disabled>
                                </div>
                            </div>
                            <div class="form-group row mb-3">
                                <label class="col-md-5 col-form-label">Author</label>
                                <div class="col-md-7">
                                    <input type="text" class="form-control" value="{{$result->author->name ?? null}}" readonly disabled>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <div class="card border-0 rounded shadow p-4">
                            <h5 class="card-title"><b>Grand Total</b></h5>
                            <h1><b class="text-total">{{number_format($result->totalNeto(),0,',','.')}}</b></h1>
                        </div>
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-12">
                        <div class="card border-0 rounded shadow p-4">
                            <h6 class="card-title">Daftar Produk</h6>
                            <div class="table-responsive">
                                <div class="table">
                                    <table class="table table-striped table-bordered">
                                        <thead>
                                            <th>No</th>
                                            <th>Kode Produk</th>
                                            <th>Nama Produk</th>
                                            <th>Harga</th>
                                            <th>Quantity</th>
                                            <th>Diskon</th>
                                            <th>Total</th>
                                        </thead>
                                        <tbody class="tbody-product">
                                            @foreach($result->items as $index => $row)
                                            <tr>
                                                <td>{{$index+1}}</td>
                                                <td>{{$row->product_code}}</td>
                                                <td>{{$row->product_name}}</td>
                                                <td>{{number_format($row->product_price,0,',','.')}}</td>
                                                <td>{{$row->qty}}</td>
                                                <td>{{number_format($row->discount,0,',','.')}}</td>
                                                <td>{{number_format($row->totalNeto(),0,',','.')}}</td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <div class="card border-0 rounded shadow p-4">
                            <div class="form-group row mb-3">
                                <label class="col-md-5 col-form-label">Sub Total</label>
                                <div class="col-md-7">
                                    <input type="text" class="form-control input-subtotal" placeholder="Sub Total" value="{{number_format($result->totalNeto() + $result->discount,0,',','.')}}" readonly disabled>
                                </div>
                            </div>
                            <div class="form-group row mb-3">
                                <label class="col-md-5 col-form-label">Diskon</label>
                                <div class="col-md-7">
                                    <input type="text" class="form-control input-discount" placeholder="Diskon" value="{{number_format($result->discount,0,',','.')}}" name="discount" readonly disabled>
                                </div>
                            </div>
                            <div class="form-group row mb-3">
                                <label class="col-md-5 col-form-label">Grand Total</label>
                                <div class="col-md-7">
                                    <input type="text" class="form-control input-total" placeholder="Grand Total" value="{{number_format($result->totalNeto(),0,',','.')}}" readonly disabled>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 mb-3">
                        <div class="card border-0 rounded shadow p-4">
                            <div class="form-group">
                                <label>Catatan</label>
                                <textarea name="note" class="form-control" rows="5" readonly disabled>{{$result->note}}</textarea>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 mb-3">
                        <div class="card border-0 rounded shadow p-4">
                            <div class="row">
                                <div class="col-12">
                                    <div class="form-group row mb-3">
                                        <label>Metode Pembayaran</label>
                                        <input type="text" class="form-control" readonly disabled value="{{$result->provider->name ?? null}}">
                                    </div>
                                    <div class="form-group row mb-3">
                                        <label>Status Transaksi</label>
                                        <span class="badge bg-{{$result->status()->class ?? null}}">{{$result->status()->msg ?? null}}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-12">
                        <div class="card border-0 rounded shadow p-4">
                            <div class="row">
                                <div class="col-12">
                                    <div class="d-flex justify-content-start">
                                        <a href="{{route('dashboard.orders.index')}}" class="btn btn-warning btn-sm" style="margin-right: 10px;"><i class="fa fa-arrow-left"></i> Kembali</a>
                                        @if(Auth::user()->hasRole([\App\Enums\RoleEnum::OWNER]))
                                        <a href="{{route('dashboard.orders.edit',$result->id)}}" class="btn btn-primary btn-sm" style="margin-right: 10px;"><i class="fa fa-edit"></i> Edit</a>
                                        <a href="#" class="btn btn-danger btn-sm btn-delete" data-id="{{$result->id}}"><i class="fa fa-trash"></i> Hapus</a>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
    </div>
</div>

@if($result->provider->type == \App\Enums\ProviderEnum::TYPE_DOKU)
    @include("dashboard.orders.doku.index")
@elseif($result->provider->type == \App\Enums\ProviderEnum::TYPE_MANUAL_TRANSFER)
    @include("dashboard.orders.manual.index")
    @include("dashboard.orders.manual.modal")
@endif

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

        $(document).on("click", ".btn-delete", function(e) {
            e.preventDefault();
            let id = $(this).data("id");
            if (confirm("Apakah anda yakin ingin menghapus data ini ?")) {
                $("#frmDelete").attr("action", "{{ route('dashboard.orders.destroy', '_id_') }}".replace("_id_", id));
                $("#frmDelete").find('input[name="id"]').val(id);
                $("#frmDelete").submit();
            }
        })

        $(document).on("click", ".btn-proof-order", function(e) {
            e.preventDefault();
            let id = $(this).data("id");
            
            $("#frmUploadProofOrder").attr("action", "{{ route('dashboard.orders.proofOrder', '_id_') }}".replace("_id_", id));
            $("#modalUploadProofOrder").modal("show");
        })

        $(document).on('submit','#frmUploadProofOrder',function(e){
            e.preventDefault();
            if(confirm("Apakah anda yakin ingin menyimpan data ini ?")){
                $.ajax({
                    url : $("#frmUploadProofOrder").attr("action"),
                    method : "POST",
                    data : new FormData($('#frmUploadProofOrder')[0]),
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
                            responseSuccess(resp.message,"{{route('dashboard.orders.show',$result->id)}}");
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
    });
</script>
@endsection