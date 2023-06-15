@extends("landing-page.layouts.main")

@section("title","Faq")

@section("css")
@endsection

@section("content")
<section class="bg-half-170 bg-light d-table w-100">
    <div class="container">
        <div class="row mt-5 justify-content-center">
            <div class="col-lg-12 text-center">
                <div class="pages-heading">
                    <h4 class="title mb-0">Faq</h4>
                </div>
            </div>
        </div>
        
        <div class="position-breadcrumb">
            <nav aria-label="breadcrumb" class="d-inline-block">
                <ul class="breadcrumb rounded shadow mb-0 px-4 py-2">
                    <li class="breadcrumb-item"><a href="{{route('landing-page.home.index')}}">Home</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Faq</li>
                </ul>
            </nav>
        </div>
    </div>
</section>

<section class="section">
    <div class="container">
        <div class="row d-flex justify-content-center">
            <div class="col-12">
                <div class="accordion" id="accordionExample">
                    @foreach($table as $index => $row)
                    <div class="accordion-item rounded shadow mt-3">
                        <h2 class="accordion-header" id="heading-{{$index}}">
                            <button class="accordion-button border-0 bg-light collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse-{{$index}}"
                                aria-expanded="false" aria-controls="collapse-{{$index}}">
                                {{$row->question}}
                            </button>
                        </h2>
                        <div id="collapse-{{$index}}" class="accordion-collapse border-0 collapse" aria-labelledby="heading-{{$index}}"
                            data-bs-parent="#accordionExample">
                            <div class="accordion-body text-muted">
                                {{$row->answer}}
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
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