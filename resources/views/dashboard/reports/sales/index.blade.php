@extends("dashboard.layouts.main")

@section("title","Sales Report")

@section("css")
@endsection

@section("breadcumb")
<div class="d-md-flex justify-content-between align-items-center">
    <h5 class="mb-0">Sales Report</h5>

    <nav aria-label="breadcrumb" class="d-inline-block mt-2 mt-sm-0">
        <ul class="breadcrumb bg-transparent rounded mb-0 p-0">
            <li class="breadcrumb-item text-capitalize"><a href="#">Report</a></li>
            <li class="breadcrumb-item text-capitalize"><a href="#">Sales</a></li>
            <li class="breadcrumb-item text-capitalize active" aria-current="page">Index</li>
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
                        <a href="{{route('dashboard.reports.sales.index')}}" class="btn @if(!empty(request()->all())) btn-warning @else btn-secondary @endif btn-sm"><i class="fa fa-refresh"></i> Refresh</a>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-12">
                    <h5 class="my-3">Total Penjualan : <b>{{number_format($total,0,',','.')}}</b></h5>
                    <div class="table-responsive">
                        <div class="table">
                            <table class="table table-striped table-bordered">
                                <thead>
                                    <th>No</th>
                                    <th>Kode Transaksi</th>
                                    <th>Total</th>
                                    <th>Status</th>
                                    <th>Dibuat Pada</th>
                                </thead>
                                <tbody>
                                    @forelse ($table as $index => $row)
                                    <tr>
                                        <td>{{$table->firstItem() + $index}}</td>
                                        <td>{{$row->code}}</td>
                                        <td>{{number_format($row->totalNeto(),0,',','.')}}</td>
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

@include("dashboard.reports.sales.modal.index")
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
            $("#frmExport").attr("action", "{{ route('dashboard.reports.sales.exportExcel') }}");
            $("#modalExport").modal("show");
        });
    })
</script>
@endsection