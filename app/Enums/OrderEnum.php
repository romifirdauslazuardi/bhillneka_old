<?php declare(strict_types=1);

namespace App\Enums;

use BenSampo\Enum\Enum;

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

    public static function status()
    {
        $data = [
            self::STATUS_WAITING_PAYMENT => 'WAITING PAYMENT',
            self::STATUS_PENDING => 'PENDING',
            self::STATUS_SUCCESS => 'SUCCESS',
            self::STATUS_FAILED => 'FAILED',
            self::STATUS_EXPIRED => 'EXPIRED',
            self::STATUS_REFUNDED => 'REFUNDED',
            self::STATUS_REDIRECT => 'REDIRECT',
            self::STATUS_TIMEOUT => 'TIMEOUT',
        ];

        return $data;
    }
}
