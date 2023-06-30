<meta charset="utf-8" />
<title>
@if(!empty(\SettingHelper::settings('dashboard', 'footer')))    
    {{\SettingHelper::settings('dashboard', 'footer')}} | @yield("title")
@else
    @yield("title")
@endif
</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="description" content="Premium Bootstrap 5 Landing Page Template" />
<meta name="keywords" content="Saas, Software, multi-uses, HTML, Clean, Modern" />
<meta name="author" content="Shreethemes" />
<meta name="email" content="support@shreethemes.in" />
<meta name="website" content="https://shreethemes.in" />
<meta name="Version" content="v4.7.0" />

<!-- favicon -->
<link rel="shortcut icon" href="{{URL::to('/')}}/templates/dashboard/assets/images/favicon.ico" />
<!-- Css -->
<link href="{{URL::to('/')}}/templates/dashboard/assets/libs/simplebar/simplebar.min.css" rel="stylesheet">
<!-- Bootstrap Css -->
<link href="{{URL::to('/')}}/templates/dashboard/assets/css/bootstrap.min.css" class="theme-opt" rel="stylesheet" type="text/css" />
<!-- Icons Css -->
<link href="{{URL::to('/')}}/templates/dashboard/assets/libs/@mdi/font/css/materialdesignicons.min.css" rel="stylesheet" type="text/css">
<link href="{{URL::to('/')}}/templates/dashboard/assets/css/icons.min.css" rel="stylesheet" type="text/css" />
<link href="{{URL::to('/')}}/templates/dashboard/assets/libs/@iconscout/unicons/css/line.css" type="text/css" rel="stylesheet" />
<!-- Style Css-->
<link href="{{URL::to('/')}}/templates/dashboard/assets/css/style.min.css" class="theme-opt" rel="stylesheet" type="text/css" />
<!-- Font Awesome -->
<link href="{{URL::to('/')}}/templates/dashboard/assets/css/font-awesome.css" type="text/css" rel="stylesheet" />
<!-- SweetAlert 2 -->
<link href="{{URL::to('/')}}/templates/dashboard/assets/libs/sweetalert2/sweetalert2.min.css" type="text/css" rel="stylesheet" />
<!-- Select2 -->
<link href="{{URL::to('/')}}/templates/dashboard/assets/libs/bootstrap-select2/select2.min.css"  rel="stylesheet"/>
<link href="{{URL::to('/')}}/templates/dashboard/assets/libs/bootstrap-select2/select2-bootstrap.min.css" rel="stylesheet">

<style type="text/css">
    .select2 {
        width: 100%;
    }

    .select2-container--default .select2-results__option--selected {
        background-color: #508aeb;
        color: white;
    }

    .select2-selection__rendered {
        line-height: calc(2.25rem + 2px) !important;
    }

    .select2-container .select2-selection--single {
        height: calc(2.25rem + 2px) !important;
    }

    .select2-selection__arrow {
        height: calc(2.25rem + 2px) !important;
    }

    .select2-close-mask{
        z-index: 2099;
    }
    .select2-dropdown{
        z-index: 3051;
    }
    .swal2-container {
        z-index: 4444;
    }
</style>
@yield("css")
