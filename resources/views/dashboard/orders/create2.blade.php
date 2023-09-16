@extends('dashboard.layouts.main')

@section('title', 'Penjualan')

@section('css')
    <!-- Datatables -->
    <link href="{{ URL::to('/') }}/templates/dashboard/assets/libs/datatables/dataTables.bootstrap4.min.css" rel="stylesheet"
        type="text/css" />
    <link href="{{ URL::to('/') }}/templates/dashboard/assets/libs/datatables/buttons.bootstrap4.min.css" rel="stylesheet"
        type="text/css" />
    <!-- Responsive datatable examples -->
    <link href="{{ URL::to('/') }}/templates/dashboard/assets/libs/datatables/responsive.bootstrap4.min.css" rel="stylesheet"
        type="text/css" />
    <!-- Datetimepicker -->
    <link href="{{ URL::to('/') }}/templates/dashboard/assets/libs/datetimepicker/jquery.datetimepicker.css" type="text/css"
        rel="stylesheet" />
    <link href="{{ URL::to('/') }}/templates/dashboard/assets/libs/owl.carousel/dist/assets/owl.carousel.min.css"
        type="text/css" rel="stylesheet" />
    <link href="{{ URL::to('/') }}/templates/dashboard/assets/libs/owl.carousel/dist/assets/owl.theme.default.min.css"
        type="text/css" rel="stylesheet" />
    <style>
        .page-wrapper {
            height: auto !important;
        }

        .owl-dots {
            display: none
        }

        .owl-nav button {
            background-color: white;
            border: none;
            color: black;
            padding-left: 9px;
            padding-right: 9px;
            border-radius: 50%;
            transition: background-color 0.3s, color 0.3s, border-radius 0.3s;
        }

        .owl-nav button:hover {
            background-color: blue;
            color: white;
        }

        .square {
            width: 100%;
            aspect-ratio: 1/1;
        }

        .product_card .anjay,
        .product_card img {
            transition: 0.5s
        }

        .product_card:hover img {
            transform: scale(140%);
        }

        .product_card .anjay {
            border: 1px solid transparent;
            transition: 0.5s
        }

        .product_card .check_select {
            display: none
        }

        .product_card:hover .anjay,
        .product_card.active .anjay {
            border: 1px solid blue;
        }

        .product_card.active .check_select {
            display: block
        }

        .product-details img {
            height: 50px !important
        }

        @media(max-width:768px) {
            .product_card .anjay {
                padding-left: 100px;
                padding-right: 100px;
            }

            .product_card:hover .anjay {
                padding-left: 98px;
                padding-right: 98px;
            }
        }
    </style>
@endsection

