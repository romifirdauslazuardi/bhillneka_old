<meta charset="utf-8" />
<title>@yield("title")</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="description" content="{{\SettingHelper::settings('landing_page', 'description')}}" />
<meta name="keywords" content="{{\SettingHelper::settings('landing_page', 'keyword')}}" />

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
@yield("css")