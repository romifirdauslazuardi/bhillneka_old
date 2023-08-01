@component('mail::message')
# Hi,

Selamat akun anda berhasil didaftarkan sebagai Agen. Silahkan cek pesan masuk pada email untuk verifikasi akun.

@component('mail::table')
| <!-- -->    | <!-- -->    |
|-------------|-------------|
@foreach($settingFee as $index => $row)
| Penjualan {{$row->mark()}} {{number_format($row->limit,0,',','.')}}
| Estimasi Jasa Layanan & Aplikasi         | {{ $row->owner_fee ?? null }}%        |
| Estimasi Pendapatan Agen        | {{ $row->agen_fee ?? null }}%       |
| <!-- -->    | <!-- -->    |
|-------------|-------------|
@endforeach
@endcomponent
@endcomponent