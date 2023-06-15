@extends("landing-page.layouts.main")

@section("title","Pembayaran")

@section("css")
@endsection

@section("content")
<section class="bg-half-170 bg-light d-table w-100">
    <div class="container">
        <div class="row mt-5 justify-content-center">
            <div class="col-lg-12 text-center">
                <div class="pages-heading">
                    <h4 class="title mb-0">Pembayaran</h4>
                </div>
            </div>
        </div>
        
        <div class="position-breadcrumb">
            <nav aria-label="breadcrumb" class="d-inline-block">
                <ul class="breadcrumb rounded shadow mb-0 px-4 py-2">
                    <li class="breadcrumb-item"><a href="{{route('landing-page.home.index')}}">Home</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Pembayaran</li>
                </ul>
            </nav>
        </div>
    </div>
</section>

<section class="section">
    <div class="container">
        <div class="row d-flex justify-content-center">
            <div class="col-12">
                <form action="{{route('landing-page.manual-payments.proofOrder',$result->id)}}" onsubmit="return confirm('Apakah anda yakin ingin mengirim data ini?')" method="POST" enctype='multipart/form-data'>
                    @csrf
                    @method("PUT")
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
                                                    <td>Tanggal Kadaluarsa</td>
                                                    <td class="text-center">:</td>
                                                    <td>{{date('d-m-Y H:i:s',strtotime($result->expired_date))}}</td>
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
                                                <tr>
                                                    <td>Status</td>
                                                    <td class="text-center">:</td>
                                                    <td>
                                                        <span class="badge bg-{{$result->status()->class ?? null}}">{{$result->status()->msg ?? null}}</span>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>Intruksi</td>
                                                    <td class="text-center">:</td>
                                                    <td>{{$result->provider->note ?? null}}</td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                <hr>
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
                    <div class="row">
                        <div class="col-12">
                            <div class="card border-0 rounded shadow p-4">
                                <div class="form-group mb-3">
                                    <label>Bukti Pembayaran <span class="text-danger">*</span></i></label>
                                    <input type="file" class="form-control" name="proof_order" accept="image/*">
                                </div>
                                <div class="form-group mb-3">
                                    <label>Catatan <span class="text-danger">*</span></i></label>
                                    <textarea class="form-control" placeholder="Catatan" name="payment_note">{{old('payment_note')}}</textarea>
                                </div>
                                <div class="row">
                                    <div class="col-12">
                                        <div class="d-grid">
                                            <button type="submit" id="submit" name="send" class="btn btn-primary" disabled>Upload Bukti Pembayaran</button>
                                        </div>
                                    </div><!--end col-->
                                </div><!--end row-->
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>
@endsection

@section("script")
<script>
    $(function(){
        $('button[type="submit"]').attr("disabled",false);
    })
</script>
@endsection