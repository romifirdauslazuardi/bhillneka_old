
<!doctype html>
<html lang="en" dir="ltr">

    <head>
        @include("dashboard.layouts.head")
    </head>

    <body>
        @include('sweetalert::alert')

        <!-- Hero Start -->
        @yield("content")
        <!-- Hero End -->
        
        @include("dashboard.layouts.script")
        
    </body>

</html>