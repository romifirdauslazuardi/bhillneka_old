<?php

namespace App\Models;

use App\Enums\OrderEnum;
use App\Helpers\DateHelper;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Haruncpi\LaravelUserActivity\Traits\Loggable;
use Illuminate\Database\Eloquent\SoftDeletes;

class Order extends Model
{
    use HasFactory, Loggable,SoftDeletes;
    protected $table = "orders";
    protected $fillable = [
        'code',
        'user_id',
        'customer_id',
        'customer_name',
        'customer_phone',
        'discount',
        'type_fee',
        'owner_fee',
        'agen_fee',
        'total_owner_fee',
        'total_agen_fee',
        'customer_type_fee',
        'customer_value_fee',
        'customer_total_fee',
        'doku_fee',
        'provider_id',
        'note',
        'doku_service_id',
        'doku_acquirer_id',
        'doku_channel_id',
        'doku_token_id',
        'payment_due_date',
        'expired_date',
        'paid_date',
        'payment_url',
        'proof_order',
        'payment_note',
        'status',
        'progress',
        'owner_bank_settlement_id',
        'agen_bank_settlement_id',
        'business_id',
        'type',
        'fnb_type',
        'due_date',
        'table_id',
        'repeat_order_at',
        'repeat_order_status',
        'order_id',
        'author_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function customer()
    {
        return $this->belongsTo(User::class, 'customer_id', 'id');
    }

    public function provider()
    {
        return $this->belongsTo(Provider::class, 'provider_id', 'id');
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class, 'order_id');
    }

    public function doku()
    {
        return $this->hasMany(OrderDoku::class, 'order_id')->orderBy("created_at","DESC");
    }

    public function author()
    {
        return $this->belongsTo(User::class, 'author_id', 'id');
    }

    public function business()
    {
        return $this->belongsTo(Business::class, 'business_id', 'id');
    }

    public function table()
    {
        return $this->belongsTo(Table::class, 'table_id', 'id');
    }

    public function order()
    {
        return $this->belongsTo(Order::class, 'order_id', 'id');
    }

    public function status()
    {
        $return = null;

        if($this->status == OrderEnum::STATUS_PENDING){
            $return = (object) [
                'class' => 'info',
                'msg' => 'Pending',
            ];
        }
        else if($this->status == OrderEnum::STATUS_WAITING_PAYMENT){
            $return = (object) [
                'class' => 'secondary',
                'msg' => 'Menunggu Pembayaran',
            ];
        }
        else if($this->status == OrderEnum::STATUS_SUCCESS){
            $return = (object) [
                'class' => 'success',
                'msg' => 'Berhasil',
            ];
        }
        else if($this->status == OrderEnum::STATUS_FAILED){
            $return = (object) [
                'class' => 'danger',
                'msg' => 'Gagal',
            ];
        }
        else if($this->status == OrderEnum::STATUS_EXPIRED){
            $return = (object) [
                'class' => 'danger',
                'msg' => 'Expired',
            ];
        }
        else if($this->status == OrderEnum::STATUS_REFUNDED){
            $return = (object) [
                'class' => 'warning',
                'msg' => 'Refunded',
            ];
        }
        else if($this->status == OrderEnum::STATUS_TIMEOUT){
            $return = (object) [
                'class' => 'danger',
                'msg' => 'Timeout',
            ];
        }
        else if($this->status == OrderEnum::STATUS_REDIRECT){
            $return = (object) [
                'class' => 'info',
                'msg' => 'Redirect',
            ];
        }
        else if($this->status == OrderEnum::STATUS_PAY_LATER){
            $return = (object) [
                'class' => 'secondary',
                'msg' => 'Bayar Nanti',
            ];
        }

        return $return;
    }

    public function progress()
    {
        $return = null;

        if($this->progress == OrderEnum::PROGRESS_BATAL){
            $return = (object) [
                'class' => 'danger',
                'msg' => 'Batal',
            ];
        }
        else if($this->progress == OrderEnum::PROGRESS_DRAFT){
            $return = (object) [
                'class' => 'secondary',
                'msg' => 'Draft',
            ];
        }
        else if($this->progress == OrderEnum::PROGRESS_PENDING){
            $return = (object) [
                'class' => 'warning',
                'msg' => 'Pending',
            ];
        }
        else if($this->progress == OrderEnum::PROGRESS_DIKONFIRMASI){
            $return = (object) [
                'class' => 'info',
                'msg' => 'Dikonfirmasi',
            ];
        }
        else if($this->progress == OrderEnum::PROGRESS_DIKIRIM){
            $return = (object) [
                'class' => 'success',
                'msg' => 'Dikirim',
            ];
        }
        else if($this->progress == OrderEnum::PROGRESS_TERIKIRIM){
            $return = (object) [
                'class' => 'success',
                'msg' => 'Terkirim',
            ];
        }
        else if($this->progress == OrderEnum::PROGRESS_SELESAI){
            $return = (object) [
                'class' => 'success',
                'msg' => 'Selesai',
            ];
        }
        else if($this->progress == OrderEnum::PROGRESS_EXPIRED){
            $return = (object) [
                'class' => 'danger',
                'msg' => 'Expired',
            ];
        }

        return $return;
    }

    public function type()
    {
        $return = null;

        if($this->type == OrderEnum::TYPE_DUE_DATE){
            $return = "Jatuh Tempo";
        }
        else{
            $return = "Sekali Bayar";
        }

        return $return;
    }

    public function fnb_type()
    {
        $return = null;

        if($this->fnb_type == OrderEnum::FNB_DINE_IN){
            $return = "Dine In";
        }
        else if($this->fnb_type == OrderEnum::FNB_TAKEAWAY){
            $return = "Take Away";
        }

        return $return;
    }

    public function getDiscountAttribute($value)
    {
        return floatval($value);
    }

    public function getOwnerFeeAttribute($value)
    {
        return floatval($value);
    }

    public function getAgenFeeAttribute($value)
    {
        return (int)$value;
    }

    public function getTotalOwnerFeeAttribute($value)
    {
        return floatval($value);
    }

    public function getTotalAgenFeeAttribute($value)
    {
        return floatval($value);
    }

    public function getCustomerValueFee($value)
    {
        return floatval($value);
    }

    public function getCustomerTotalFee($value)
    {
        return floatval($value);
    }

    public function getDokuFeeAttribute($value)
    {
        return (int)$value;
    }

    public function totalBruto(){
        $total = 0;

        foreach($this->items()->get() as $index => $row){
            $total += $row->qty * $row->product_price;
        }

        $total += $this->customer_total_fee + $this->doku_fee;
        return (int)$total;
    }

    public function totalNeto(){
        $total = 0;
        foreach($this->items()->get() as $index => $row){
            $total += ($row->qty * $row->product_price) - $row->discount;
        }

        $total = $total - $this->discount;
        $total += $this->customer_total_fee;

        return (int)$total;
    }

    public function totalNetoWithoutCustomerFee(){
        $total = 0;
        foreach($this->items()->get() as $index => $row){
            $total += ($row->qty * $row->product_price) - $row->discount;
        }

        $total = $total - $this->discount;

        return (int)$total;
    }

    public function incomeAgenBruto(){
        $total = $this->total_agen_fee + $this->doku_fee;
        return $total;
    }

    public function incomeAgenNeto(){
        $total = $this->total_agen_fee - $this->doku_fee;
        return $total;
    }

    public function incomeOwnerNeto(){
        $total = $this->total_owner_fee;
        return $total;
    }

    public function incomeOwnerBruto(){
        $total = $this->total_owner_fee + $this->doku_fee;
        return $total;
    }
}
