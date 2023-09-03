@extends("dashboard.layouts.main")

@section("title","Daftar Pengunjung Landing Page")

@section("css")
@endsection

@section('breadcumb')
    <div class="row">
        <div class="col-sm-12">
            <h3 class="page-title">Daftar Pengunjung Landing Page</h3>
            <ul class="breadcrumb">
                <li class="breadcrumb-item"><a href="#">Daftar Pengunjung Landing Page</a></li>
                <li class="breadcrumb-item active">List</li>
            </ul>
        </div>
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
                                    <th>Judul</th>
                                    <th>URL</th>
                                    <th>Jumlah Dilihat</th>
                                </thead>
                                <tbody>
                                    @forelse ($table as $index => $row)
                                    <tr>
                                        <td>{{$index+1}}</td>
                                        <td>{{$row['pageTitle'] }}</td>
                                        <td>{{$row['fullPageUrl'] }}</td>
                                        <td>{{$row['screenPageViews'] }}</td>
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
