@component('mail::message')
# Hi,

Selamat akun anda berhasil didaftarkan sebagai Agen. Biaya pertransaksi mulai dari 0.5%. Berikut  ini kami informasikan detail persentase pertransaksi

@component('mail::panel')
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

Dapat Kami Informasikan terkait pencairan dana setiap transaksinya, berikut detailnya

@component('mail::panel')
@component('mail::table')
| Metode Pembayaran | Pencairan |
| :------------- | :------------- |
| VA BRI, BNI, Bank Mandiri | T+3 |
| VA BCA | T+1 |
| VA Bank Lainnya | T+2 | 
| OVO , ShopeePay | T+2 |
| DOKU Wallet | T+1 |
| AlfaGroup | T+4 |  
| Credit Card BRI, BNI, Bank Mandiri | T+3 |  
@endcomponent
@endcomponent

@endcomponent
