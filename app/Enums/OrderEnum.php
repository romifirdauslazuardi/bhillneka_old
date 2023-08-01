<?php declare(strict_types=1);

namespace App\Enums;

use BenSampo\Enum\Enum;
use Auth;

/**
 * @method static static OptionOne()
 * @method static static OptionTwo()
 * @method static static OptionThree()
 */
final class OrderEnum extends Enum
{
    const STATUS_WAITING_PAYMENT = "WAITING PAYMENT";
    const STATUS_PENDING = "PENDING";
    const STATUS_SUCCESS = "SUCCESS";
    const STATUS_FAILED = "FAILED";
    const STATUS_EXPIRED = "EXPIRED";
    const STATUS_REFUNDED = "REFUNDED";
    const STATUS_REDIRECT = "REDIRECT";
    const STATUS_TIMEOUT = "TIMEOUT";
    const STATUS_PAY_LATER = "PAY LATER";

    const PROOF_ORDER_EXT = ['jpeg','jpg','png','svg'];

    const TYPE_ON_TIME_PAY = 1;
    const TYPE_DUE_DATE = 2;

    const FNB_DINE_IN = 1;
    const FNB_TAKEAWAY = 2;
    const FNB_NONE = 0;

    const PROGRESS_BATAL = 0;
    const PROGRESS_DRAFT = 1;
    const PROGRESS_PENDING = 2;
    const PROGRESS_DIKONFIRMASI = 3;
    const PROGRESS_DIKIRIM = 4;
    const PROGRESS_TERIKIRIM = 5;
    const PROGRESS_SELESAI = 6;
    const PROGRESS_EXPIRED = 7;

    const REPEAT_ORDER_STATUS_TRUE = 1;
    const REPEAT_ORDER_STATUS_FALSE = 0; 

    public static function status()
    {
        $data = [
            self::STATUS_WAITING_PAYMENT => 'MENUNGGU PEMBAYARAN',
            self::STATUS_PENDING => 'PENDING',
            self::STATUS_SUCCESS => 'BERHASIL',
            self::STATUS_FAILED => 'GAGAL',
            self::STATUS_EXPIRED => 'EXPIRED',
            self::STATUS_REFUNDED => 'REFUNDED',
            self::STATUS_REDIRECT => 'REDIRECT',
            self::STATUS_TIMEOUT => 'TIMEOUT',
            SELF::STATUS_PAY_LATER => 'BAYAR NANTI',
        ];

        return $data;
    }

    public static function type()
    {
        $data = [
            self::TYPE_ON_TIME_PAY => 'Sekali Bayar',
            self::TYPE_DUE_DATE => 'Jatuh Tempo',
        ];

        return $data;
    }

    public static function fnb_type()
    {
        $data = [
            self::FNB_DINE_IN => 'Dine In',
            self::FNB_TAKEAWAY => 'Take Away',
        ];

        return $data;
    }

    public static function progress()
    {
        $data = [
            self::PROGRESS_BATAL => 'Batal',
            self::PROGRESS_DRAFT => 'Draft',
            self::PROGRESS_PENDING => 'Pending',
            self::PROGRESS_DIKONFIRMASI => 'Dikonfirmasi',
            self::PROGRESS_DIKIRIM => 'Dikirim',
            self::PROGRESS_TERIKIRIM => 'Terkirim',
            self::PROGRESS_SELESAI => 'Selesai',
            self::PROGRESS_EXPIRED => 'Expired',
        ];

        return $data;
    }

    public static function repeat_order_status()
    {
        $data = [
            self::REPEAT_ORDER_STATUS_TRUE => 'Aktif',
            self::REPEAT_ORDER_STATUS_FALSE => 'Tidak Aktif',
        ];

        return $data;
    }
}
