@component('mail::message')
# Hi,

Selamat akun anda berhasil didaftarkan sebagai Agen. Silahkan cek pesan masuk pada email untuk verifikasi akun.

@component('mail::table')
| <!-- -->    | <!-- -->    |
|-------------|-------------|
| <b>Jasa Layanan & Aplikasi</b>         | {{ $settingFee->owner_fee ?? null }}%        |
| <b>Pendapatan Agen</b>        | {{ $settingFee->agen_fee ?? null }}%       |
@endcomponent
@endcomponent