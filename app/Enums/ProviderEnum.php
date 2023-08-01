<?php declare(strict_types=1);

namespace App\Enums;

use BenSampo\Enum\Enum;

/**
 * @method static static OptionOne()
 * @method static static OptionTwo()
 * @method static static OptionThree()
 */
final class ProviderEnum extends Enum
{
    const TYPE_MANUAL_TRANSFER = 1;
    const TYPE_DOKU = 2;
    const TYPE_PAY_LATER = 3;

    const STATUS_TRUE = 1;
    const STATUS_FALSE = 0;

    public static function status()
    {
        $data = [
            self::STATUS_TRUE => 'Aktif',
            self::STATUS_FALSE => 'Tidak Aktif',
        ];

        return $data;
    }

    public static function type()
    {
        $data = [
            self::TYPE_MANUAL_TRANSFER => 'Manual Transfer',
            self::TYPE_DOKU => 'Doku',
            self::TYPE_PAY_LATER => 'Bayar Nanti'
        ];

        return $data;
    }
}