<div style="margin-top: -30px">
    @section('content')
        <div class="row">
            <div class="col-12 col-lg-8">
                <div class="row mb-3">
                    <div class="col-sm-12">
                        <h3 class="page-title">Penjualan</h3>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><a href="#">Penjualan</a></li>
                            <li class="breadcrumb-item active">Create</li>
                        </ul>
                    </div>
                </div>

                <div class="d-flex justify-content-end mb-2">
                    <div class="owl-nav">
                        <button id="prevButton" type="button" role="presentation" class="owl-prev disabled"><span
                                aria-label="Previous">‹</span></button>
                        <button id="nextButton" type="button" role="presentation" class="owl-next"><span
                                aria-label="Next">›</span></button>
                    </div>
                </div>
                <ul class="owl-carousel owl-theme">
                    @foreach ($product_category as $index => $item)
                        <li class="" id="fruits">
                            <div class="product-details position-relative {{ $index == 0 ? 'active' : '' }}"
                                data-section="#prdctg{{ $item->id }}">
                                <img src="{{ $item->image() }}" alt="{{ $item->name }}"
                                    onerror="this.src='{{ asset('assets/placeholder-image.webp') }}'">
                                <h6>{{ $item->name }}</h6>
                            </div>
                        </li>
                    @endforeach
                </ul>

                @foreach ($product_category as $index => $category)
                    <div class="prd_section row mt-4 {{ $index != 0 ? 'd-none' : '' }}" id="prdctg{{ $category->id }}">
                        @if ($category->products->count() > 0)
                            @foreach ($category->products as $product)
                                <div class="product_card col-12 col-md-4 mb-4 position-relative"
                                    data-id="{{ $product->id }}" data-code="{{ $product->code }}"
                                    id="product_card{{ $product->code }}">
                                    <div class="anjay bg-white rounded overflow-hidden shadow-sm"
                                        id="product{{ $product->id }}">
                                        <div class="overflow-hidden square">
                                            <img src="{{ $product->image() }}"
                                                style="object-fit: cover; width: 100%; height: 100%;"
                                                onerror="this.src='{{ asset('assets/placeholder-image.webp') }}'">
                                        </div>
                                        <div class="text-center p-3">
                                            <small class="d-block text-muted">{{ $category->name }}</small>
                                            <h6>{{ $product->name }}</h6>
                                            <p class="text-danger mt-2">Rp {{ number_format($product->price) }}</p>
                                        </div>
                                    </div>
                                    <div class="position-absolute top-0 bg-primary text-white p-1 check_select">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="20"
                                            viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                            stroke-linecap="round" stroke-linejoin="round" class="feather feather-check">
                                            <path d="M20 6L9 17l-5-5"></path>
                                        </svg>
                                    </div>
                                </div>
                            @endforeach
                        @else
                            <h4 class="text-center mt-5">Tidak Ada Produk</h4>
                        @endif
                    </div>
                @endforeach
            </div>
            <div class="col-12 col-lg-4">

                <div class="order-list">
                    <div class="orderid">
                        <h4>Order List</h4>
                        <h5>Transaction id : #65565</h5>
                    </div>
                </div>
                <form action="{{ route('dashboard.orders.store') }}" id="frmStore" autocomplete="off">
                    @csrf
                    <div class="d-none">
                        <input type="text" class="form-control code" placeholder="Pilih Produk">
                        <input type="number" class="form-control input-qty" placeholder="Quantity" value="1">
                        <button type="button" class="btn btn-primary btn-sm btn-submit-product"><i class="fa fa-plus"></i>
                            Tambah</button>
                        <input type="text" class="form-control input-subtotal" placeholder="Sub Total" value="0"
                            readonly disabled>
                        <input type="text" class="form-control input-total" placeholder="Grand Total" value="0"
                            readonly disabled>
                    </div>

                    <div class="card card-order">
                        <div class="card-body">
                            <div class="form-group row mb-3">
                                <label class="col-md-5 col-form-label">Tanggal </label>
                                <div class="col-md-7">
                                    <input type="text" class="form-control" placeholder="Tanggal"
                                        value="{{ date('d-m-Y') }}" readonly disabled>
                                </div>
                            </div>
                            <div class="form-group row mb-3">
                                <label class="col-md-5 col-form-label">Customer</label>
                                <div class="col-md-7">
                                    <select class="form-control select2 select-customer" name="customer_id"
                                        style="width: 100%;">
                                        <option value="">==Umum==</option>
                                    </select>
                                </div>
                            </div>
                            <div class="display-general-customer">
                                <div class="form-group row mb-3">
                                    <label class="col-md-5 col-form-label">Nama Customer</label>
                                    <div class="col-md-7">
                                        <input type="text" class="form-control" name="customer_name"
                                            placeholder="Nama Customer">
                                    </div>
                                </div>
                                <div class="form-group row mb-3">
                                    <label class="col-md-5 col-form-label">Telp. Customer</label>
                                    <div class="col-md-7">
                                        <input type="text" class="form-control" name="customer_phone"
                                            placeholder="Telp. Customer">
                                    </div>
                                </div>
                                <div class="form-group row mb-3">
                                    <label class="col-md-5 col-form-label">Email Customer</label>
                                    <div class="col-md-7">
                                        <input type="email" class="form-control" name="customer_email"
                                            placeholder="Email Customer">
                                    </div>
                                </div>
                            </div>
                            @if (in_array(Auth::user()->business->category->name ?? null, [\App\Enums\BusinessCategoryEnum::FNB]))
                                <div class="form-group row mb-3">
                                    <label class="col-md-5 col-form-label">Dine In/Take Away</label>
                                    <div class="col-md-7">
                                        <select class="form-control select2" name="fnb_type" style="width: 100%;">
                                            @foreach ($fnb_type as $index => $row)
                                                <option value="{{ $index }}">{{ $row }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group row mb-3">
                                    <label class="col-md-5 col-form-label">Meja</label>
                                    <div class="col-md-7">
                                        <select class="form-control select2 select-table" name="table_id"
                                            style="width: 100%;">
                                            <option value="">==Pilih Meja==</option>
                                        </select>
                                    </div>
                                </div>
                            @endif
                        </div>
                        <div class="split-card">
                        </div>
                        <div class="card-body pt-0 pb-4">
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
                        <div class="split-card">
                        </div>
                        <div class="card-body pt-0 pb-2">
                            <div class="setvalue">
                                <ul>
                                    <li>
                                        <h5>Subtotal </h5>
                                        <h6 class="text-subtotal">0</h6>
                                    </li>
                                    <li class="form-group row">
                                        <label class="col-md-5 col-form-label">Diskon</label>
                                        <div class="col-md-7">
                                            <input type="text" class="form-control input-discount"
                                                placeholder="Diskon" value="0" name="discount">
                                        </div>
                                    </li>
                                    <li class="total-value">
                                        <h5>Total </h5>
                                        <h6 class="text-total">0</h6>
                                    </li>
                                </ul>
                            </div>
                            <div class="setvaluecash">
                                <input type="hidden" name="type" value="1">
                                <div class="display-due-date d-none">
                                    <div class="form-group row mb-3">
                                        <label>Jatuh Tempo Setiap Tanggal</label>
                                        <select class="form-control select2" name="repeat_order_at" style="width: 100%;">
                                            <option value="">==Pilih Tanggal==</option>
                                            @foreach (\DateHelper::date1to28() as $index => $row)
                                                <option value="{{ $row }}">{{ $row }}</option>
                                            @endforeach
                                        </select>
                                        {{-- <p class="text-info" style="margin-top: 0px;margin-bottom: 0px;padding-top: 0px;padding-bottom: 0px;"><small><i>Kosong = Tagihan baru di 30 hari kedepan</i></small></p> --}}
                                    </div>
                                </div>
                                <div class="display-repeat-interval d-none">
                                    <div class="form-group row mb-3">
                                        <label>Interval Jatuh Tempo</label>
                                        <select class="form-control select2" name="repeat_interval" style="width: 100%;">
                                            <option value="">==Pilih Interval==</option>
                                            <option value="1">1 Bulan</option>
                                            <option value="3">3 Bulan</option>
                                            <option value="6">6 Bulan</option>
                                            <option value="12">12 Bulan</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group row mb-3">
                                    <label>Metode Pembayaran</label>
                                    <select class="form-control select2" name="provider_id">
                                        @foreach ($providers as $index => $row)
                                            <option value="{{ $row->id }}"
                                                @if ($row->id == old('provider_id')) selected @endif>{{ $row->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label>Catatan</label>
                                    <textarea name="note" class="form-control" rows="5"></textarea>
                                </div>
                                <button type="submit" class="btn btn-totallabel w-100" disabled>
                                    <h5>Checkout</h5>
                                    <h6 class="text-total">0</h6>
                                </button>
                            </div>
                        </div>
                    </div>
                </form>

            </div>
        </div>

        @include('dashboard.orders.modal.create')
        @include('dashboard.components.loader')

    @endsection
</div>

@section('script')
    <!-- Datatables -->
    <script src="{{ URL::to('/') }}/templates/dashboard/assets/libs/parsleyjs/parsley.min.js"></script>
    <script src="{{ URL::to('/') }}/templates/dashboard/assets/libs/datatables/jquery.dataTables.min.js"></script>
    <script src="{{ URL::to('/') }}/templates/dashboard/assets/libs/datatables/dataTables.bootstrap4.min.js"></script>
    <script src="{{ URL::to('/') }}/assets/pages/datatables.init.js"></script>
    <script src="{{ URL::to('/') }}/templates/dashboard/assets/libs/datatables/dataTables.responsive.min.js"></script>
    <script src="{{ URL::to('/') }}/templates/dashboard/assets/libs/datatables/responsive.bootstrap4.min.js"></script>
    <!-- Datetimepicker -->
    <script src="{{ URL::to('/') }}/templates/dashboard/assets/libs/moment/moment.min.js"></script>
    <script src="{{ URL::to('/') }}/templates/dashboard/assets/libs/datetimepicker/jquery.datetimepicker.min.js"></script>
    <script src="{{ URL::to('/') }}/templates/dashboard/assets/libs/axios/axios.min.js"></script>
    <script src="{{ URL::to('/') }}/templates/dashboard/assets/libs/owl.carousel/dist/owl.carousel.min.js"></script>
    <script>
        $(function() {
            ///////////////////ENTERCODE//////////////////
            $(document).ready(function() {
                $('.owl-carousel').owlCarousel({
                    margin: 10,
                    nav: false,
                    responsive: {
                        140: {
                            items: 2
                        },
                        992: {
                            items: 6
                        }
                    }
                });
                $('#prevButton').click(function() {
                    $('.owl-carousel').trigger('prev.owl.carousel');
                });

                $('#nextButton').click(function() {
                    $('.owl-carousel').trigger('next.owl.carousel');
                });
            })

            $('.product_card').on('click', function() {
                let self = $(this)
                let id = $(this).data("id");
                let code = $(this).data("code");

                if (self.hasClass('active')) {
                    self.removeClass('active');
                    $('#btn-delete-product' + code).trigger('click')
                } else {
                    self.addClass('active');
                    if (code == null || code == undefined || code == "") {
                        responseFailed("Kode produk tidak boleh kosong");
                        return false;
                    }
                    getProductShow(code, 1);
                }
            })

            $('.product-details').on('click', function() {
                let self = $(this)
                let product_section = $(self.data('section'))
                $('.product-details').removeClass('active');
                self.addClass('active');

                $('.prd_section').addClass('d-none')
                product_section.removeClass('d-none')
            })
            ////////////////END ENTERCODE////////////////////

            $.datetimepicker.setDateFormatter('moment');
            $.datetimepicker.setLocale('id');

            $('.datetimepicker').datetimepicker({
                format: 'YYYY-MM-DD HH:mm:ss',
                formatTime: 'HH:mm:ss',
                formatDate: 'YYYY-MM-DD'
            });

            $(".page-wrapper").removeClass("toggled");

            $('button[type="submit"]').attr("disabled", false);

            @if (!empty(Auth::user()->business_id))
                getProduct('{{ Auth::user()->business_id }}', null);
                getCustomer('.select-customer', '{{ Auth::user()->business_id }}', null);
                getOrder('{{ Auth::user()->business_id }}', null);
                getTable('.select-table', '{{ Auth::user()->business_id }}', null);
            @endif

            $(document).on("click", ".btn-show-product", function(e) {
                e.preventDefault();
                $('#modalAddProduct').modal("show");
            });

            $(document).on("change", ".select-customer", function(e) {
                e.preventDefault();

                let val = $(this).val();

                if (val != null && val != undefined && val != "") {
                    $('.display-general-customer').removeClass("d-none").addClass("d-none");
                } else {
                    $('.display-general-customer').removeClass("d-none");
                }

            });

            $(document).on("change", ".select-type", function(e) {
                e.preventDefault();

                let val = $(this).val();

                if (val == '{{ \App\Enums\OrderEnum::TYPE_ON_TIME_PAY }}') {
                    $('.display-due-date').removeClass("d-none").addClass("d-none");
                    $('.display-repeat-interval').removeClass("d-none").addClass("d-none");
                    $('.display-expired-month').removeClass("d-none");
                } else {
                    $('.display-due-date').removeClass("d-none");
                    $('.display-repeat-interval').removeClass("d-none");
                    $('.display-expired-month').removeClass("d-none").addClass("d-none");
                }

            });

            $(document).on("keyup input paste", ".tbody-product-qty", function(e) {
                e.preventDefault();

                generateSubTotalRow($(this).parent().parent());
                generateTotal();

            });

            $(document).on("keyup input paste", ".tbody-product-discount", function(e) {
                e.preventDefault();

                let val = $(this).val();

                $(this).val(formatRupiah(val, undefined));

                generateSubTotalRow($(this).parent().parent());
                generateTotal();

            });

            $(document).on("keyup input paste", ".input-discount", function(e) {
                e.preventDefault();

                let val = $(this).val();

                $(this).val(formatRupiah(val, undefined));

                generateTotal();

            });

            $(document).on("click", ".btn-delete-product", function(e) {
                e.preventDefault();
                $(this).parent().parent().remove();
                sortTableProduct();
                generateTotal();
            });

            $(document).on("click", ".btn-pppoe", function(e) {
                e.preventDefault();
                let index = $(this).attr("data-index");

                $(this).next().modal("show");
            });

            $(document).on("click", ".btn-hotspot", function(e) {
                e.preventDefault();
                let index = $(this).attr("data-index");

                $(this).next().modal("show");
            });

            $(document).on("change", ".auto_userpassword", function(e) {
                e.preventDefault();

                let val = $(this).val();

                $(".display-username").removeClass("d-none").addClass("d-none");
                $(".display-password").removeClass("d-none").addClass("d-none");

                if (val != null && val != "" && val != undefined) {
                    if (val == '{{ App\Enums\OrderMikrotikEnum::AUTO_USERPASSWORD_FALSE }}') {
                        $(".display-username").removeClass("d-none");
                        $(".display-password").removeClass("d-none");
                    }
                }
            })

            $(document).on("change", ".select-profile-pppoe", function(e) {
                e.preventDefault();

                let $this = $(this);
                let val = $this.val();
                let mikrotik_id = $this.parent().parent().parent().parent().find(
                        ".mikrotik_config_id")
                    .val();

                console.log($this.parent().parent().parent().parent().find(
                    ".mikrotik_config_id"));

                if (val != "" && val != null && val != undefined) {
                    $.ajax({
                        url: '{{ route('base.mikrotik-configs.detailProfilePppoe', ['mikrotik_id' => '_mikrotik_id_', 'name' => '_name_']) }}'
                            .replace("_mikrotik_id_", mikrotik_id).replace("_name_",
                                val),
                        method: "GET",
                        dataType: "JSON",
                        beforeSend: function() {
                            return openLoader();
                        },
                        success: function(resp) {
                            if (resp.success == false) {
                                responseFailed(resp.message);
                            } else {
                                $this.parent().parent().parent().parent().find(
                                        ".local-address")
                                    .val(resp.data.local_address);
                                $this.parent().parent().parent().parent().find(
                                    ".remote-address").val(resp.data
                                    .remote_address);
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
            });

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
                                    "{{ route('dashboard.orders.create') }}");
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
        })

        function getProduct(business_id) {
            $.ajax({
                url: '{{ route('base.products.index') }}',
                method: "GET",
                data: {
                    business_id: business_id
                },
                dataType: "JSON",
                beforeSend: function() {
                    return openLoader();
                },
                success: function(resp) {
                    if (resp.success == false) {
                        responseFailed(resp.message);
                        $('.tbody-modal-product').html(
                            '<tr><td class="text-center" colspan="7">Produk Tidak Ditemukan</td></tr>'
                        );
                    } else {
                        let html = "";
                        $.each(resp.data, function(index, element) {
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

        function getProductShow(code, inputQty = 1) {
            let data = {};

            data.code = code;
            data.business_id = '{{ Auth::user()->business_id }}';

            $.ajax({
                url: '{{ route('base.products.showByCode') }}',
                method: "GET",
                dataType: "JSON",
                data: data,
                beforeSend: function() {
                    return openLoader();
                },
                success: function(resp) {
                    if (resp.success == false) {
                        responseFailed(resp.message);
                    } else {
                        let index = 0;

                        $('.repeater-product').each(function(index, element) {
                            index += 1;
                        });

                        let total = inputQty * resp.data.price;

                        if ($('.tbody-product-' + resp.data.id).length >= 1) {
                            let existQty = $('.tbody-product-' + resp.data.id).find(
                                ".tbody-product-qty").val();
                            inputQty = parseInt(inputQty) + parseInt(existQty);
                            total = inputQty * resp.data.price;

                            $('.tbody-product-' + resp.data.id).find(".tbody-product-qty").val(
                                inputQty);
                            $('.tbody-product-' + resp.data.id).find(".tbody-product-total").html(
                                formatRupiah(
                                    total, undefined));

                            generateTotal();

                            return false;
                        } else {
                            let config = "";

                            if (resp.data.mikrotik ==
                                '{{ App\Enums\ProductEnum::MIKROTIK_PPPOE }}') {
                                config =
                                    `<a href="#" class="btn btn-info btn-sm mr-2 mb-2 btn-pppoe" data-index='${index}'>Konfigurasi User</a>`;

                                config += `
                                <div class="modal fade modalPppoe" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered modal-lg">
                                        <div class="modal-content rounded shadow border-0">
                                            <div class="modal-header border-bottom">
                                                <h5 class="modal-title">Pengaturan PPPOE</h5>
                                                <button type="button" class="btn btn-icon btn-close" data-bs-dismiss="modal" id="close-modal"><i class="uil uil-times fs-4 text-dark"></i></button>
                                            </div>
                                            <div class="modal-body">
                                                <input type="hidden" value="` + echo(resp.data.mikrotik_config_id) + `" class="mikrotik_config_id"/>
                                                <input type="hidden" value="` +
                                    '{{ \App\Enums\OrderMikrotikEnum::AUTO_USERPASSWORD_FALSE }}' +
                                    `" class="auto_userpassword"/>
                                                <div class="form-group mb-3">
                                                    <label>Username<span class="text-danger">*</span></label>
                                                    <input type="text" class="form-control username" placeholder="Username" name="repeater[${index}][username]">
                                                </div>
                                                <div class="form-group mb-3">
                                                    <label>Password<span class="text-danger">*</span></label>
                                                    <input type="text" class="form-control password" placeholder="Password" name="repeater[${index}][password]">
                                                </div>
                                                <div class="row">
                                                    <div class="col-lg-6">
                                                        <div class="form-group mb-3">
                                                            <label>Service<span class="text-danger">*</span></label>
                                                            <select class="form-control service" name="repeater[${index}][service]" style="width:100%">
                                                                <option value="any">any</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-6">
                                                        <div class="form-group mb-3">
                                                            <label>Profile<span class="text-danger">*</span></label>
                                                            <select class="form-control profile-${resp.data.id} select-profile-pppoe profile" style="width:100%" name="repeater[${index}][profile]">
                                                                <option value="">==Pilih Profile</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-lg-6">
                                                        <div class="form-group mb-3">
                                                            <label>Local Address<span class="text-danger">*</span></label>
                                                            <input type="text" class="form-control local-address" placeholder="Local Address" name="repeater[${index}][local_address]" value="` +
                                    echo(resp.data.local_address) +
                                    `">
                                                        </div>
                                                    </div>

                                                    <div class="col-lg-6">
                                                        <div class="form-group mb-3">
                                                            <label>Remote Address<span class="text-danger">*</span></label>
                                                            <input type="text" class="form-control remote-address" placeholder="Remote Address" name="repeater[${index}][remote_address]" value="` +
                                    echo(resp.data.remote_address) + `">
                                                        </div>
                                                    </div>
                                                </div>`;
                                if ($('select[name="type"]').val() ==
                                    '{{ App\Enums\OrderEnum::TYPE_ON_TIME_PAY }}') {
                                    config += `
                                                        <div class="display-expired-month">
                                                            <div class="form-group mb-3">
                                                                <label>Berlaku Hingga</label>
                                                                <div class="input-group">
                                                                    <input type="number" class="form-control expired-month" placeholder="Berlaku Hingga" name="repeater[${index}][expired_month]" value="${echo(resp.data.expired_month)}">
                                                                    <div class="input-group-append">
                                                                        <span class="input-group-text">BULAN</span>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    `;
                                }
                                config +=
                                    `
                                                <div class="form-group mb-3">
                                                    <label>Comment</label>
                                                    <input type="text" class="form-control comment" placeholder="Comment" name="repeater[${index}][comment]" value="` +
                                    echo(resp.data.comment) + `">
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            `
                            } else if (resp.data.mikrotik ==
                                '{{ App\Enums\ProductEnum::MIKROTIK_HOTSPOT }}') {
                                config =
                                    `<a href="#" class="btn btn-info btn-sm mr-2 mb-2 btn-hotspot" data-index='${index}'>Konfigurasi User</a>`;

                                config += `
                                <div class="modal fade modalHotspot" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered modal-lg">
                                        <div class="modal-content rounded shadow border-0">
                                            <div class="modal-header border-bottom">
                                                <h5 class="modal-title">Pengaturan Hotspot</h5>
                                                <button type="button" class="btn btn-icon btn-close" data-bs-dismiss="modal" id="close-modal"><i class="uil uil-times fs-4 text-dark"></i></button>
                                            </div>
                                            <div class="modal-body">
                                                <input type="hidden" value="` + echo(resp.data.mikrotik_config_id) + `" class="mikrotik_config_id"/>
                                                <div class="form-group mb-3">
                                                    <label>Jenis Pengisian Username dan Password<span class="text-danger">*</span></label>
                                                    <select class="form-control auto_userpassword" name="repeater[${index}][auto_userpassword]">
                                                        <option value="">==Pilih Jenis Pengisian Username dan Password==</option>
                                                        <option value="` +
                                    '{{ \App\Enums\OrderMikrotikEnum::AUTO_USERPASSWORD_TRUE }}' + `">Otomatis</option>
                                                        <option value="` +
                                    '{{ \App\Enums\OrderMikrotikEnum::AUTO_USERPASSWORD_FALSE }}' +
                                    `">Input Manual</option>
                                                    </select>
                                                </div>
                                                <div class="form-group mb-3 display-username d-none">
                                                    <label>Username<span class="text-danger">*</span></label>
                                                    <input type="text" class="form-control username" placeholder="Username" name="repeater[${index}][username]">
                                                </div>
                                                <div class="form-group mb-3 display-password d-none">
                                                    <label>Password<span class="text-danger">*</span></label>
                                                    <input type="text" class="form-control password" placeholder="Password" name="repeater[${index}][password]">
                                                </div>
                                                <div class="form-group mb-3">
                                                    <label>Server<span class="text-danger">*</span></label>
                                                    <select class="form-control select2 server server-${resp.data.id}" style="width:100%" name="repeater[${index}][server]">
                                                        <option value="">==Pilih Server</option>
                                                    </select>
                                                    <p class="text-info" style="margin-top: 0px;margin-bottom: 0px;padding-top: 0px;padding-bottom: 0px;"><small><i>Nama server akan ditampikan sebagai SSID</i></small></p>
                                                </div>
                                                <div class="form-group mb-3">
                                                    <label>Profile<span class="text-danger">*</span></label>
                                                    <select class="form-control select2 profile profile-${resp.data.id}" style="width:100%" name="repeater[${index}][profile]">
                                                        <option value="">==Pilih Profile</option>
                                                    </select>
                                                </div>
                                                <div class="row">
                                                    <div class="col-lg-6">
                                                        <div class="form-group mb-3">
                                                            <label>Address</label>
                                                            <input type="text" class="form-control address" placeholder="Address" name="repeater[${index}][address]" value="` +
                                    echo(
                                        resp.data.address) +
                                    `">
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-6">
                                                        <div class="form-group mb-3">
                                                            <label>Mac Address</label>
                                                            <input type="text" class="form-control mac-address" placeholder="Mac Address" name="repeater[${index}][mac_address]" value="` +
                                    echo(resp.data.mac_address) +
                                    `">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="form-group mb-3">
                                                    <label>Time Limit<span class="text-danger">*</span></label>
                                                    <input type="text" class="form-control time-limit" placeholder="Contoh : 1d4h30m20s" name="repeater[${index}][time_limit]" value="` +
                                    echo(resp.data.time_limit) +
                                    `">
                                                </div>
                                                <div class="form-group mb-3">
                                                    <label>Comment</label>
                                                    <input type="text" class="form-control comment" placeholder="Comment" name="repeater[${index}][comment]" value="` +
                                    echo(resp.data.comment) + `">
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
                                        <a href="#" class="btn btn-danger btn-sm mr-2 mb-2 btn-delete-product" id="btn-delete-product${resp.data.code}" onclick="$('#product_card${resp.data.code}').removeClass('active')">Hapus</a>
                                    </td>
                                </tr>
                        `;
                            $('.tbody-product').append(html);

                            if (resp.data.mikrotik ==
                                '{{ App\Enums\ProductEnum::MIKROTIK_PPPOE }}') {
                                getProfilePppoe('.profile-' + resp.data.id, resp.data
                                    .mikrotik_config_id, resp
                                    .data.profile);
                            } else if (resp.data.mikrotik ==
                                '{{ App\Enums\ProductEnum::MIKROTIK_HOTSPOT }}') {
                                getProfileHotspot('.profile-' + resp.data.id, resp.data
                                    .mikrotik_config_id, resp
                                    .data.profile);
                                getServerHotspot('.server-' + resp.data.id, resp.data
                                    .mikrotik_config_id, resp
                                    .data.server);
                            }
                        }

                        sortTableProduct();

                        generateTotal();
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

        function getOrder(business_id) {
            $.ajax({
                url: '{{ route('base.orders.index') }}',
                method: "GET",
                dataType: "JSON",
                data: {
                    business_id: business_id
                },
                beforeSend: function() {
                    return openLoader();
                },
                success: function(resp) {
                    if (resp.success == false) {
                        responseFailed(resp.message);
                    } else {
                        let html = "";

                        $.each(resp.data, function(index, element) {
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

        function generateSubTotalRow(parent) {
            let qty = $(parent).find(".tbody-product-qty").val();

            let price = $(parent).find(".tbody-product-price").html();
            let discount = $(parent).find(".tbody-product-discount").val();

            price = price.split(".").join("");
            discount = discount.split(".").join("");

            qty = parseInt(qty);
            price = parseInt(price);
            discount = parseInt(discount);

            if (isNaN(qty)) {
                qty = 0;
            }

            if (isNaN(price)) {
                price = 0;
            }

            if (isNaN(discount)) {
                discount = 0;
            }

            let subtotal = (qty * price) - discount;
            $(parent).find(".tbody-product-total").html(formatRupiah(subtotal, undefined));
        }

        function generateTotal() {
            let subtotal = 0;
            let total = 0;
            let discount = $('.input-discount').val();
            let total_discount = 0;

            discount = discount.split(".").join("");

            discount = parseInt(discount);

            if (isNaN(discount)) {
                discount = 0;
            }

            $('.repeater-product').each(function(index, element) {
                let price = $('.repeater-product').eq(index).find(".tbody-product-price").html();
                let qty = $('.repeater-product').eq(index).find(".tbody-product-qty").val();
                let disc = $('.repeater-product').eq(index).find(".tbody-product-discount").val();

                price = price.split(".").join("");
                disc = disc.split(".").join("");

                price = parseInt(price);
                qty = parseInt(qty);
                disc = parseInt(disc);

                if (isNaN(price)) {
                    price = 0;
                }

                if (isNaN(qty)) {
                    qty = 0;
                }

                if (isNaN(disc)) {
                    disc = 0;
                }

                subtotal += (price * qty) - disc;
                total_discount += disc;
            });

            total_discount += discount;
            total = subtotal - discount;

            if (total <= 0 || total_discount >= subtotal) {
                subtotal = 0;
                total = subtotal;
            }

            $('.input-subtotal').val(formatRupiah(subtotal, undefined));
            $('.input-total').val(formatRupiah(total, undefined));

            $('.text-subtotal').html(formatRupiah(subtotal, undefined));
            $('.text-total').html(formatRupiah(total, undefined));

        }

        function sortTableProduct() {
            $('.repeater-product').each(function(index, element) {
                $('.repeater-product').eq(index).find(".tbody-product-number").html(index + 1);
                $('.repeater-product').eq(index).find(".tbody-product-id").attr("name", "repeater[" +
                    index +
                    "][product_id]");
                $('.repeater-product').eq(index).find(".tbody-product-qty").attr("name", "repeater[" +
                    index +
                    "][qty]");
                $('.repeater-product').eq(index).find(".tbody-product-discount").attr("name",
                    "repeater[" +
                    index +
                    "][discount]");
                $('.repeater-product').eq(index).find(".btn-pppoe").attr("data-index", index);
                $('.repeater-product').eq(index).find(".btn-hotspot").attr("data-index", index);
                $('.repeater-product').eq(index).find(".auto_userpassword").attr("name", "repeater[" +
                    index +
                    "][auto_userpassword]");
                $('.repeater-product').eq(index).find(".username").attr("name", "repeater[" + index +
                    "][username]");
                $('.repeater-product').eq(index).find(".password").attr("name", "repeater[" + index +
                    "][password]");
                $('.repeater-product').eq(index).find(".service").attr("name", "repeater[" + index +
                    "][service]");
                $('.repeater-product').eq(index).find(".server").attr("name", "repeater[" + index +
                    "][server]");
                $('.repeater-product').eq(index).find(".profile").attr("name", "repeater[" + index +
                    "][profile]");
                $('.repeater-product').eq(index).find(".local-address").attr("name", "repeater[" +
                    index +
                    "][local_address]");
                $('.repeater-product').eq(index).find(".remote-address").attr("name", "repeater[" +
                    index +
                    "][remote_address]");
                $('.repeater-product').eq(index).find(".expired-month").attr("name", "repeater[" +
                    index +
                    "][expired_month]");
                $('.repeater-product').eq(index).find(".address").attr("name", "repeater[" + index +
                    "][address]");
                $('.repeater-product').eq(index).find(".mac-address").attr("name", "repeater[" + index +
                    "][mac_address]");
                $('.repeater-product').eq(index).find(".time-limit").attr("name", "repeater[" + index +
                    "][time_limit]");
                $('.repeater-product').eq(index).find(".comment").attr("name", "repeater[" + index +
                    "][comment]");
            });
        }

        function clearTableLatestOrder() {
            $(".tbody-latest-order").html("");
        }

        function echo(val) {
            if (val != null && val != undefined && val != "") {
                return val;
            } else {
                return "";
            }
        }
    </script>
@endsection
