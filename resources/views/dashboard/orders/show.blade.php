@extends("dashboard.layouts.main")

@section("title","Penjualan")

@section("css")
@endsection

@section("breadcumb")
<div class="d-md-flex justify-content-between align-items-center">
    <h5 class="mb-0">Penjualan</h5>

    <nav aria-label="breadcrumb" class="d-inline-block mt-2 mt-sm-0">
        <ul class="breadcrumb bg-transparent rounded mb-0 p-0">
            <li class="breadcrumb-item text-capitalize"><a href="#">Penjualan</a></li>
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
                                        <label class="col-md-3 col-form-label">Biaya Layanan & Aplikasi</label>
                                        <div class="col-md-9">
                                            <div class="input-group">
                                                <input type="text" class="form-control" placeholder="Biaya Layanan & Aplikasi" value="{{$result->owner_fee}}" readonly disabled>
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
                                                <input type="text" class="form-control" placeholder="Fee Agen" value="{{$result->agen_fee}}" readonly disabled>
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
                            <div class="display-general-customer @if(!empty($result->customer_id)) d-none @endif">
                                <div class="form-group row mb-3">
                                    <label class="col-md-5 col-form-label">Nama Customer</label>
                                    <div class="col-md-7">
                                        <input type="text" class="form-control" value="{{$result->customer_name}}" readonly disabled>
                                    </div>
                                </div>
                                <div class="form-group row mb-3">
                                    <label class="col-md-5 col-form-label">Telp. Customer</label>
                                    <div class="col-md-7">
                                        <input type="text" class="form-control" value="{{$result->customer_phone}}" readonly disabled>
                                    </div>
                                </div>
                                <div class="form-group row mb-3">
                                    <label class="col-md-5 col-form-label">Email Customer</label>
                                    <div class="col-md-7">
                                        <input type="text" class="form-control" value="{{$result->customer_email}}" readonly disabled>
                                    </div>
                                </div>
                            </div>
                            @if(in_array($result->business->category->name ?? null,[\App\Enums\BusinessCategoryEnum::FNB]))
                            <div class="form-group row mb-3">
                                <label class="col-md-5 col-form-label">Dine In/Take Away</label>
                                <div class="col-md-7">
                                    <input type="text" class="form-control" value="{{$result->fnb_type() ?? null}}" readonly disabled>
                                </div>
                            </div>
                            <div class="form-group row mb-3">
                                <label class="col-md-5 col-form-label">Meja</label>
                                <div class="col-md-7">
                                <input type="text" class="form-control" value="{{$result->table->name ?? null}}" readonly disabled>
                                </div>
                            </div>
                            @endif
                            <div class="form-group row mb-3">
                                <label class="col-md-5 col-form-label">Pemilik Usaha</label>
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
                            <h1 style="margin-top: 0;"><b class="text-total">{{number_format($result->totalNeto(),0,',','.')}}</b></h1>
                            <p><small><i><i class="fa fa-info-circle"></i> Grand total belum dipotong biaya layanan & aplikasi</i></small></p>
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
                                            <th>Aksi</th>
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
                                                <td>
                                                    @if($row->product->mikrotik == \App\Enums\ProductEnum::MIKROTIK_PPPOE)
                                                        <a href="#" class="btn btn-info btn-sm mr-2 mb-2 btn-pppoe" data-index='{{$index}}'>Lihat Konfigurasi</a>

                                                        <div class="modal fade modalPppoe" aria-hidden="true">
                                                            <div class="modal-dialog modal-dialog-centered modal-lg">
                                                                <div class="modal-content rounded shadow border-0">
                                                                    <div class="modal-header border-bottom">
                                                                        <h5 class="modal-title">Pengaturan PPPOE</h5>
                                                                        <button type="button" class="btn btn-icon btn-close" data-bs-dismiss="modal" id="close-modal"><i class="uil uil-times fs-4 text-dark"></i></button>
                                                                    </div>
                                                                    <div class="modal-body">
                                                                        <div class="form-group mb-3">
                                                                            <label>Username</label>
                                                                            <input type="text" class="form-control username" placeholder="Username" value="{{$row->order_mikrotik->username ?? null}}" readonly disabled>
                                                                        </div>
                                                                        <div class="form-group mb-3">
                                                                            <label>Password</label>
                                                                            <input type="text" class="form-control password" placeholder="Password" value="{{$row->order_mikrotik->password ?? null}}" readonly disabled>
                                                                        </div>
                                                                        <div class="row">
                                                                            <div class="col-lg-6">
                                                                                <div class="form-group mb-3">
                                                                                    <label>Service</label>
                                                                                    <input type="text" class="form-control service" placeholder="Service" value="{{$row->order_mikrotik->service ?? null}}" readonly disabled>
                                                                                </div>
                                                                            </div>
                                                                            <div class="col-lg-6">
                                                                                <div class="form-group mb-3">
                                                                                    <label>Profile</label>
                                                                                    <input type="text" class="form-control profile" placeholder="Profile" value="{{$row->order_mikrotik->profile ?? null}}" readonly disabled>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        <div class="row">
                                                                            <div class="col-lg-6">
                                                                                <div class="form-group mb-3">
                                                                                    <label>Local Address</label>
                                                                                    <input type="text" class="form-control local-address" placeholder="Local Address" value="{{$row->order_mikrotik->local_address ?? null}}" readonly disabled>
                                                                                </div>
                                                                            </div>

                                                                            <div class="col-lg-6">
                                                                                <div class="form-group mb-3">
                                                                                    <label>Remote Address</label>
                                                                                    <input type="text" class="form-control remote-address" placeholder="Remote Address" value="{{$row->order_mikrotik->remote_address ?? null}}" readonly disabled>
                                                                                </div>
                                                                            </div>
                                                                        </div>

                                                                        @if($result->type == \App\Enums\OrderEnum::TYPE_ON_TIME_PAY)
                                                                        <div class="display-expired-month">
                                                                            <div class="form-group mb-3">
                                                                                <label>Berlaku Hingga</label>
                                                                                <div class="input-group">
                                                                                    <input type="number" class="form-control expired-month" placeholder="Berlaku Hingga" value="{{$row->order_mikrotik->expired_month ?? null}}" readonly disabled>
                                                                                    <div class="input-group-append">
                                                                                        <span class="input-group-text">BULAN</span>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        @endif
                                                                        
                                                                        <div class="form-group mb-3">
                                                                            <label>Comment</label>
                                                                            <input type="text" class="form-control comment" placeholder="Comment" value="{{$row->order_mikrotik->comment ?? null}}" readonly disabled>
                                                                        </div>
                                                                    </div>
                                                                    
                                                                    <div class="modal-footer">
                                                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    @elseif($row->product->mikrotik == \App\Enums\ProductEnum::MIKROTIK_HOTSPOT)
                                                        <a href="#" class="btn btn-info btn-sm mr-2 mb-2 btn-hotspot" data-index='{{$index}}'>Lihat Konfigurasi</a>

                                                        <div class="modal fade modalHotspot" aria-hidden="true">
                                                            <div class="modal-dialog modal-dialog-centered modal-lg">
                                                                <div class="modal-content rounded shadow border-0">
                                                                    <div class="modal-header border-bottom">
                                                                        <h5 class="modal-title">Pengaturan Hotspot</h5>
                                                                        <button type="button" class="btn btn-icon btn-close" data-bs-dismiss="modal" id="close-modal"><i class="uil uil-times fs-4 text-dark"></i></button>
                                                                    </div>
                                                                    <div class="modal-body">
                                                                        <div class="form-group mb-3">
                                                                            <label>Jenis Pengisian Username dan Password</label>
                                                                            <input type="text" class="form-control auto_username" placeholder="Jenis Pengisian Username dan Password" value="{{$row->order_mikrotik->auto_userpassword() ?? null}}" readonly disabled>
                                                                        </div>
                                                                        <div class="form-group mb-3">
                                                                            <label>Username</label>
                                                                            <input type="text" class="form-control username" placeholder="Username" value="{{$row->order_mikrotik->username ?? null}}" readonly disabled>
                                                                        </div>
                                                                        <div class="form-group mb-3">
                                                                            <label>Password</label>
                                                                            <input type="text" class="form-control password" placeholder="Password" value="{{$row->order_mikrotik->password ?? null}}" readonly disabled>
                                                                        </div>
                                                                        <div class="form-group mb-3">
                                                                            <label>Server</label>
                                                                            <input type="text" class="form-control" placeholder="Server" value="{{$row->order_mikrotik->server ?? null}}" readonly disabled>
                                                                        </div>
                                                                        <div class="form-group mb-3">
                                                                            <label>Profile</label>
                                                                            <input type="text" class="form-control" placeholder="Profile" value="{{$row->order_mikrotik->profile ?? null}}" readonly disabled>
                                                                        </div>
                                                                        <div class="form-group mb-3">
                                                                            <label>Time Limit</label>
                                                                            <input type="text" class="form-control time_limit" placeholder="Contoh : 1d4h30m20s" value="{{$row->order_mikrotik->time_limit ?? null}}" readonly disabled>
                                                                        </div>
                                                                        <div class="form-group mb-3">
                                                                            <label>Comment</label>
                                                                            <input type="text" class="form-control comment" placeholder="Comment" value="{{$row->order_mikrotik->comment ?? null}}" readonly disabled>
                                                                        </div>
                                                                    </div>
                                                                    <div class="modal-footer">
                                                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    @endif
                                                </td>
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
                                        <label>Jenis Transaksi</label>
                                        <input type="text" class="form-control" readonly disabled value="{{$result->type() ?? null}}">
                                    </div>
                                    <div class="display-due-date @if($result->type == App\Enums\OrderEnum::TYPE_ON_TIME_PAY) d-none @endif">
                                        <div class="form-group row mb-3">
                                            <label>Jatuh Tempo</label>
                                            <input type="text" class="form-control" readonly disabled value="{{(empty($result->repeat_order_at)) ? date('d-m-Y',strtotime($result->created_at.' + 30 day')) : 'Setiap tanggal '.$result->repeat_order_at}}">
                                        </div>
                                    </div>
                                    <div class="form-group row mb-3">
                                        <label>Metode Pembayaran</label>
                                        <input type="text" class="form-control" readonly disabled value="{{$result->provider->name ?? null}}">
                                    </div>
                                    <div class="form-group row mb-3">
                                        <label>Status Progress</label>
                                        <span class="badge bg-{{$result->progress()->class ?? null}}">{{$result->progress()->msg ?? null}}</span>
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
                                            @if(!empty($result->business_id))
                                                <a href="{{route('dashboard.orders.edit',$result->id)}}" class="btn btn-primary btn-sm" style="margin-right: 10px;"><i class="fa fa-edit"></i> Edit</a>
                                                <a href="#" class="btn btn-danger btn-sm btn-delete" data-id="{{$result->id}}"><i class="fa fa-trash"></i> Hapus</a>
                                            @endif
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

@include("dashboard.orders.duedate.index")

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

        $(document).on("click",".btn-pppoe",function(e){
            e.preventDefault();
            let index = $(this).attr("data-index");

            $(this).next().modal("show");
        });

        $(document).on("click",".btn-hotspot",function(e){
            e.preventDefault();
            let index = $(this).attr("data-index");

            $(this).next().modal("show");
        });

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