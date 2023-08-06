@component('mail::message')

{!! $message !!}

@component('mail::panel')
# Ringkasan Pesanan:
@component('mail::table')
| Deskripsi | Keterangan |
| :------------- | :------------- |
| Nama Usaha | {!! $order->business->name ?? null !!} |
| Nomor Pesanan | {!! $order->code !!} |
| Tanggal Pesanan | {!! date('d-m-Y H:i:s',strtotime($order->created_at)) !!} |
@if($order->provider->type == \App\Enums\ProviderEnum::TYPE_DOKU)
| Metode Pembayaran | {!! str_replace("_"," ",$order->doku_channel_id) !!} |
@else
| Metode Pembayaran | {!! $order->provider->name ?? null !!} |
@endif
| Status | {!! $order->status()->msg ?? null !!} |
| Subtotal | {!! number_format($order->subTotalItemBruto(),0,',','.') !!} |
| Discount | {!! number_format($order->totalDiscount(),0,',','.') !!} |
| Biaya Layanan | {!! number_format($order->customer_total_fee,0,',','.') !!} |
| Total | {!! number_format($order->totalNeto(),0,',','.') !!} |
@if($order->status == \App\Enums\OrderEnum::STATUS_SUCCESS)
| Bayar | {!! number_format($order->totalNeto(),0,',','.') !!} |
@else
| Bayar | {!! number_format(0,0,',','.') !!} |
@endif
| Kembalian | {!! number_format(0,0,',','.') !!} |
@endcomponent
@endcomponent

@component('mail::panel')
# Detail Pembelian:
@component('mail::table')
| Produk | Harga |
| :------------- | :------------- |
@foreach($order->items as $index => $row)
| {!!$row->product_name!!} x{!!$row->qty!!} | {!! number_format($row->totalBruto(),0,',','.') !!} |
@endforeach
@endcomponent
@endcomponent


@component('mail::button', ['url' => url($url)])
BUKA WEBSITE
@endcomponent

Thanks,<br>
{!! \SettingHelper::settings('dashboard', 'title') !!}
@endcomponent
