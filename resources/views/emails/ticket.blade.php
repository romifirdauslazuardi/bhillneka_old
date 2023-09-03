@component('mail::message')
# Hi,

Terdapat tiket bantuan baru di aplikasi <b>{!! \SettingHelper::settings('landing_page', 'title') !!}</b>.

@component('mail::table')
| <!-- -->    | <!-- -->    |
|-------------|-------------|
| <b>Nama</b>         | {{ $userName }}         |
| <b>Email</b>        | {{ $email }}        |
| <b>Subjek</b>     | {{ $subject }}         |
| <b>Pesan</b>     | {{ $message }}         |
@endcomponent
@endcomponent