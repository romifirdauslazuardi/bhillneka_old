<html>
    <head>
        <title>QRCode</title>
        <link href="{{URL::to('/')}}/templates/dashboard/assets/css/bootstrap.min.css" class="theme-opt" rel="stylesheet" type="text/css" />
    </head>

    <body>
        <div class="container pt-5">
            {{\QrCode::size(250)->generate(route('landing-page.buy-products.index',$result->slug))}}
        </div>
    </body>
</html>