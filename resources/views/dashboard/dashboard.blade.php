@extends("dashboard.layouts.main")

@section("title","Dashboard")

@section("css")
<!-- ChartJs Css -->
<link href="{{URL::to('/')}}/templates/dashboard/assets/libs/chartjs/Chart.min.css" rel="stylesheet" type="text/css" />
<style>
    .p-total{
        font-size: 14px !important;
        text-transform: uppercase;
    }
    .box-total{
        height: 110px;;
    }
</style>
@endsection

@section("breadcumb")
<div class="d-flex align-items-center justify-content-between">
    <div>
        <h6 class="text-muted mb-1">Welcome back, {{Auth::user()->name}}</h6>
        <h5 class="mb-0">Dashboard</h5>
    </div>
</div>
@endsection

@section("content")
<div class="row">
    @if(Auth::user()->hasRole([\App\Enums\RoleEnum::OWNER,\App\Enums\RoleEnum::AGEN,\App\Enums\RoleEnum::ADMIN_AGEN]))
    <div class="col-md-4 mt-4 col-xs-12">
        <a href="{{route('dashboard.products.index')}}" class="features feature-primary d-flex justify-content-between align-items-center rounded shadow p-3 box-total">
            <div class="d-flex align-items-center">
                <div class="icon text-center rounded-pill">
                    <i class="fa fa-tags fs-5 mb-0"></i>
                </div>
                <div class="flex-1 ms-3">
                    <p class="mb-0 text-muted p-total">Produk</p>
                    <p class="fs-5 text-dark fw-bold mb-0">{{$total_product}}</p>
                </div>
            </div>
        </a>
    </div>
    @endif
    @if(Auth::user()->hasRole([\App\Enums\RoleEnum::AGEN,\App\Enums\RoleEnum::ADMIN_AGEN]))
    <div class="col-md-4 mt-4 col-xs-12">
        <a href="{{route('dashboard.reports.incomes.index')}}" class="features feature-primary d-flex justify-content-between align-items-center rounded shadow p-3 box-total">
            <div class="d-flex align-items-center">
                <div class="icon text-center rounded-pill">
                    <i class="fa fa-dollar fs-5 mb-0"></i>
                </div>
                <div class="flex-1 ms-3">
                    <p class="mb-0 text-muted p-total">Pendapatan {{date("F Y")}}</p>
                    <p class="fs-5 text-dark fw-bold mb-0">{{number_format($total_income_agen,0,',','.')}}</p>
                </div>
            </div>
        </a>
    </div>
    @endif
    @if(Auth::user()->hasRole([\App\Enums\RoleEnum::OWNER]))
    <div class="col-md-4 mt-4 col-xs-12">
        <a href="{{route('dashboard.reports.incomes.index')}}" class="features feature-primary d-flex justify-content-between align-items-center rounded shadow p-3 box-total">
            <div class="d-flex align-items-center">
                <div class="icon text-center rounded-pill">
                    <i class="fa fa-dollar fs-5 mb-0"></i>
                </div>
                <div class="flex-1 ms-3">
                    <p class="mb-0 text-muted p-total">Jasa Aplikasi & Layanan {{date("F Y")}}</p>
                    <p class="fs-5 text-dark fw-bold mb-0">{{number_format($total_income_owner,0,',','.')}}</p>
                </div>
            </div>
        </a>
    </div>
    @endif
</div>

@if(Auth::user()->hasRole([\App\Enums\RoleEnum::OWNER,\App\Enums\RoleEnum::AGEN,\App\Enums\RoleEnum::ADMIN_AGEN,\App\Enums\RoleEnum::CUSTOMER]))

@if(Auth::user()->hasRole([\App\Enums\RoleEnum::OWNER]))
<div class="row mt-4">
    <div class="col-12">
        <div class="card border-0 rounded shadow p-4">
            <canvas id="ownerChart"></canvas>
        </div>
    </div>
</div>
@endif

@if(Auth::user()->hasRole([\App\Enums\RoleEnum::AGEN,\App\Enums\RoleEnum::ADMIN_AGEN]))
<div class="row mt-4">
    <div class="col-12">
        <div class="card border-0 rounded shadow p-4">
            <canvas id="agenChart"></canvas>
        </div>
    </div>
</div>
@endif

