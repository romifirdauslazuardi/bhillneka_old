@extends("landing-page.layouts.main")

@section("title",$business->name)

@section("css")
@endsection

@section("content")
<section class="bg-half-170 bg-light d-table w-100">
    <div class="container">
        <div class="row mt-5 justify-content-center">
            <div class="col-lg-12 text-center">
                <div class="pages-heading">
                    <h4 class="title mb-0">{{$business->name}}</h4>
                </div>
            </div>
        </div>
        
        <div class="position-breadcrumb">
            <nav aria-label="breadcrumb" class="d-inline-block">
                <ul class="breadcrumb rounded shadow mb-0 px-4 py-2">
                    <li class="breadcrumb-item"><a href="{{route('landing-page.home.index')}}">Home</a></li>
                    <li class="breadcrumb-item active" aria-current="page">{{$business->name}}</li>
                </ul>
            </nav>
        </div>
    </div>
</section>

<section class="section">
    <div class="container">
        <form method="get">
            <div class="row mb-5">
                <div class="col-12">
                    <div class="input-group">
                        <input type="text" class="form-control" placeholder="Nama Produk" name="search" value="{{request()->get('search')}}">
                        <div class="input-group-append">
                            <div class="d-flex">
                                <button class="input-group-text btn btn-success" type="submit"><i class="fa fa-search"></i></button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
        <div class="row">
            <div class="col-lg-7 mb-3">
                <div class="card shadow rounded border-0">
                    <div class="card-body">
                        <div class="row">
                            @forelse($products as $index => $row)
                            <div class="col-sm-6 col-md-6 mb-3">
                                <div class="card shadow rounded border-0">
                                    <div class="card-body text-center text-uppercase">
                                        <div class="mb-3" style="width: 100%;height:150px;">
                                            <img src="{{(!empty($row->image)) ? asset($row->image) : 'https://t3.ftcdn.net/jpg/04/34/72/82/360_F_434728286_OWQQvAFoXZLdGHlObozsolNeuSxhpr84.jpg'}}" alt="{{$row->name}}" style="width:100%;height:100%;">
                                        </div>
                                        <p style="margin-bottom: 0;padding-bottom:0;">{{$row->name}}</p>
                                        <h6>{{number_format($row->price,0,',','.')}}</h6>
                                    </div>
                                    <a href="#" class="btn btn-success btn-sm d-block btn-add-cart" data-id="{{$row->id}}" data-name="{{$row->name}}" data-price="{{$row->price}}" data-image="{{$row->image}}"><i class="fa fa-shopping-cart"></i> ADD TO CART</a>
                                </div>
                            </div>
                            @empty
                            <div class="alert alert-warning text-center py-3">
                                Produk tidak ditemukan
                            </div>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-5 mb-3">
                <div class="card shadow rounded border-0">
                    <div class="card-body">
                        <h6 class="card-title mb-3">Informasi Pesanan</h6>
                        <form action="{{route('landing-page.buy-products.store')}}" method="POST" onsubmit="return confirm('Apakah anda yakin ingin memesan data ini?')">
                            @csrf
                            <input type="hidden" name="user_id" value="{{$business->user_id}}">
                            <input type="hidden" name="business_id" value="{{$business->id}}">
                            <input type="hidden" name="type" value="{{\App\Enums\OrderEnum::TYPE_ON_TIME_PAY}}">
                            <input type="hidden" name="table_id" value="{{$table->id}}">
                            @if(count($carts) >= 1)
                                @php
                                    $index = 0;
                                @endphp
                                @foreach($carts as $i => $row)
                                
                                <input type="hidden" name="repeater[{{$index}}][product_id]" value="{{$row->id}}">
                                <input type="hidden" name="repeater[{{$index}}][qty]" value="{{$row->quantity}}">
                                <table class="mb-1">
                                    <tbody>
                                        <tr>
                                            <td style="width: 35%;">{{$row->name}}</td>
                                            <td style="width: 35%;">{{number_format($row->price,0,',','.')}}</td>
                                            <td style="width: 30%;">
                                                <div class="d-flex">
                                                    <button class="btn btn-icon btn-soft-primary btn-min-cart" style="margin-right: 5px;" data-id="{{$row->id}}">-</button>
                                                    <input min="0" name="quantity" value="{{$row->quantity}}" type="number" class="btn btn-icon btn-soft-primary qty-btn quantity" style="margin-right: 5px;" readonly>
                                                    <button class="btn btn-icon btn-soft-primary btn-plus-cart" data-id="{{$row->id}}">+</button>
                                                </div>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                                @php
                                    $index += 1;
                                @endphp
                                @endforeach

                                <div class="row mb-3 mt-3">
                                    <div class="col-12">
                                        <div class="table-responsive">
                                            <div class="table">
                                                <table class="table table-bordered">
                                                    <tbody>
                                                        <tr>
                                                            <td>Meja</td>
                                                            <td>:</td>
                                                            <td>
                                                                {{$table->name}}
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td>Total</td>
                                                            <td>:</td>
                                                            <td>
                                                                {{number_format(Cart::getTotal(),0,',','.')}}
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td>Nama Customer</td>
                                                            <td>:</td>
                                                            <td>
                                                                <input type="text" class="form-control" name="customer_name" value="{{old('customer_name')}}">
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td>Telp Customer</td>
                                                            <td>:</td>
                                                            <td>
                                                                <input type="text" class="form-control" name="customer_phone" value="{{old('customer_phone')}}">
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td>Dine In/Take Away</td>
                                                            <td>:</td>
                                                            <td>
                                                                <select class="form-control" name="fnb_type">
                                                                    @foreach($fnb_type as $index => $row)
                                                                    <option value="{{$index}}">{{$row}}</option>
                                                                    @endforeach
                                                                </select>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td>Metode Pembayaran</td>
                                                            <td>:</td>
                                                            <td>
                                                                <select class="form-control" name="provider_id">
                                                                    <option value="">==Pilih Metode Pembayaran==</option>
                                                                    @foreach($providers as $index => $row)
                                                                    <option value="{{$row->id}}" @if(old('provider_id') == $row->id) @endif>{{$row->name}}</option>
                                                                    @endforeach
                                                                </select>
                                                            </td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <button class="btn btn-success btn-sm" style="width: 100%;" type="submit">Checkout</button>
                            @else
                            <p class="text-center">Cart tidak tersedia</p>
                            @endif
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<form action="{{route('landing-page.shops.addToCart')}}" method="POST" id="frmAddToCart">
    @csrf
    <input type="hidden" name="product_id">
    <input type="hidden" name="product_name">
    <input type="hidden" name="product_price">
    <input type="hidden" name="qty">
    <input type="hidden" name="image">
