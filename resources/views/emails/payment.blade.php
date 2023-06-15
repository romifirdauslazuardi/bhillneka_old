@component('mail::message')

{!! $message !!}

@component('mail::panel')
# Ringkasan Pesanan:
@component('mail::table')
| Deskripsi | Keterangan |
| :------------- | :------------- |
| Pemilik Usaha | {!! $order->user->name ?? null !!} |
| Nomor Pesanan | {!! $order->code !!} |
| Tanggal Pesanan | {!! date('d-m-Y H:i:s',strtotime($order->created_at)) !!} |
| Metode Pembayaran | {!! $order->provider->name ?? null !!} |
| Total | {!! number_format($order->totalNeto(),0,',','.') !!} |
| Status | {!! $order->status()->msg ?? null !!} |
@endcomponent
@endcomponent

@component('mail::button', ['url' => url($url)])
BUKA WEBSITE
@endcomponent

Thanks,<br>
{!! \SettingHelper::settings('dashboard', 'title') !!}
@endcomponent
