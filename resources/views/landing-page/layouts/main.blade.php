<!doctype html>
<html lang="en" dir="ltr">

    <head>
        @include("landing-page.layouts.head")
    </head>

    <body oncontextmenu="return true;">
        @include('sweetalert::alert')
        
        @include("landing-page.layouts.topbar")

        @yield("content")
        
        @include("landing-page.layouts.footer")

        @include("landing-page.layouts.backtotop")

        @include("landing-page.layouts.script")
    </body>
</html>