</form>

<form action="#" method="POST" id="frmUpdateCart">
    @csrf
    @method("PUT")
    <input type="hidden" name="qty">
</form>
@endsection

@section("script")
<script>
    $(function(){
        $(document).on("click",'.btn-add-cart',function(e){
            e.preventDefault();
            let product_id = $(this).attr("data-id");
            let product_name = $(this).attr("data-name");
            let product_price = $(this).attr("data-price");
            let qty = 1;
            let image = $(this).attr("data-image");

            $('#frmAddToCart').find('input[name="product_id"]').val(product_id);
            $('#frmAddToCart').find('input[name="product_name"]').val(product_name);
            $('#frmAddToCart').find('input[name="product_price"]').val(product_price);
            $('#frmAddToCart').find('input[name="qty"]').val(qty);
            $('#frmAddToCart').find('input[name="image"]').val(image);
            $('#frmAddToCart').submit();
        });

        $(document).on("click",".btn-min-cart",function(e){
            e.preventDefault();
            
            $(this).next().val(parseInt($(this).next().val())-1);

            let id = $(this).attr("data-id");
            let qty = $(this).next().val();
            qty = parseInt(qty);

            $('#frmUpdateCart').find('input[name="qty"]').val(qty);
            $("#frmUpdateCart").attr("action", "{{ route('landing-page.shops.updateCart', '_id_') }}".replace("_id_", id));
            $('#frmUpdateCart').submit();
        })

        $(document).on("click",".btn-plus-cart",function(e){
            e.preventDefault();
            
            $(this).prev().val(parseInt($(this).prev().val())+1);

            let id = $(this).attr("data-id");
            let qty = $(this).prev().val();
            qty = parseInt(qty);

            $('#frmUpdateCart').find('input[name="qty"]').val(qty);
            $("#frmUpdateCart").attr("action", "{{ route('landing-page.shops.updateCart', '_id_') }}".replace("_id_", id));
            $('#frmUpdateCart').submit();
        })
    })
</script>
@endsection