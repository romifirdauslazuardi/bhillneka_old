@extends("landing-page.layouts.main")

@section("title","Checkout")

@section("css")
@endsection

@section("content")
<section class="bg-half-170 bg-light d-table w-100">
    <div class="container">
        <div class="row mt-5 justify-content-center">
            <div class="col-lg-12 text-center">
                <div class="pages-heading">
                    <h4 class="title mb-0">Checkout</h4>
                </div>
            </div>
        </div>
        
        <div class="position-breadcrumb">
            <nav aria-label="breadcrumb" class="d-inline-block">
                <ul class="breadcrumb rounded shadow mb-0 px-4 py-2">
                    <li class="breadcrumb-item"><a href="{{route('landing-page.home.index')}}">Home</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Checkout</li>
                </ul>
            </nav>
        </div>
    </div>
</section>

<section class="section">
    <div class="container">
        <div class="row d-flex justify-content-center">
            <div class="col-12">
                <form action="{{route('landing-page.buy-products.store',$result->id)}}" onsubmit="return confirm('Apakah anda yakin ingin mengirim data ini?')" method="POST" enctype='multipart/form-data'>
                    @csrf
                    <input type="hidden" name="user_id" value="{{$result->user_id}}">
                    <input type="hidden" name="business_id" value="{{$result->business_id}}">
                    <input type="hidden" name="repeater[0][product_id]" value="{{$result->id}}">
                    <div class="row mb-3">
                        <div class="col-12">
                            <div class="card border-0 rounded shadow p-4">
                                <div class="row mb-2">
                                    <div class="col-12">
                                        <h5>Infomasi Produk</h5>
                                    </div>
                                </div>
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
                                                    <td>Nama Produk</td>
                                                    <td class="text-center">:</td>
                                                    <td>{{$result->name}}</td>
                                                </tr>
                                                <tr>
                                                    <td>Kode Produk</td>
                                                    <td class="text-center">:</td>
                                                    <td>{{$result->code}}</td>
                                                </tr>
                                                <tr>
                                                    <td>Harga Produk</td>
                                                    <td class="text-center">:</td>
                                                    <td>{{number_format($result->price,0,',','.')}}</td>
                                                </tr>
                                                <tr>
                                                    <td>Nama Customer</td>
                                                    <td class="text-center">:</td>
                                                    <td>
                                                        <input type="text" class="form-control" placeholder="Nama Customer" name="customer_name">
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>Telp Customer</td>
                                                    <td class="text-center">:</td>
                                                    <td>
                                                        <input type="text" class="form-control" placeholder="Telp Customer" name="customer_phone">
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>Quantity</td>
                                                    <td class="text-center">:</td>
                                                    <td>
                                                        <input type="number" class="form-control code" placeholder="Quantity" name="repeater[0][qty]" value="1">
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            <hr>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-12">
                            <div class="card border-0 rounded shadow p-4">
                                <div class="row mb-2">
                                    <div class="col-12">
                                        <h5>Pilih metode pembayaran</h5>
                                    </div>
                                </div>
                                @foreach($providers as $index => $row)
                                <div class="row mb-3">
                                    <div class="col-12">
                                        <div class="card border-0 rounded shadow p-4">
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="provider_id" value="{{$row->id}}">
                                                <label class="form-check-label">
                                                    {{$row->name ?? null}}
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <div class="d-grid">
                                <button type="submit" id="submit" name="send" class="btn btn-primary" disabled>Checkout</button>
                            </div>
                        </div><!--end col-->
                    </div><!--end row-->
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