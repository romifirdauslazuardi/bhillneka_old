<?php declare(strict_types=1);

namespace App\Enums;

use BenSampo\Enum\Enum;

/**
 * @method static static OptionOne()
 * @method static static OptionTwo()
 * @method static static OptionThree()
 */
final class ProductEnum extends Enum
{
    const STATUS_TRUE = 1;
    const STATUS_FALSE = 0;

    const IS_USING_STOCK_TRUE = 1;
    const IS_USING_STOCK_FALSE = 0;
    
    const MIKROTIK_NONE = 0;
    const MIKROTIK_PPPOE = 1;
    const MIKROTIK_HOTSPOT = 2;

    const IMAGE_EXT = ['jpeg','jpg','png'];

    public static function status()
    {
        $data = [
            self::STATUS_TRUE => 'Aktif',
            self::STATUS_FALSE => 'Tidak Aktif',
        ];

        return $data;
    }

    public static function is_using_stock()
    {
        $data = [
            self::IS_USING_STOCK_TRUE => 'Ya',
            self::IS_USING_STOCK_FALSE => 'Tidak',
        ];

        return $data;
    }

    public static function mikrotik()
    {
        $data = [
            self::MIKROTIK_PPPOE => 'PPPOE',
            self::MIKROTIK_HOTSPOT => 'Hotspot',
        ];

        return $data;
    }
}
