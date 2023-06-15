@extends("dashboard.layouts.main")

@section("title","Transaksi")

@section("css")
<!-- Datatables -->
<link href="{{URL::to('/')}}/templates/dashboard/assets/libs/datatables/dataTables.bootstrap4.min.css" rel="stylesheet" type="text/css" />
<link href="{{URL::to('/')}}/templates/dashboard/assets/libs/datatables/buttons.bootstrap4.min.css" rel="stylesheet" type="text/css" />
<!-- Responsive datatable examples -->
<link href="{{URL::to('/')}}/templates/dashboard/assets/libs/datatables/responsive.bootstrap4.min.css" rel="stylesheet" type="text/css" />
@endsection

@section("breadcumb")
<div class="d-md-flex justify-content-between align-items-center">
    <h5 class="mb-0">Transaksi</h5>

    <nav aria-label="breadcrumb" class="d-inline-block mt-2 mt-sm-0">
        <ul class="breadcrumb bg-transparent rounded mb-0 p-0">
            <li class="breadcrumb-item text-capitalize"><a href="#">Transaksi</a></li>
            <li class="breadcrumb-item text-capitalize active" aria-current="page">Create</li>
        </ul>
    </nav>
</div>
@endsection

@section("content")
<div class="row">
    <div class="col-12 mt-4">
            <form action="{{route('dashboard.orders.store')}}" id="frmStore" autocomplete="off">
                @csrf
                <div class="row mb-3">
                    <div class="col-md-12 mb-3">
                        <div class="card border-0 rounded shadow p-4">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group row">
                                        <label class="col-md-3 col-form-label">Fee Owner</label>
                                        <div class="col-md-9">
                                            <div class="input-group">
                                                <input type="text" class="form-control" placeholder="Fee Owner" value="{{\SettingHelper::settingFee()->owner_fee ?? null}} (Include Biaya Penanganan)" readonly disabled>
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
                                                <input type="text" class="form-control" placeholder="Fee Owner" value="{{\SettingHelper::settingFee()->agen_fee ?? null}}" readonly disabled>
                                                <button class="input-group-text btn btn-secondary" type="button" disabled>%</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-md-4 mb-3">
                        <div class="card border-0 rounded shadow p-4">
                            <div class="form-group row mb-3">
                                <label class="col-md-5 col-form-label">Tanggal </label>
                                <div class="col-md-7">
                                    <input type="text" class="form-control" placeholder="Tanggal" value="{{date('d-m-Y')}}" readonly disabled>
                                </div>
                            </div>
                            <div class="form-group row mb-3">
                                <label class="col-md-5 col-form-label">Pengguna</label>
                                <div class="col-md-7">
                                    @if(Auth::user()->hasRole([\App\Enums\RoleEnum::OWNER]))
                                    <select class="form-control select2 select-user" name="user_id" >
                                        <option value="">==Pilih Pengguna==</option>
                                        @foreach ($users as $index => $row)
                                        <option value="{{$row->id}}" @if($row->id == old('user_id')) selected @endif>{{$row->name}} - {{$row->phone}}</option>
                                        @endforeach
                                    </select>
                                    @elseif(Auth::user()->hasRole([\App\Enums\RoleEnum::AGEN]))
                                    <input type="text" class="form-control" value="{{Auth::user()->name.' - '.Auth::user()->phone}}" readonly disabled>
                                    @else
                                    @endif
                                </div>
                            </div>
                            <div class="form-group row mb-3">
                                <label class="col-md-5 col-form-label">Customer</label>
                                <div class="col-md-7">
                                    <select class="form-control select2 select-customer" name="customer_id" style="width: 100%;">
                                        <option value="">==Umum==</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 mb-3">
                        <div class="card border-0 rounded shadow p-4">
                            <div class="form-group row mb-3">
                                <label class="col-md-5 col-form-label">Kode Produk </label>
                                <div class="col-md-7">
                                    <div class="input-group">
                                        <input type="text" class="form-control code" placeholder="Kode">
                                        <div class="input-group-append">
                                        <button class="input-group-text btn btn-success btn-show-product" type="button"><i class="fa fa-search"></i></button>
                                    </div>
                                  </div>
                                </div>
                            </div>
                            <div class="form-group row mb-3">
                                <label class="col-md-5 col-form-label">Quantity</label>
                                <div class="col-md-7">
                                    <input type="number" class="form-control input-qty" placeholder="Quantity" value="1">
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-12">
                                    <button type="button" class="btn btn-primary btn-sm btn-submit-product"><i class="fa fa-plus"></i> Tambah</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 mb-3">
                        <div class="card border-0 rounded shadow p-4">
                            <h5 class="card-title"><b>Grand Total</b></h5>
                            <h1><b class="text-total">0</b></h1>
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
                                            
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-md-4 mb-3">
                        <div class="card border-0 rounded shadow p-4">
                            <div class="form-group row mb-3">
                                <label class="col-md-5 col-form-label">Sub Total</label>
                                <div class="col-md-7">
                                    <input type="text" class="form-control input-subtotal" placeholder="Sub Total" value="0" readonly disabled>
                                </div>
                            </div>
                            <div class="form-group row mb-3">
                                <label class="col-md-5 col-form-label">Diskon</label>
                                <div class="col-md-7">
                                    <input type="text" class="form-control input-discount" placeholder="Diskon" value="0" name="discount">
                                </div>
                            </div>
                            <div class="form-group row mb-3">
                                <label class="col-md-5 col-form-label">Grand Total</label>
                                <div class="col-md-7">
                                    <input type="text" class="form-control input-total" placeholder="Grand Total" value="0" readonly disabled>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 mb-3">
                        <div class="card border-0 rounded shadow p-4">
                            <div class="form-group">
                                <label>Catatan</label>
                                <textarea name="note" class="form-control" rows="5"></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 mb-3">
                        <div class="card border-0 rounded shadow p-4">
                            <div class="row">
                                <div class="col-12">
                                <div class="form-group row mb-3">
                                    <label>Metode Pembayaran</label>
                                    <select class="form-control select2" name="provider_id" >
                                        <option value="">==Pilih Metode Pembayaran==</option>
                                        @foreach ($providers as $index => $row)
                                        <option value="{{$row->id}}" @if($row->id == old('provider_id')) selected @endif>{{$row->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                    <a href="{{route('dashboard.orders.index')}}" class="btn btn-warning btn-sm mb-2"><i class="fa fa-arrow-left"></i> Kembali</a>
                                    <button type="submit" class="btn btn-success btn-sm mb-2" disabled><i class="fa fa-send"></i> Proses Pembayaran</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row mb-3 display-latest-order">
                    <div class="col-12">
                        <div class="card border-0 rounded shadow p-4">
                            <h6 class="card-title">Order Terbaru</h6>
                            <div class="table-responsive">
                                <div class="table">
                                    <table class="table table-striped table-bordered latest-order-datatable">
                                        <thead>
                                            <th>No</th>
                                            <th>Kode Transaksi</th>
                                            <th>Total</th>
                                            <th>Status</th>
                                        </thead>
                                        <tbody class="tbody-latest-order">
                                            <tr>
                                                <td colspan="5" class="text-center">Data tidak ditemukan</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
    </div>
</div>

@include("dashboard.orders.modal.create")
@include("dashboard.components.loader")

@endsection

@section("script")
<!-- Datatables -->
<script src="{{URL::to('/')}}/templates/dashboard/assets/libs/parsleyjs/parsley.min.js"></script>
<script src="{{URL::to('/')}}/templates/dashboard/assets/libs/datatables/jquery.dataTables.min.js"></script>
<script src="{{URL::to('/')}}/templates/dashboard/assets/libs/datatables/dataTables.bootstrap4.min.js"></script>
<script src="{{URL::to('/')}}/assets/pages/datatables.init.js"></script>
<script src="{{URL::to('/')}}/templates/dashboard/assets/libs/datatables/dataTables.responsive.min.js"></script>
<script src="{{URL::to('/')}}/templates/dashboard/assets/libs/datatables/responsive.bootstrap4.min.js"></script>
<script>
    $(function(){
        $(".page-wrapper").removeClass("toggled");

        $('button[type="submit"]').attr("disabled",false);

        @if(Auth::user()->hasRole([\App\Enums\RoleEnum::AGEN]))
            getProduct('{{Auth::user()->id}}');
            getCustomer('.select-customer','{{Auth::user()->id}}',null);
            getOrder('{{Auth::user()->id}}');
        @endif

        $(document).on("click",".btn-show-product",function(e){
            e.preventDefault();
            $('#modalAddProduct').modal("show");
        });

        $(document).on("change",".select-user",function(e){
            e.preventDefault();
            let val = $(this).val();

            $('.select-customer').html('<option value="">==Umum==</option>');
            clearTableLatestOrder();

            if(val != null && val != undefined && val != ""){
                getProduct(val);
                getCustomer(".select-customer",val,null);
                getOrder(val);
            }
            else{
                $('.tbody-modal-product').html('<tr><td class="text-center" colspan="7">Produk Tidak Ditemukan</td></tr>');
            }
        });

        $(document).on("click",".btn-select-product",function(e){
            e.preventDefault();
            let id = $(this).data("id");
            let code = $(this).data("code");

            $('.code').val(code);
            $('.input-qty').val(1);

            $("#modalAddProduct").modal("hide");
        });

        $(document).on("click",".btn-submit-product",function(e){
            e.preventDefault();
            let code = $(".code").val();
            let qty = $(".input-qty").val();

            if(code == null || code == undefined || code == ""){
                responseFailed("Kode produk tidak boleh kosong");
                return false;
            }

            if(qty == null || qty == undefined || qty == ""){
                responseFailed("Quantity produk tidak boleh kosong");
                return false;
            }

            if(qty <= 0 ){
                responseFailed("Quantity produk tidak boleh kurang dari 1");
                return false;
            }

            getProductShow(code,qty);

            $(".code").val(null);
        });

        $(document).on("keyup",".tbody-product-qty",function(e){
            e.preventDefault();
            
            generateSubTotalRow($(this).parent().parent());
            generateTotal();
            
        });

        $(document).on("keyup",".tbody-product-discount",function(e){
            e.preventDefault();

            let val = $(this).val();

            $(this).val(formatRupiah(val,undefined));
            
            generateSubTotalRow($(this).parent().parent());
            generateTotal();
            
        });

        $(document).on("keyup",".input-discount",function(e){
            e.preventDefault();
            
            let val = $(this).val();

            $(this).val(formatRupiah(val,undefined));

            generateTotal();
            
        });

        $(document).on("click",".btn-delete-product",function(e){
            e.preventDefault();
            $(this).parent().parent().remove();
            sortTableProduct();
            generateTotal();
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
                            responseSuccess(resp.message,"{{route('dashboard.orders.create')}}");
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

    function getProduct(user_id){
        $.ajax({
            url : '{{route("base.products.index")}}',
            method : "GET",
            data : {
                user_id : user_id
            },
            dataType : "JSON",
            beforeSend : function(){
                return openLoader();
            },
            success : function(resp){
                if(resp.success == false){
                    responseFailed(resp.message);       
                    $('.tbody-modal-product').html('<tr><td class="text-center" colspan="7">Produk Tidak Ditemukan</td></tr>');         
                }
                else{
                    let html = "";
                    $.each(resp.data,function(index,element){
                        html += `
                            <tr>
                                <td>${index+1}</td>
                                <td>${element.code}</td>
                                <td>${element.name}</td>
                                <td>${formatRupiah(element.price,undefined)}</td>
                                <td>
                                    <a href="#" class="btn btn-success btn-sm btn-select-product" data-id="${element.id}" data-code="${element.code}">Tambah</a>
                                </td>
                            </tr>
                        `;
                    });
                    $('.tbody-modal-product').html(html);

                    $('.datatables').DataTable();
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

    function getProductShow(code,inputQty=1){
        let data = {};

        @if(Auth::user()->hasRole([\App\Enums\RoleEnum::OWNER]))
            data.user_id = $('select[name="user_id"]').val();
        @endif

        data.code = code;

        $.ajax({
            url : '{{route("base.products.showByCode")}}',
            method : "GET",
            dataType : "JSON",
            data : data,
            beforeSend : function(){
                return openLoader();
            },
            success : function(resp){
                if(resp.success == false){
                    responseFailed(resp.message);         
                }
                else{
                    let index = 0;
                    
                    $('.repeater-product').each(function(index,element){
                        index += 1;
                    });

                    let total = inputQty * resp.data.price;

                    if($('.tbody-product-'+resp.data.id).length >= 1){
                        let existQty = $('.tbody-product-'+resp.data.id).find(".tbody-product-qty").val();
                        inputQty = parseInt(inputQty) + parseInt(existQty);
                        total = inputQty * resp.data.price;

                        $('.tbody-product-'+resp.data.id).find(".tbody-product-qty").val(inputQty);
                        $('.tbody-product-'+resp.data.id).find(".tbody-product-total").html(formatRupiah(total,undefined));

                        generateTotal();

                        return false;
                    }
                    else{
                        let html = `
                                <tr class="repeater-product tbody-product-${resp.data.id}">
                                    <input type="hidden" class="tbody-product-id" value="${resp.data.id}" name="repeater[${index}][product_id]"/>
                                    <td class="tbody-product-number">${index+1}</td>
                                    <td>${resp.data.code}</td>
                                    <td>${resp.data.name}</td>
                                    <td class="tbody-product-price">${formatRupiah(resp.data.price,undefined)}</td>
                                    <td>
                                        <input type="number" class="form-control tbody-product-qty" placeholder="Qty" value="${inputQty}" name="repeater[${index}][qty]"/>
                                    </td>
                                    <td>
                                        <input type="text" class="form-control tbody-product-discount" placeholder="Diskon" value="0" name="repeater[${index}][discount]"/>
                                    </td>
                                    <td class="tbody-product-total">${formatRupiah(total,undefined)}</td>
                                    <td>
                                        <a href="#" class="btn btn-danger btn-sm mr-2 mb-2 btn-delete-product"><i class="fa fa-trash"></i></a>
                                    </td>
                                </tr>
                        `;
                        $('.tbody-product').append(html);
                    }

                    sortTableProduct();

                    generateTotal();
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

    function getOrder(user_id){
        $.ajax({
            url : '{{route("base.orders.index")}}',
            method : "GET",
            dataType : "JSON",
            data : {
                user_id : user_id
            },
            beforeSend : function(){
                return openLoader();
            },
            success : function(resp){
                if(resp.success == false){
                    responseFailed(resp.message);         
                }
                else{
                    let html = "";

                    $.each(resp.data,function(index,element){
                        html += `
                            <tr>
                                <td>${index+1}</td>
                                <td>${element.code}</td>
                                <td>${formatRupiah(element.total,undefined)}</td>
                                <td>
                                    <span class="badge bg-${element.status.class}">${element.status.msg}<span>
                                </td>
                            </tr>
                        `;
                    });
                    
                    $('.tbody-latest-order').html(html);
                    $('.latest-order-datatable').DataTable();
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

    function generateSubTotalRow(parent){
        let qty = $(parent).find(".tbody-product-qty").val();

        let price = $(parent).find(".tbody-product-price").html();
        let discount = $(parent).find(".tbody-product-discount").val();

        price = price.split(".").join("");
        discount = discount.split(".").join("");

        qty = parseInt(qty);
        price = parseInt(price);
        discount = parseInt(discount);

        if(isNaN(qty)){
            qty = 0;
        }

        if(isNaN(price)){
            price = 0;
        }

        if(isNaN(discount)){
            discount = 0;
        }

        let subtotal = ( qty * price ) - discount;
        $(parent).find(".tbody-product-total").html(formatRupiah(subtotal,undefined));
    }

    function generateTotal(){
        let subtotal = 0;
        let total = 0;
        let discount = $('.input-discount').val();
        let total_discount = 0;
        
        discount = discount.split(".").join("");

        discount = parseInt(discount);
        
        if(isNaN(discount)){
            discount = 0;
        }

        $('.repeater-product').each(function(index,element){
            let price = $('.repeater-product').eq(index).find(".tbody-product-price").html();
            let qty = $('.repeater-product').eq(index).find(".tbody-product-qty").val();
            let disc = $('.repeater-product').eq(index).find(".tbody-product-discount").val();

            price = price.split(".").join("");
            disc = disc.split(".").join("");

            price = parseInt(price);
            qty = parseInt(qty);
            disc = parseInt(disc);

            if(isNaN(price)){
                price = 0;
            }

            if(isNaN(qty)){
                qty = 0;
            }

            if(isNaN(disc)){
                disc = 0;
            }

            subtotal += (price * qty) - disc;
            total_discount += disc;
        });

        total_discount += discount;
        total = subtotal - discount;

        if(total <= 0 || total_discount >= subtotal){
            subtotal = 0;
            total = subtotal;
        }
        
        $('.input-subtotal').val(formatRupiah(subtotal,undefined));
        $('.input-total').val(formatRupiah(total,undefined));
        $('.text-total').html(formatRupiah(total,undefined));
        
    }

    function sortTableProduct(){
        $('.repeater-product').each(function(index,element){
            $('.repeater-product').eq(index).find(".tbody-product-number").html(index+1);
            $('.repeater-product').eq(index).find(".tbody-product-id").attr("name","repeater["+index+"][product_id]");
            $('.repeater-product').eq(index).find(".tbody-product-qty").attr("name","repeater["+index+"][qty]");
            $('.repeater-product').eq(index).find(".tbody-product-discount").attr("name","repeater["+index+"][discount]");
        });
    }

    function clearTableLatestOrder(){
        $(".tbody-latest-order").html("");
    }
</script>
@endsection