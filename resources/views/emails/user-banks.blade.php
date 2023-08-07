@component('mail::message')

<b>{!! $title !!}</b>,
<br>
{!! $message !!}

@component('mail::button', ['url' => url($url)])
BUKA WEBSITE
@endcomponent

Thanks,<br>
{!! \SettingHelper::settings('dashboard', 'title') !!}
@endcomponent
