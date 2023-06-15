<?php declare(strict_types=1);

namespace App\Enums;

use BenSampo\Enum\Enum;

/**
 * @method static static OptionOne()
 * @method static static OptionTwo()
 * @method static static OptionThree()
 */
final class UserBankEnum extends Enum
{
    const STATUS_WAITING_APPROVE = 1;
    const STATUS_APPROVED = 2;
    const STATUS_REJECTED = 3;

    const DEFAULT_TRUE = 1;
    const DEFAULT_FALSE = 0;

    public static function status()
    {
        $data = [
            self::STATUS_WAITING_APPROVE => 'Menunggu Diverifikasi',
            self::STATUS_APPROVED => 'Terverifikasi',
            self::STATUS_REJECTED => 'Ditolak',
        ];

        return $data;
    }

    public static function default()
    {
        $data = [
            self::DEFAULT_TRUE => 'Ya',
            self::DEFAULT_FALSE => 'Tidak',
        ];

        return $data;
    }
}
