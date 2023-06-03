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
}
