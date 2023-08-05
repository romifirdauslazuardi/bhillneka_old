<html>
<head>
<title>Faktur Pembayaran</title>
<style>
 
#tabel
{
font-size:15px;
border-collapse:collapse;
}
#tabel  td
{
padding-left:5px;
border: 1px solid black;
}
</style>
</head>
    <body style='font-family:tahoma; font-size:8pt;'>
        <center>
            <table style='width:300px; font-size:12pt; border-collapse: collapse;' border = '0'>
                <td width='70%' align='CENTER' vertical-align:top'>
                    <span style='color:black;'>
                        <b>{{$result->business->name ?? null}}</b>
                    </span>
                    </br>
                    <span style='font-size:12pt'>---Nota Transaksi---</span>
                    </br>
                    <span style='font-size:12pt'>{{$result->business->location ?? null}}</span>
                    <br>
                    <span style='font-size:12pt'>{{$result->user->phone ?? null}}</span>
                    <br>
                    <br>
                    @if($result->provider->type == \App\Enums\ProviderEnum::TYPE_DOKU)
                    {{\QrCode::size(100)->generate($result->payment_url)}}
                    @else
                    {{\QrCode::size(100)->generate(route('landing-page.manual-payments.index',$result->code))}}
                    @endif
                    <br>
                    <br>
                </td>
            </table>
            <table style='width:300px; font-size:12pt;'>
                <tr style="width: 70%;">
                    <td align="center">
                        @if(!empty($result->customer_id))
                            {{$result->customer->name ?? null}}
                        @else
                            {{$result->customer_name}}
                        @endif
                    </td>
                </tr>
                <tr>
                    <td align="left">--------------------------------------------------</td>
                </tr>
            </table>
            <table style='width:300px; font-size:11pt;'>
                <tr>
                    <td>
                        <div style="float: left;">
                            {{$result->status()->msg ?? null}}
                        </div> 
                        <div style="float: right;">
                            #{{$result->code}}
                        </div> 
                    </td>
                </tr>
                <tr>
                    <td align="left">------------------------------------------------------</td>
                </tr>
                <tr>
                    <td>
                        <div style="float: left;">
                            {{date("d-m-Y H:i",strtotime($result->created_at))}}
                        </div> 
                        <div style="float: right;">
                            {{$result->author->name ?? null}}
                        </div> 
                    </td>
                </tr>
                <tr>
                    <td align="left">------------------------------------------------------</td>
                </tr>
                @foreach($result->items as $index => $row)
                <tr>
                    <td style="float:left;">
                        <p style="margin-top: 0;margin-bottom:0;padding-top: 0;margin-bottom:0;">{{$row->product_name}}</p>
                    </td>
                </tr>
                <tr>
                    <td style="float:left;">
                        <div style="display: flex;">
                            <p style="margin-top: 0;margin-bottom:0;padding-top: 0;padding-bottom:0;margin-right:5px;">{{$row->qty}}</p>
                            <p style="margin-top: 0;margin-bottom:0;padding-top: 0;padding-bottom:0;margin-right:5px;">x</p>
                            <p style="margin-top: 0;margin-bottom:0;padding-top: 0;padding-bottom:0;margin-right:5px;">{{number_format($row->product_price,0,',','.')}}</p>
                        </div>
                    </td>
                    <td style="float: right;">
                        <p style="margin-top: 0;margin-bottom:0;padding-top: 0;padding-bottom:0;">{{number_format($row->totalBruto(),0,',','.')}}</p>
                    </td>
                </tr>
                @endforeach
                <tr>
                    <td align="left">------------------------------------------------------</td>
                </tr>
                <tr>
                    <td>
                        <div style="float: left;">
                            SUBTOTAL
                        </div> 
                        <div style="float: right;">
                            {{number_format($result->subTotalItemBruto(),0,',','.')}}
                        </div> 
                    </td>
                </tr>
                <tr>
                    <td>
                        <div style="float: left;">
                            DISCOUNT
                        </div> 
                        <div style="float: right;">
                            {{number_format($result->totalDiscount(),0,',','.')}}
                        </div> 
                    </td>
                </tr>
                <tr>
                    <td>
                        <div style="float: left;">
                            BIAYA LAYANAN
                        </div> 
                        <div style="float: right;">
                            {{number_format($result->customer_total_fee,0,',','.')}}
                        </div> 
                    </td>
                </tr>
                <tr>
                    <td>
                        <div style="float: left;">
                            TOTAL
                        </div> 
                        <div style="float: right;">
                            {{number_format($result->totalNeto(),0,',','.')}}
                        </div> 
                    </td>
                </tr>
                <tr>
                    <td>
                        <div style="float: left;">
                            BAYAR
                        </div> 
                        <div style="float: right;">
                            @if($result->status == \App\Enums\OrderEnum::STATUS_SUCCESS)
                            {{number_format($result->totalNeto(),0,',','.')}}
                            @else
                            {{number_format(0,0,',','.')}}
                            @endif
                        </div> 
                    </td>
                </tr>
                <tr>
                    <td>
                        <div style="float: left;">
                            KEMBALI
                        </div> 
                        <div style="float: right;">
                            {{number_format(0,0,',','.')}}
                        </div> 
                    </td>
                </tr>
                <tr>
                    <td align="left">------------------------------------------------------</td>
                </tr>
            </table>
            <table style='width:300px; font-size:12pt;' cellspacing='2'>
                <tr></br><td align='center'>Penyedia Layanan / www.bhilnekka.com</br></td></tr>
                <tr></br><td align='center'>Billing Digital</br></td></tr>
            </table>
            <table style='width:300px; font-size:12pt;' cellspacing='2'>
                <tr></br><td align='center'>****** TERIMAKASIH ******</br></td></tr>
            </table>
        </center>
    </body>
</html>