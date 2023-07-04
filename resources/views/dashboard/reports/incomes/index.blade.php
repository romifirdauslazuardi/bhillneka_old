@extends("dashboard.layouts.main")

@section("title","Laporan Pendapatan")

@section("css")
@endsection

@section("breadcumb")
<div class="d-md-flex justify-content-between align-items-center">
    <h5 class="mb-0">Laporan Pendapatan</h5>

    <nav aria-label="breadcrumb" class="d-inline-block mt-2 mt-sm-0">
        <ul class="breadcrumb bg-transparent rounded mb-0 p-0">
            <li class="breadcrumb-item text-capitalize"><a href="#">Laporan</a></li>
            <li class="breadcrumb-item text-capitalize active" aria-current="page">Pendapatan</li>
        </ul>
    </nav>
</div>
@endsection

@section("content")
<div class="row">
    <div class="col-12 mt-4">
        <div class="card border-0 rounded shadow p-4">
            <div class="row mb-3">
                <div class="col-12">
                    <div class="d-flex">
                        <a href="#" class="btn btn-success btn-sm btn-filter" style="margin-right: 5px;"><i class="fa fa-filter"></i> Filter</a>
                        <div class="dropdown-primary" style="margin-right: 5px;">
                            <button type="button" class="btn btn-info btn-sm" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                Export <i class="fa fa-file"></i>
                            </button>
                            <div class="dropdown-menu">
                                <a href="#" class="dropdown-item btn-export-excel">Export Excel</a>
                            </div>
                        </div>
                        <a href="{{route('dashboard.reports.incomes.index')}}" class="btn @if(!empty(request()->all())) btn-warning @else btn-secondary @endif btn-sm"><i class="fa fa-refresh"></i> Refresh</a>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-12">
                    <div class="d-flex justify-content-between">
                        @if(Auth::user()->hasRole([\App\Enums\RoleEnum::OWNER]))
                        <h5 class="my-3">Biaya Layanan & Aplikasi : <b>{{number_format($total_owner,0,',','.')}}</b></h5>
                        @endif
                        <h5 class="my-3">Pendapatan Agen : <b>{{number_format($total_agen,0,',','.')}}</b></h5>
                    </div>
                    <div class="table-responsive">
                        <div class="table">
                            <table class="table table-striped table-bordered">
                                <thead>
                                    <th>No</th>
                                    <th>Kode Transaksi</th>
                                    <th>Customer</th>
                                    <th>Pendapatan Agen</th>
                                    <th>Jasa Aplikasi & Layanan</th>
                                    <th>Status Pembayaran</th>
                                    <th>Dibuat Pada</th>
                                </thead>
                                <tbody>
                                    @forelse ($table as $index => $row)
                                    <tr>
                                        <td>{{$table->firstItem() + $index}}</td>
                                        <td>{{$row->code}}</td>
                                        <td>
                                            @if(!empty($row->customer))
                                            {{$row->customer->name ?? null}}
                                            <br>
                                            {{$row->customer->phone ?? null}}
                                            @else
                                            {{$row->customer_name}}
                                            <br>
                                            {{$row->customer_phone}}
                                            @endif
                                        </td>
                                        <td>{{number_format($row->incomeAgen(),0,',','.')}}</td>
                                        <td>{{number_format($row->incomeOwnerBruto(),0,',','.')}}</td>
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
                    {!!$table->links()!!}
                </div>
            </div>
        </div>
    </div>
</div>

@include("dashboard.reports.incomes.modal.index")
@include("dashboard.components.loader")

@endsection

@section("script")
<script>
    $(function() {
        $(document).on("click", ".btn-filter", function(e) {
            e.preventDefault();

            $("#modalFilter").modal("show");
        });

        $(document).on("click", ".btn-export-excel", function(e) {
            e.preventDefault();

            $('#modalExport').find('.export-title').html("Excel");
            $("#frmExport").attr("action", "{{ route('dashboard.reports.incomes.exportExcel') }}");
            $("#modalExport").modal("show");
        });
    })
</script>
@endsection