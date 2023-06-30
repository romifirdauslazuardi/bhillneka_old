<html>
    <head>
        <title>QRCode</title>
        <link href="{{URL::to('/')}}/templates/dashboard/assets/css/bootstrap.min.css" class="theme-opt" rel="stylesheet" type="text/css" />
    </head>

    <body>
        <div class="container pt-5">
        {{\QrCode::size(250)->generate(route('landing-page.shops.index',["business_id" => $result->business_id,"table_id" => $result->id]))}}
        </div>
    </body>
</html>