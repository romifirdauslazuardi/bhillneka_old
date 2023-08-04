@extends("dashboard.layouts.main")

@section("title","Produk")

@section("css")
<!-- Datatables -->
<link href="{{URL::to('/')}}/templates/dashboard/assets/libs/datatables/dataTables.bootstrap4.min.css" rel="stylesheet" type="text/css" />
<link href="{{URL::to('/')}}/templates/dashboard/assets/libs/datatables/buttons.bootstrap4.min.css" rel="stylesheet" type="text/css" />
<!-- Responsive datatable examples -->
<link href="{{URL::to('/')}}/templates/dashboard/assets/libs/datatables/responsive.bootstrap4.min.css" rel="stylesheet" type="text/css" />
@endsection

@section("breadcumb")
<div class="d-md-flex justify-content-between align-items-center">
    <h5 class="mb-0">Produk</h5>

    <nav aria-label="breadcrumb" class="d-inline-block mt-2 mt-sm-0">
        <ul class="breadcrumb bg-transparent rounded mb-0 p-0">
            <li class="breadcrumb-item text-capitalize"><a href="#">Produk</a></li>
            <li class="breadcrumb-item text-capitalize active" aria-current="page">Edit</li>
        </ul>
    </nav>
</div>
@endsection

@section("content")
<div class="row pb-2">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title mb-3">Informasi Data Produk</h5>
                <ul class="nav nav-tabs">
                    <li class="nav-item">
                        <a class="nav-link active" data-bs-toggle="tab" href="#product">Data Produk</a>
                    </li>

                    @if(in_array(Auth::user()->business->category->name ?? null,[\App\Enums\BusinessCategoryEnum::MIKROTIK]))
                    <li class="nav-item">
                        <a class="nav-link" data-bs-toggle="tab" href="#configuration">Konfigurasi User Mikrotik</a>
                    </li>
                    @endif
                </ul>
                <!-- Tab panes -->
                <div class="tab-content pt-3">
                    <div class="tab-pane container active" id="product">
                        <div class="row">
                            <div class="col-12">
                                @if(in_array(Auth::user()->business->category->name ?? null,[\App\Enums\BusinessCategoryEnum::FNB]))
                                <div class="row mb-2">
                                    <div class="col-md-3">
                                        Foto Produk
                                    </div>
                                    <div class="col-md-8">
                                        : @if(!empty($result->image)) <a href="{{asset($result->image)}}"><img src="{{asset($result->image)}}" style="width:100px;height:100px;"></a> @endif
                                    </div>
                                </div>
                                @endif

                                @if(in_array($result->mikrotik,[\App\Enums\ProductEnum::MIKROTIK_HOTSPOT,\App\Enums\ProductEnum::MIKROTIK_PPPOE]))
                                <div class="row mb-2">
                                    <div class="col-md-3">
                                        Mikrotik
                                    </div>
                                    <div class="col-md-8">
                                        : {{$result->mikrotik() ?? null}}
                                    </div>
                                </div>
                                @endif

                                <div class="row mb-2">
                                    <div class="col-md-3">
                                        Kode Produk
                                    </div>
                                    <div class="col-md-8">
                                        : {{$result->code}}
                                    </div>
                                </div>

                                <div class="row mb-2">
                                    <div class="col-md-3">
                                        Nama Produk
                                    </div>
                                    <div class="col-md-8">
                                        : {{$result->name}}
                                    </div>
                                </div>

                                <div class="row mb-2">
                                    <div class="col-md-3">
                                        Slug
                                    </div>
                                    <div class="col-md-8">
                                        : {{$result->slug}}
                                    </div>
                                </div>

                                <div class="row mb-2">
                                    <div class="col-md-3">
                                        Harga Produk
                                    </div>
                                    <div class="col-md-8">
                                        : {{number_format($result->price,0,',','.')}}
                                    </div>
                                </div>

                                <div class="row mb-2">
                                    <div class="col-md-3">
                                        Estimasi Pendapatan
                                    </div>
                                    <div class="col-md-8">
                                        : {{number_format($result->estimationAgenIncome,0,',','.')}}
                                    </div>
                                </div>

                                <div class="row mb-2">
                                    <div class="col-md-3">
                                        Estimasi Biaya Layanan & Aplikasi
                                    </div>
                                    <div class="col-md-8">
                                        : {{number_format($result->estimationOwnerIncome,0,',','.')}}
                                    </div>
                                </div>

                                @if(in_array(Auth::user()->business->category->name ?? null,[\App\Enums\BusinessCategoryEnum::BARANG]))
                                <div class="row mb-2">
                                    <div class="col-md-3">
                                        Berat Produk
                                    </div>
                                    <div class="col-md-8">
                                        : {{$result->weight() ?? null}}
                                    </div>
                                </div>
                                @endif

                                <div class="row mb-2">
                                    <div class="col-md-3">
                                        Deskripsi
                                    </div>
                                    <div class="col-md-8">
                                        : {{$result->description}}
                                    </div>
                                </div>

                                <div class="row mb-2">
                                    <div class="col-md-3">
                                        Apakah Menggunakan Stock
                                    </div>
                                    <div class="col-md-8">
                                        : <span class="badge bg-{{$result->is_using_stock()->class ?? null}}">{{$result->is_using_stock()->msg ?? null}}</span>
                                    </div>
                                </div>

                                <div class="row mb-2">
                                    <div class="col-md-3">
                                        Status
                                    </div>
                                    <div class="col-md-8">
                                        : <span class="badge bg-{{$result->status()->class ?? null}}">{{$result->status()->msg ?? null}}</span>
                                    </div>
                                </div>

                                <div class="row mb-2">
                                    <div class="col-md-3">
                                        QRcode
                                    </div>
                                    <div class="col-md-8">
                                        : <a href="{{route('dashboard.products.qrcode',$result->id)}}">
                                            {{\QrCode::size(100)->generate(route('landing-page.buy-products.index',$result->slug))}}
                                        </a>
                                    </div>
                                </div>

                                <div class="row mb-2">
                                    <div class="col-md-3">
                                        Author
                                    </div>
                                    <div class="col-md-8">
                                        : {{$result->author->name ?? null}}
                                    </div>
                                </div>

                                <div class="row mb-2">
                                    <div class="col-md-3">
                                        Tanggal Dibuat
                                    </div>
                                    <div class="col-md-8">
                                        : {{ date('d-m-Y H:i:s',strtotime($result->created_at)) }}
                                    </div>
                                </div>

                                <div class="row mb-2">
                                    <div class="col-md-3">
                                        Tanggal Diperbarui
                                    </div>
                                    <div class="col-md-8">
                                        : {{ date('d-m-Y H:i:s',strtotime($result->updated_at)) }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="tab-pane container" id="configuration">
                        <div class="row">
                            <div class="col-12">
                                <div class="row mb-2">
                                    <div class="col-md-3">
                                        Profile
                                    </div>
                                    <div class="col-md-8">
                                        : {{$result->profile}}
                                    </div>
                                </div>
                                @if(in_array($result->mikrotik,[\App\Enums\ProductEnum::MIKROTIK_PPPOE]))
                                <div class="row mb-2">
                                    <div class="col-md-3">
                                        Service
                                    </div>
                                    <div class="col-md-8">
                                        : {{$result->service}}
                                    </div>
                                </div>
                                <div class="row mb-2">
                                    <div class="col-md-3">
                                        Local Address
                                    </div>
                                    <div class="col-md-8">
                                        : {{$result->local_address}}
                                    </div>
                                </div>
                                <div class="row mb-2">
                                    <div class="col-md-3">
                                        Remote Address
                                    </div>
                                    <div class="col-md-8">
                                        : {{$result->remote_address}}
                                    </div>
                                </div>
                                <div class="row mb-2">
                                    <div class="col-md-3">
                                        Berlaku Sampai Tanggal
                                    </div>
                                    <div class="col-md-8">
                                        : @if(!empty($result->expired_date)) {{date("d-m-Y",strtotime($result->expired_date))}} @endif
                                    </div>
                                </div>
                                @endif
                                @if(in_array($result->mikrotik,[\App\Enums\ProductEnum::MIKROTIK_HOTSPOT]))
                                <div class="row mb-2">
                                    <div class="col-md-3">
                                        Server
                                    </div>
                                    <div class="col-md-8">
                                        : {{$result->server}}
                                    </div>
                                </div>
                                <div class="row mb-2">
                                    <div class="col-md-3">
                                        Address
                                    </div>
                                    <div class="col-md-8">
                                        : {{$result->address}}
                                    </div>
                                </div>
                                <div class="row mb-2">
                                    <div class="col-md-3">
                                        Mac Address
                                    </div>
                                    <div class="col-md-8">
                                        : {{$result->mac_address}}
                                    </div>
                                </div>
                                @endif
                                <div class="row mb-2">
                                    <div class="col-md-3">
                                        Comment
                                    </div>
                                    <div class="col-md-8">
                                        : {{$result->comment}}
                                    </div>
                                </div>
                            </div>
                        </div> 
                    </div>
                </div>
                <div class="row">
                    <div class="col-12">
                        <div class="d-flex justify-content-start mt-3">
                            <a href="{{route('dashboard.products.index')}}" class="btn btn-warning btn-sm" style="margin-right: 10px;"><i class="fa fa-arrow-left"></i> Kembali</a>
                            @if(!empty(Auth::user()->business_id))
                            <a href="{{route('dashboard.products.edit',$result->id)}}" class="btn btn-primary btn-sm" style="margin-right: 10px;"><i class="fa fa-edit"></i> Edit</a>
                            <a href="#" class="btn btn-danger btn-sm btn-delete" data-id="{{$result->id}}"><i class="fa fa-trash"></i> Hapus</a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<form id="frmDelete" method="POST">
    @csrf
    @method('DELETE')
    <input type="hidden" name="id"/>
</form>

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
        $('.datatables').DataTable();

        $(document).on("click", ".btn-add-stock", function(e) {
            e.preventDefault();

            $('#modalStoreStock').modal("show");
        });

        $(document).on("click", ".btn-delete", function(e) {
            e.preventDefault();
            let id = $(this).data("id");
            if (confirm("Apakah anda yakin ingin menghapus data ini ?")) {
                $("#frmDelete").attr("action", "{{ route('dashboard.products.destroy', '_id_') }}".replace("_id_", id));
                $("#frmDelete").find('input[name="id"]').val(id);
                $("#frmDelete").submit();
            }
        })
        
    })
</script>
@endsection