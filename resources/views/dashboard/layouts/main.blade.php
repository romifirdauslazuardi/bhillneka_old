
<!doctype html>
<html lang="en" dir="ltr">
    <head>
        @include("dashboard.layouts.head")
    </head>

    <body oncontextmenu="return true;">

        @include('sweetalert::alert')

        <div class="page-wrapper toggled">
        @include("dashboard.layouts.sidebar")

            <!-- Start Page Content -->
            <main class="page-content bg-light">
            @include("dashboard.layouts.topbar")

                <div class="container-fluid">
                    <div class="layout-specing">
                        @yield("breadcumb")

                        @if(session('error') || session('success'))
                        <div class="row">
                            <div class="col-12">
                                @if (session('error'))
                                    @component('dashboard.components.alert')
                                        @slot("class")
                                            danger
                                        @endslot
                                        @slot("title")
                                            Gagal
                                        @endslot
                                        {!! session('error') !!}
                                    @endcomponent
                                @elseif (session('success'))
                                    @component('dashboard.components.alert')
                                        @slot("class")
                                            success
                                        @endslot
                                        @slot("title")
                                            Berhasil
                                        @endslot
                                        {!! session('success') !!}
                                    @endcomponent
                                @endif
                            </div>
                        </div>
                        @endif

                        @if ($errors->any())
                        <div class="row">
                            <div class="col-12">
                                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                    Terjadi kesalahan saat memproses data:
                                    <ul>
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                            </div>
                        </div>
                        @endif
                    
                        @yield("content")
                    </div>
                </div><!--end container-->

                @include("dashboard.layouts.footer")
            </main>
            <!--End page-content" -->
        </div>
        <!-- page-wrapper -->

        @include("dashboard.layouts.rightbar")

        @include("dashboard.layouts.select-business")
        
        @include("dashboard.layouts.script")
    </body>

</html>