<div class="row">
    <div class="col-12 mt-4">
        <div class="card border-0 rounded shadow p-4">
            <h5>Pesanan {{date("F Y")}}</h5>
            <div class="table-responsive">
                <div class="table">
                    <table class="table table-striped table-bordered">
                        <thead>
                            <th>No</th>
                            <th>Kode Transaksi</th>
                            @if(Auth::user()->hasRole([\App\Enums\RoleEnum::OWNER,\App\Enums\RoleEnum::AGEN,\App\Enums\RoleEnum::ADMIN_AGEN]))
                                <th>Pendapatan</th>
                                <th>Jasa Aplikasi & Layanan</th>
                            @endif
                            <th>Total</th>
                            <th>Progress</th>
                            <th>Status</th>
                            <th>Dibuat Pada</th>
                        </thead>
                        <tbody>
                            @forelse ($orders as $index => $row)
                            <tr>
                                <td>{{$index + 1}}</td>
                                <td>{{$row->code}}</td>
                                @if(Auth::user()->hasRole([\App\Enums\RoleEnum::OWNER,\App\Enums\RoleEnum::AGEN,\App\Enums\RoleEnum::ADMIN_AGEN]))
                                    <td>{{number_format($row->incomeAgenNeto(),0,',','.')}}</td>
                                    <td>{{number_format($row->incomeOwnerBruto(),0,',','.')}}</td>
                                @endif
                                <td>{{number_format($row->totalNeto(),0,',','.')}}</td>
                                <td>
                                    <span class="badge bg-{{$row->progress()->class ?? null}}">{{$row->progress()->msg ?? null}}</span>
                                </td>
                                <td>
                                    <span class="badge bg-{{$row->status()->class ?? null}}">{{$row->status()->msg ?? null}}</span>
                                </td>
                                <td>{{date('d-m-Y H:i:s',strtotime($row->created_at))}}</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="10" class="text-center">Data tidak ditemukan</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

@endif

@include("dashboard.components.loader")

@endsection

@section("script")
<!-- ChartJs -->
<script src="{{URL::to('/')}}/templates/dashboard/assets/libs/chartjs/Chart.min.js"></script>
<script>
    $(function(){

        @if((Auth::user()->hasRole([\App\Enums\RoleEnum::AGEN,\App\Enums\RoleEnum::ADMIN_AGEN]) && empty(Auth::user()->business_id)))
            $("#modalBusinessPage").find('button[type="button"]').remove();
            $("#modalBusinessPage").modal("show");
        @endif

        @if(Auth::user()->hasRole([\App\Enums\RoleEnum::AGEN,\App\Enums\RoleEnum::ADMIN_AGEN]))
        var ctxAgen = document.getElementById('agenChart').getContext('2d');
        @endif

        @if(Auth::user()->hasRole([\App\Enums\RoleEnum::OWNER]))
        var ctxOwner = document.getElementById('ownerChart').getContext('2d');
        @endif

        @if(Auth::user()->hasRole([\App\Enums\RoleEnum::AGEN,\App\Enums\RoleEnum::ADMIN_AGEN]))
        var agenChart = new Chart(ctxAgen, {
        //chart akan ditampilkan sebagai bar chart
            type: 'line',
            data: {
            //membuat label chart
                labels: JSON.parse('<?php echo json_encode($chart_income_agen["labels"]); ?>'),
                datasets: [{
                    label: 'Pendapatan {{date("F Y")}}',
                    data: JSON.parse('<?php echo json_encode($chart_income_agen["value"]); ?>'),
                    borderColor: 'rgb(75, 192, 192)',
                    borderWidth: 1
                }]
            },
            options: {
                tooltips: {
                    callbacks: {
                        label: function(t, d) {
                            return t.yLabel.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
                        }
                    }
                },
                scales: {
                    yAxes: [{
                        ticks: {
                            beginAtZero: true,
                            callback: function(value, index, values) {
                                return value.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
                            }
                        },
                    }]
                }
            }
        });
        @endif
        
        @if(Auth::user()->hasRole([\App\Enums\RoleEnum::OWNER]))
        var ownerChart = new Chart(ctxOwner, {
        //chart akan ditampilkan sebagai bar chart
            type: 'line',
            data: {
            //membuat label chart
                labels: JSON.parse('<?php echo json_encode($chart_income_owner["labels"]); ?>'),
                datasets: [{
                    label: 'Jasa Aplikasi & Layanan {{date("F Y")}}',
                    data: JSON.parse('<?php echo json_encode($chart_income_owner["value"]); ?>'),
                    borderColor: 'rgb(75, 192, 192)',
                    borderWidth: 1
                }]
            },
            options: {
                tooltips: {
                    callbacks: {
                        label: function(t, d) {
                            return t.yLabel.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
                        }
                    }
                },
                scales: {
                    yAxes: [{
                        ticks: {
                            beginAtZero: true,
                            callback: function(value, index, values) {
                                return value.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
                            }
                        },
                    }]
                }
            }
        });
        @endif
    })
</script>
@endsection