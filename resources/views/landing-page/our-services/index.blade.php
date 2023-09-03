@extends("landing-page.layouts.main")

@section("title","Layanan Kami")

@section("css")
@endsection

@section("content")
<section class="bg-half-170 bg-light d-table w-100">
    <div class="container">
        <div class="row mt-5 justify-content-center">
            <div class="col-lg-12 text-center">
                <div class="pages-heading">
                    <h4 class="title mb-0">Layanan Kami</h4>
                </div>
            </div>
        </div>
        
        <div class="position-breadcrumb">
            <nav aria-label="breadcrumb" class="d-inline-block">
                <ul class="breadcrumb rounded shadow mb-0 px-4 py-2">
                    <li class="breadcrumb-item"><a href="{{route('landing-page.home.index')}}">Home</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Layanan Kami</li>
                </ul>
            </nav>
        </div>
    </div>
</section>

<section class="section">
    <div class="container">
        <div class="row d-flex justify-content-center">
            @foreach($table as $index => $row)
            <div class="col-md-4 col-12 wow animate__animated animate__fadeInUp" style="margin-bottom: 80px;">
                <div class="features feature-primary text-center">
                    <div class="image position-relative d-inline-block">
                        <i class="{{$row->icon}} h2 text-primary" style="font-size:40px;"></i>
                    </div>

                    <div class="content mt-4">
                        <h5>{{$row->name}}</h5>
                        <p class="text-muted mb-0">{{$row->description}}</p>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>
@endsection

@section("script")
<script>
    $(function(){

    })
</script>
@endsection