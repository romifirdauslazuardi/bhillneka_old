@extends("dashboard.layouts.main")

@section("title","Daftar Pengunjung Landing Page")

@section("css")
@endsection

@section("breadcumb")
<div class="d-md-flex justify-content-between align-items-center">
    <h5 class="mb-0">Daftar Pengunjung Landing Page</h5>

    <nav aria-label="breadcrumb" class="d-inline-block mt-2 mt-sm-0">
        <ul class="breadcrumb bg-transparent rounded mb-0 p-0">
            <li class="breadcrumb-item text-capitalize"><a href="#">Daftar Pengunjung Landing Page</a></li>
            <li class="breadcrumb-item text-capitalize active" aria-current="page">Daftar Pengunjung Landing Page</li>
        </ul>
    </nav>
</div>
@endsection

@section("content")
<div class="row">
    <div class="col-12 mt-4">
        <div class="card border-0 rounded shadow p-4">
            <div class="row">
                <div class="col-lg-12">
                    <div class="table-responsive">
                        <div class="table">
                            <table class="table table-striped table-bordered">
                                <thead>
                                    <th>No</th>
                                    <th>URL</th>
                                    <th>Page Views</th>
                                </thead>
                                <tbody>
                                    @php 
                                        $no = 1;
                                    @endphp
                                    @forelse ($table as $index => $row)
                                    <tr>
                                        <td>{{$no}}</td>
                                        <td>{{$row['url'] }}</td>
                                        <td>{{$row['pageViews'] }}</td>
                                    </tr>

                                    @php
                                        $no += 1;
                                    @endphp
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
    </div>
</div>

@include("dashboard.components.loader")

@endsection

@section("script")
<script>
    $(function() {
    })
</script>
@endsection