@component('mail::message')
# Hi,

Selamat akun anda berhasil didaftarkan sebagai Agen. Silahkan cek pesan masuk pada email untuk verifikasi akun.

@component('mail::table')
| <!-- -->    | <!-- -->    |
|-------------|-------------|
| <b>Pendapatan Owner</b>         | {{ $settingFee->owner_fee ?? null }}% (Include Biaya Penanganan)        |
| <b>Pendapatan Agen</b>        | {{ $settingFee->agen_fee ?? null }}%       |
@endcomponent
@endcomponent