<meta charset="utf-8">
<title>
@if(!empty(\SettingHelper::settings('landing_page', 'title')))    
    {{\SettingHelper::settings('landing_page', 'title')}} | @yield("title")
@else
    @yield("title")
@endif
</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">

{!! SEO::generate() !!}

<!-- favicon -->
<link rel="shortcut icon" href="{{!empty(\SettingHelper::settings('landing_page', 'favicon')) ? asset(\SettingHelper::settings('landing_page', 'favicon')) : URL::to('/').'/templates/landing-page/assets/images/favicon.ico'}}">

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
<style>
  .bg-half-170 {
    padding: 50px 0 !important;
  }
  .section {
    padding-bottom: 0px;
  }
</style>
@yield("css")

@if(app()->isProduction())
<!-- Google tag (gtag.js) -->
<script async src="https://www.googletagmanager.com/gtag/js?id=G-WDWE4H597R"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());

  gtag('config', 'G-WDWE4H597R');
</script>
@endif