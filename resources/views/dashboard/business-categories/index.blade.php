@extends("dashboard.layouts.main")

@section("title","Kategori Bisnis")

@section("css")
@endsection

@section("breadcumb")
<div class="d-md-flex justify-content-between align-items-center">
    <h5 class="mb-0">Kategori Bisnis</h5>

    <nav aria-label="breadcrumb" class="d-inline-block mt-2 mt-sm-0">
        <ul class="breadcrumb bg-transparent rounded mb-0 p-0">
            <li class="breadcrumb-item text-capitalize"><a href="#">Kategori Bisnis</a></li>
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
                <div class="col-lg-12">
                    <a href="#" class="btn btn-success btn-sm btn-filter"><i class="fa fa-filter"></i> Filter</a>
                    <a href="{{route('dashboard.business-categories.index')}}" class="btn @if(!empty(request()->all())) btn-warning @else btn-secondary @endif btn-sm"><i class="fa fa-refresh"></i> Refresh</a>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-12">
                    <div class="table-responsive">
                        <div class="table">
                            <table class="table table-striped table-bordered">
                                <thead>
                                    <th>No</th>
                                    <th>Nama</th>
                                    <th>Dibuat Pada</th>
                                </thead>
                                <tbody>
                                    @forelse ($table as $index => $row)
                                    <tr>
                                        <td>{{$table->firstItem() + $index}}</td>
                                        <td>{{$row->name}}</td>
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

@include("dashboard.business-categories.modal.index")
@include("dashboard.components.loader")

@endsection

@section("script")
<script>
    $(function() {

        $('button[type="submit"]').attr("disabled",false);

        $(document).on("click", ".btn-filter", function(e) {
            e.preventDefault();

            $("#modalFilter").modal("show");
        });
    })
</script>
@endsection