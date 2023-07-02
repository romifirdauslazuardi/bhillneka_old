@extends("dashboard.layouts.main")

@section("title","Order Mikrotik")

@section("css")
@endsection

@section("breadcumb")
<div class="d-md-flex justify-content-between align-items-center">
    <h5 class="mb-0">Order Mikrotik</h5>

    <nav aria-label="breadcrumb" class="d-inline-block mt-2 mt-sm-0">
        <ul class="breadcrumb bg-transparent rounded mb-0 p-0">
            <li class="breadcrumb-item text-capitalize"><a href="#">Order Mikrotik</a></li>
            <li class="breadcrumb-item text-capitalize active" aria-current="page">Show</li>
        </ul>
    </nav>
</div>
@endsection

@section("content")
<div class="row pb-2">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title mb-3">Informasi Data Order Mikrotik</h5>
                <div class="row">
                    <div class="col-12">

                        <div class="row mb-2">
                            <div class="col-md-3">
                                Ordernum
                            </div>
                            <div class="col-md-8">
                                : {{$result->order_item->order->code ?? null}}
                            </div>
                        </div>

                        <div class="row mb-2">
                            <div class="col-md-3">
                                Username
                            </div>
                            <div class="col-md-8">
                                : {{$result->username}}
                            </div>
                        </div>

                        <div class="row mb-2">
                            <div class="col-md-3">
                                Password
                            </div>
                            <div class="col-md-8">
                                : {{$result->password}}
                            </div>
                        </div>

                        @if($result->type == \App\Enums\OrderMikrotikEnum::TYPE_PPPOE)
                        <div class="row mb-2">
                            <div class="col-md-3">
                                Service
                            </div>
                            <div class="col-md-8">
                                : {{$result->service}}
                            </div>
                        </div>
                        @endif

                        @if($result->type == \App\Enums\OrderMikrotikEnum::TYPE_HOTSPOT)
                        <div class="row mb-2">
                            <div class="col-md-3">
                                Server
                            </div>
                            <div class="col-md-8">
                                : {{$result->server}}
                            </div>
                        </div>
                        @endif

                        @if($result->type == \App\Enums\OrderMikrotikEnum::TYPE_PPPOE)
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
                        @endif

                        @if($result->type == \App\Enums\OrderMikrotikEnum::TYPE_HOTSPOT)
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
                        <div class="row mb-2">
                            <div class="col-md-3">
                                Time Limit
                            </div>
                            <div class="col-md-8">
                                : {{$result->time_limit}}
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

                        <div class="row mb-2">
                            <div class="col-md-3">
                                Disabled
                            </div>
                            <div class="col-md-8">
                                : {{$result->disabled}}
                            </div>
                        </div>

                        <div class="row mb-2">
                            <div class="col-md-3">
                                Tipe
                            </div>
                            <div class="col-md-8">
                                : {{$result->type() ?? null}}
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
                <div class="row">
                    <div class="col-12">
                        <div class="d-flex justify-content-start mt-3">
                            <a href="{{route('dashboard.reports.order-mikrotiks.index')}}" class="btn btn-warning btn-sm" style="margin-right: 10px;"><i class="fa fa-arrow-left"></i> Kembali</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@include("dashboard.components.loader")

@endsection

@section("script")
<script>
    $(function(){
    })
</script>
@endsection