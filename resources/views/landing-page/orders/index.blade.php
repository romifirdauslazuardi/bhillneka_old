@extends("landing-page.layouts.main")

@section("title","Cek Status Order")

@section("css")
@endsection

@section("content")
<section class="bg-half-170 bg-light d-table w-100">
    <div class="container">
        <div class="row mt-5 justify-content-center">
            <div class="col-lg-12 text-center">
                <div class="pages-heading">
                    <h4 class="title mb-0">Cek Status Order</h4>
                </div>
            </div>
        </div>
        
        <div class="position-breadcrumb">
            <nav aria-label="breadcrumb" class="d-inline-block">
                <ul class="breadcrumb rounded shadow mb-0 px-4 py-2">
                    <li class="breadcrumb-item"><a href="{{route('landing-page.home.index')}}">Home</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Cek Status Order</li>
                </ul>
            </nav>
        </div>
    </div>
</section>

<section class="section">
    <div class="container">
        <div class="row d-flex justify-content-center">
            <div class="col-lg-8">
                <form action="{{route('landing-page.orders.index')}}">
                    <div class="input-group">
                        <input type="text" class="form-control code" placeholder="Kode Transaksi" name="code" value="{{request()->get('code')}}">
                        <div class="input-group-append">
                            <div class="d-flex">
                                <button class="input-group-text btn btn-success" type="submit"><i class="fa fa-search"></i></button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        @if(!empty($result))
        <div class="row d-flex justify-content-center mt-5">
            <div class="col-12">
                <div class="row mb-3">
                    <div class="col-12">
                        <div class="card border-0 rounded shadow p-4">
                            <h5>Informasi Order</h5>
                            <div class="table-responsive">
                                <div class="table">
                                    <table class="table table-bordered">
                                        <tbody>
                                            <tr>
                                                <td>Pemilik Usaha</td>
                                                <td class="text-center">:</td>
                                                <td>{{$result->user->name ?? null}}</td>
                                            </tr>
                                            <tr>
                                                <td>Kode Transaksi</td>
                                                <td class="text-center">:</td>
                                                <td>{{$result->code}}</td>
                                            </tr>
                                            <tr>
                                                <td>Tanggal Order</td>
                                                <td class="text-center">:</td>
                                                <td>{{date('d-m-Y H:i:s',strtotime($result->created_at))}}</td>
                                            </tr>
                                            <tr>
                                                <td>Customer</td>
                                                <td class="text-center">:</td>
                                                <td>@if(empty($result->customer_id)) - @else {{$result->customer->name ?? null}} @endif</td>
                                            </tr>
                                            <tr>
                                                <td>Metode Pembayaran</td>
                                                <td class="text-center">:</td>
                                                <td>{{$result->provider->name ?? null}}</td>
                                            </tr>
                                            @if($result->provider->type == \App\Enums\ProviderEnum::TYPE_DOKU && $result->status != \App\Enums\OrderEnum::STATUS_WAITING_PAYMENT)
                                            <tr>
                                                <td>Doku Service ID</td>
                                                <td class="text-center">:</td>
                                                <td>{{$result->doku_service_id}}</td>
                                            </tr>
                                            <tr>
                                                <td>Doku Acquirer ID</td>
                                                <td class="text-center">:</td>
                                                <td>{{$result->doku_acquirer_id}}</td>
                                            </tr>
                                            <tr>
                                                <td>Doku Channel ID</td>
                                                <td class="text-center">:</td>
                                                <td>{{$result->doku_channel_id}}</td>
                                            </tr>
                                            @endif
                                            <tr>
                                                <td>SubTotal</td>
                                                <td class="text-center">:</td>
                                                <td>{{number_format($result->totalNeto()+$result->discount,0,',','.')}}</td>
                                            </tr>
                                            <tr>
                                                <td>Diskon</td>
                                                <td class="text-center">:</td>
                                                <td>{{number_format($result->discount,0,',','.')}}</td>
                                            </tr>
                                            <tr>
                                                <td>Grand Total</td>
                                                <td class="text-center">:</td>
                                                <td>{{number_format($result->totalNeto(),0,',','.')}}</td>
                                            </tr>
                                            @if($result->provider->type == \App\Enums\ProviderEnum::TYPE_DOKU && $result->status == \App\Enums\OrderEnum::STATUS_WAITING_PAYMENT)
                                            <tr>
                                                <td>Link Pembayaran</td>
                                                <td class="text-center">:</td>
                                                <td>
                                                    <a href="{{$result->payment_url}}" target="_blank">Bayar Sekarang</a>
                                                </td>
                                            </tr>
                                            @endif

                                            @if($result->provider->type == \App\Enums\ProviderEnum::TYPE_MANUAL_TRANSFER && $result->status == \App\Enums\OrderEnum::STATUS_WAITING_PAYMENT)
                                            <tr>
                                                <td>Link Pembayaran</td>
                                                <td class="text-center">:</td>
                                                <td>
                                                    <a href="{{route('landing-page.manual-payments.index',$result->code)}}">Bayar Sekarang</a>
                                                </td>
                                            </tr>
                                            @endif
                                            <tr>
                                                <td>Status</td>
                                                <td class="text-center">:</td>
                                                <td>
                                                    <span class="badge bg-{{$result->status()->class ?? null}}">{{$result->status()->msg ?? null}}</span>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-12">
                        <div class="card border-0 rounded shadow p-4">
                            <h5>Informasi Produk</h5>
                            <div class="table-responsive">
                                <div class="table">
                                    <table class="table table-striped table-bordered">
                                        <thead>
                                            <th>No</th>
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
                <div class="row mb-3">
                    <div class="col-12">
                        <div class="card border-0 rounded shadow p-4">
                            <h6>Grand Total</h6>
                            <h1 class="card-title"><b>{{number_format($result->totalNeto(),0,',','.')}}</b></h1>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endif
    </div>
</section>
@endsection

@section("script")
<script>
    $(function(){

    })
</script>
@endsection