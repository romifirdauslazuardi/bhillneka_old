@php
    $base_url = url('/');
@endphp
<title>{{ $data != null ? $data->title : $business->name }} - Landing Page</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="description" content="{{ $data?->description }}">
<meta name="keywords" content="{{ $data?->keyword }}">
<meta name="author" content="Shreethemes">
<meta name="email" content="{{ $data?->email }}">
<meta name="website" content="{{ route(Route::currentRouteName()) }}">
<meta name="Version" content="v4.7.0">

<!-- favicon -->
<link rel="shortcut icon" href="{{ asset($data?->favicon) }}">
<input type="hidden" name="base_url" id="base_url" value="{{ $base_url }}">
