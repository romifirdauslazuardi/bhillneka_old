<meta charset="utf-8">
<title>
@if(!empty(\SettingHelper::settings('landing_page', 'footer')))    
    {{\SettingHelper::settings('landing_page', 'footer')}} | @yield("title")
@else
    @yield("title")
@endif
</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="description" content="Premium Bootstrap 5 Landing Page Template">
<meta name="keywords" content="Saas, Software, multi-uses, HTML, Clean, Modern">
<meta name="author" content="Shreethemes">
<meta name="email" content="support@shreethemes.in">
<meta name="website" content="https://shreethemes.in">
<meta name="Version" content="v4.7.0">

<!-- favicon -->
<link rel="shortcut icon" href="{{URL::to('/')}}/templates/landing-page/assets/images/favicon.ico">

<!-- Style Css-->
<link href="{{URL::to('/')}}/templates/landing-page/assets/libs/tiny-slider/tiny-slider.css" rel="stylesheet">
<link href="{{URL::to('/')}}/templates/landing-page/assets/libs/tobii/css/tobii.min.css" rel="stylesheet">
<link href="{{URL::to('/')}}/templates/landing-page/assets/libs/animate.css/animate.min.css" rel="stylesheet">
<!-- Bootstrap Css -->
<link href="{{URL::to('/')}}/templates/landing-page/assets/css/bootstrap-green.min.css" id="bootstrap-style" class="theme-opt" rel="stylesheet" type="text/css">
<!-- Icons Css -->
<link href="{{URL::to('/')}}/templates/landing-page/assets/libs/@mdi/font/css/materialdesignicons.min.css" rel="stylesheet" type="text/css">
<link href="{{URL::to('/')}}/templates/landing-page/assets/libs/@iconscout/unicons/css/line.css" type="text/css" rel="stylesheet">
<!-- Style Css-->
<link href="{{URL::to('/')}}/templates/landing-page/assets/css/style-green.min.css" id="color-opt" class="theme-opt" rel="stylesheet" type="text/css">
<!-- Font Awesome -->
<link href="{{URL::to('/')}}/templates/dashboard/assets/css/font-awesome.css" type="text/css" rel="stylesheet" />
@yield("css")