@extends("dashboard.layouts.main")

@section("title","Penjualan")

@section("css")
<!-- Datatables -->
<link href="{{URL::to('/')}}/templates/dashboard/assets/libs/datatables/dataTables.bootstrap4.min.css" rel="stylesheet" type="text/css" />
<link href="{{URL::to('/')}}/templates/dashboard/assets/libs/datatables/buttons.bootstrap4.min.css" rel="stylesheet" type="text/css" />
<!-- Responsive datatable examples -->
<link href="{{URL::to('/')}}/templates/dashboard/assets/libs/datatables/responsive.bootstrap4.min.css" rel="stylesheet" type="text/css" />
<!-- Datetimepicker -->
<link href="{{URL::to('/')}}/templates/dashboard/assets/libs/datetimepicker/jquery.datetimepicker.css" type="text/css" rel="stylesheet" />
@endsection

@section("breadcumb")
<div class="d-md-flex justify-content-between align-items-center">
    <h5 class="mb-0">Penjualan</h5>

    <nav aria-label="breadcrumb" class="d-inline-block mt-2 mt-sm-0">
        <ul class="breadcrumb bg-transparent rounded mb-0 p-0">
            <li class="breadcrumb-item text-capitalize"><a href="#">Penjualan</a></li>
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
                                <label class="col-md-5 col-form-label">Customer</label>
                                <div class="col-md-7">
                                    <select class="form-control select2 select-customer" name="customer_id" style="width: 100%;">
                                        <option value="">==Umum==</option>
                                    </select>
                                </div>
                            </div>
                            <div class="display-general-customer">
                                <div class="form-group row mb-3">
                                    <label class="col-md-5 col-form-label">Nama Customer</label>
                                    <div class="col-md-7">
                                        <input type="text" class="form-control" name="customer_name" placeholder="Nama Customer">
                                    </div>
                                </div>
                                <div class="form-group row mb-3">
                                    <label class="col-md-5 col-form-label">Telp. Customer</label>
                                    <div class="col-md-7">
                                        <input type="text" class="form-control" name="customer_phone" placeholder="Telp. Customer">
                                    </div>
                                </div>
                            </div>
                            @if(in_array(Auth::user()->business->category->name,[\App\Enums\BusinessCategoryEnum::FNB]))
                            <div class="form-group row mb-3">
                                <label class="col-md-5 col-form-label">Dine In/Take Away</label>
                                <div class="col-md-7">
                                    <select class="form-control select2" name="fnb_type" style="width: 100%;">
                                        @foreach($fnb_type as $index => $row)
                                        <option value="{{$index}}">{{$row}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="form-group row mb-3">
                                <label class="col-md-5 col-form-label">Meja</label>
                                <div class="col-md-7">
                                    <select class="form-control select2 select-table" name="table_id" style="width: 100%;">
                                        <option value="">==Pilih Meja==</option>
                                    </select>
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>
                    <div class="col-md-4 mb-3">
                        <div class="card border-0 rounded shadow p-4">
                            <div class="form-group row mb-3">
                                <label class="col-md-5 col-form-label">Pilih Produk </label>
                                <div class="col-md-7">
                                    <div class="input-group">
                                        <input type="text" class="form-control code" placeholder="Kode Produk">
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
                                        <label>Jenis Transaksi</label>
                                        <select class="form-control select2 select-type" name="type" >
                                            @foreach($type as $index => $row)
                                            <option value="{{$index}}">{{$row}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="display-due-date d-none">
                                        <div class="form-group row mb-3">
                                            <label>Jatuh Tempo Setiap Tanggal</label>
                                            <select class="form-control select2" name="repeat_order_at" style="width: 100%;">
                                                <option value="">==Pilih Tanggal==</option>
                                                @foreach(\DateHelper::date1to28() as $index => $row)
                                                <option value="{{$row}}">{{$row}}</option>
                                                @endforeach
                                            </select>
                                            <p class="text-info" style="margin-top: 0px;margin-bottom: 0px;padding-top: 0px;padding-bottom: 0px;"><small><i>Kosong = Tagihan baru di 30 hari kedepan</i></small></p>
                                        </div>
                                    </div>
                                    <div class="form-group row mb-3">
                                        <label>Metode Pembayaran</label>
                                        <select class="form-control select2" name="provider_id" >
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
                                            <th>Tanggal</th>
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
<!-- Datetimepicker -->
<script src="{{URL::to('/')}}/templates/dashboard/assets/libs/moment/moment.min.js"></script>
<script src="{{URL::to('/')}}/templates/dashboard/assets/libs/datetimepicker/jquery.datetimepicker.min.js"></script>
<script src="{{URL::to('/')}}/templates/dashboard/assets/libs/axios/axios.min.js"></script>
<script>
    $(function(){
        $.datetimepicker.setDateFormatter('moment');
        $.datetimepicker.setLocale('id');
        
        $('.datetimepicker').datetimepicker({
              format:'YYYY-MM-DD HH:mm:ss',
              formatTime:'HH:mm:ss',
              formatDate:'YYYY-MM-DD'
        });

        $(".page-wrapper").removeClass("toggled");

        $('button[type="submit"]').attr("disabled",false);

        @if(!empty(Auth::user()->business_id))
            getProduct('{{Auth::user()->business_id}}',null);
            getCustomer('.select-customer','{{Auth::user()->business_id}}',null);
            getOrder('{{Auth::user()->business_id}}',null);
            getTable('.select-table','{{Auth::user()->business_id}}',null);
        @endif

        $(document).on("click",".btn-show-product",function(e){
            e.preventDefault();
            $('#modalAddProduct').modal("show");
        });

        $(document).on("change",".select-customer",function(e){
            e.preventDefault();

            let val = $(this).val();

            if(val != null && val != undefined && val != ""){
                $('.display-general-customer').removeClass("d-none").addClass("d-none");
            }
            else{
                $('.display-general-customer').removeClass("d-none");
            }
            
        });

        $(document).on("change",".select-type",function(e){
            e.preventDefault();

            let val = $(this).val();

            if(val == '{{\App\Enums\OrderEnum::TYPE_ON_TIME_PAY}}'){
                $('.display-due-date').removeClass("d-none").addClass("d-none");
            }
            else{
                $('.display-due-date').removeClass("d-none");
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

        $(document).on("change",".autouserpassword",function(e){
            e.preventDefault();

            let val = $(this).val();

            $(".display-username").removeClass("d-none").addClass("d-none");
            $(".display-password").removeClass("d-none").addClass("d-none");

            if(val != null && val != "" && val != undefined){
                if(val == '{{App\Enums\OrderMikrotikEnum::AUTO_USERPASSWORD_FALSE}}'){
                    $(".display-username").removeClass("d-none");
                    $(".display-password").removeClass("d-none");
                }
            }
        })

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

    function getProduct(business_id){
        $.ajax({
            url : '{{route("base.products.index")}}',
            method : "GET",
            data : {
                business_id : business_id
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

        data.code = code;
        data.business_id = '{{Auth::user()->business_id}}';

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
                        let config = "";

                        if(resp.data.mikrotik == '{{App\Enums\ProductEnum::MIKROTIK_PPPOE}}'){
                            config = `<a href="#" class="btn btn-info btn-sm mr-2 mb-2 btn-pppoe" data-index='${index}'>Konfigurasi User</a>`;

                            config += `
                                <div class="modal fade modalPppoe" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered modal-lg">
                                        <div class="modal-content rounded shadow border-0">
                                            <div class="modal-header border-bottom">
                                                <h5 class="modal-title">Pengaturan PPPOE</h5>
                                                <button type="button" class="btn btn-icon btn-close" data-bs-dismiss="modal" id="close-modal"><i class="uil uil-times fs-4 text-dark"></i></button>
                                            </div>
                                            <div class="modal-body">
                                                <input type="hidden" name="repeater[${index}][auto_userpassword]" value="`+'{{\App\Enums\OrderMikrotikEnum::AUTO_USERPASSWORD_FALSE}}'+`" class="auto_userpassword"/>
                                                <div class="form-group mb-3">
                                                    <label>Username</label>
                                                    <input type="text" class="form-control username" placeholder="Username" name="repeater[${index}][username]">
                                                </div>
                                                <div class="form-group mb-3">
                                                    <label>Password</label>
                                                    <input type="text" class="form-control password" placeholder="Password" name="repeater[${index}][password]">
                                                </div>
                                                <div class="row">
                                                    <div class="col-lg-6">
                                                        <div class="form-group mb-3">
                                                            <label>Service</label>
                                                            <select class="form-control service" name="repeater[${index}][service]" style="width:100%">
                                                                <option value="pppoe">PPPOE</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-6">
                                                        <div class="form-group mb-3">
                                                            <label>Profile</label>
                                                            <select class="form-control profile-${index}" style="width:100%" name="repeater[${index}][profile]">
                                                                <option value="">==Pilih Profile</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-lg-6">
                                                        <div class="form-group mb-3">
                                                            <label>Local Address</label>
                                                            <input type="text" class="form-control local-address" placeholder="Local Address" name="repeater[${index}][local_address]">
                                                        </div>
                                                    </div>

                                                    <div class="col-lg-6">
                                                        <div class="form-group mb-3">
                                                            <label>Remote Address</label>
                                                            <input type="text" class="form-control remote-address" placeholder="Remote Address" name="repeater[${index}][remote_address]">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="form-group mb-3">
                                                    <label>Comment</label>
                                                    <input type="text" class="form-control comment" placeholder="Comment" name="repeater[${index}][comment]">
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            `
                        }
                        else if(resp.data.mikrotik == '{{App\Enums\ProductEnum::MIKROTIK_HOTSPOT}}'){
                            config = `<a href="#" class="btn btn-info btn-sm mr-2 mb-2 btn-hotspot" data-index='${index}'>Konfigurasi User</a>`;

                            config += `
                                <div class="modal fade modalHotspot" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered modal-lg">
                                        <div class="modal-content rounded shadow border-0">
                                            <div class="modal-header border-bottom">
                                                <h5 class="modal-title">Pengaturan Hotspot</h5>
                                                <button type="button" class="btn btn-icon btn-close" data-bs-dismiss="modal" id="close-modal"><i class="uil uil-times fs-4 text-dark"></i></button>
                                            </div>
                                            <div class="modal-body">
                                                <div class="form-group mb-3">
                                                    <label>Jenis Pengisian</label>
                                                    <select class="form-control autouserpassword" name="repeater[${index}][auto_userpassword]">
                                                        <option value="">==Pilih Jenis Pengisian==</option>
                                                        <option value="`+'{{\App\Enums\OrderMikrotikEnum::AUTO_USERPASSWORD_TRUE}}'+`">Otomatis</option>
                                                        <option value="`+'{{\App\Enums\OrderMikrotikEnum::AUTO_USERPASSWORD_FALSE}}'+`">Input Manual</option>
                                                    </select>
                                                </div>
                                                <div class="form-group mb-3 display-username d-none">
                                                    <label>Username</label>
                                                    <input type="text" class="form-control username" placeholder="Username" name="repeater[${index}][username]">
                                                </div>
                                                <div class="form-group mb-3 display-password d-none">
                                                    <label>Password</label>
                                                    <input type="text" class="form-control password" placeholder="Password" name="repeater[${index}][password]">
                                                </div>
                                                <div class="form-group mb-3">
                                                    <label>Server</label>
                                                    <select class="form-control select2 server server-${index}" style="width:100%" name="repeater[${index}][server]">
                                                        <option value="">==Pilih Server</option>
                                                    </select>
                                                </div>
                                                <div class="form-group mb-3">
                                                    <label>Profile</label>
                                                    <select class="form-control select2 profile profile-${index}" style="width:100%" name="repeater[${index}][profile]">
                                                        <option value="">==Pilih Profile</option>
                                                    </select>
                                                </div>
                                                <div class="row">
                                                    <div class="col-lg-6">
                                                        <div class="form-group mb-3">
                                                            <label>Address</label>
                                                            <input type="text" class="form-control address" placeholder="Address" name="repeater[${index}][address]">
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-6">
                                                        <div class="form-group mb-3">
                                                            <label>Mac Address</label>
                                                            <input type="text" class="form-control mac-address" placeholder="Mac Address" name="repeater[${index}][mac_address]">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="form-group mb-3">
                                                    <label>Time Limit</label>
                                                    <input type="text" class="form-control time-limit" placeholder="Contoh : 1d4h30m20s" name="repeater[${index}][time_limit]">
                                                </div>
                                                <div class="form-group mb-3">
                                                    <label>Comment</label>
                                                    <input type="text" class="form-control comment" placeholder="Comment" name="repeater[${index}][comment]">
                                                </div>
                                                
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            `
                        }

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
                                        ${config}
                                        <a href="#" class="btn btn-danger btn-sm mr-2 mb-2 btn-delete-product">Hapus</a>
                                    </td>
                                </tr>
                        `;
                        $('.tbody-product').append(html);

                        if(resp.data.mikrotik == '{{App\Enums\ProductEnum::MIKROTIK_PPPOE}}'){
                            getProfilePppoe('.profile-'+index,null);
                        }
                        else if(resp.data.mikrotik == '{{App\Enums\ProductEnum::MIKROTIK_HOTSPOT}}'){
                            getProfileHotspot('.profile-'+index,null);
                            getServerHotspot('.server-'+index,null);
                        }
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

    function getOrder(business_id){
        $.ajax({
            url : '{{route("base.orders.index")}}',
            method : "GET",
            dataType : "JSON",
            data : {
                business_id : business_id
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
                                <td>${element.created_at}</td>
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
            $('.repeater-product').eq(index).find(".btn-pppoe").attr("data-index",index);
            $('.repeater-product').eq(index).find(".btn-hotspot").attr("data-index",index);
            $('.repeater-product').eq(index).find(".auto_userpassword").attr("name","repeater["+index+"][auto_userpassword]");
            $('.repeater-product').eq(index).find(".username").attr("name","repeater["+index+"][username]");
            $('.repeater-product').eq(index).find(".password").attr("name","repeater["+index+"][password]");
            $('.repeater-product').eq(index).find(".service").attr("name","repeater["+index+"][service]");
            $('.repeater-product').eq(index).find(".server").attr("name","repeater["+index+"][server]");
            $('.repeater-product').eq(index).find(".profile").attr("name","repeater["+index+"][profile]");
            $('.repeater-product').eq(index).find(".local-address").attr("name","repeater["+index+"][local_address]");
            $('.repeater-product').eq(index).find(".remote-address").attr("name","repeater["+index+"][remote_address]");
            $('.repeater-product').eq(index).find(".address").attr("name","repeater["+index+"][address]");
            $('.repeater-product').eq(index).find(".mac-address").attr("name","repeater["+index+"][mac_address]");
            $('.repeater-product').eq(index).find(".time-limit").attr("name","repeater["+index+"][time_limit]");
            $('.repeater-product').eq(index).find(".comment").attr("name","repeater["+index+"][comment]");
        });
    }

    function clearTableLatestOrder(){
        $(".tbody-latest-order").html("");
    }
</script>
@endsection