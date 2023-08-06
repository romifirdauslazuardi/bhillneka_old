@component('mail::message')
# Hi,

Selamat akun anda berhasil didaftarkan sebagai Agen. Berikut kami informasikan estimasi pendapatan yang didapatkan.

@component('mail::table')
| <!-- -->    | <!-- -->    |
|-------------|-------------|
@foreach($settingFee as $index => $row)
| Transaksi {{$row->mark()}} {{number_format($row->limit,0,',','.')}}
| Jasa Layanan & Aplikasi         | {{ $row->owner_fee ?? null }}%        |
| Pendapatan Agen        | {{ $row->agen_fee ?? null }}%       |
@endforeach
@endcomponent
@endcomponent