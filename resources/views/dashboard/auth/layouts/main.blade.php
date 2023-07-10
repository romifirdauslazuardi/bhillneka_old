
<!doctype html>
<html lang="en" dir="ltr">

    <head>
        @include("dashboard.auth.layouts.head")
    </head>

    <body oncontextmenu="return false;">
        @include('sweetalert::alert')

        <!-- Hero Start -->
        @yield("content")
        <!-- Hero End -->
        
        @include("dashboard.auth.layouts.script")
        
    </body>

</html>