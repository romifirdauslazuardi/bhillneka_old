<?php declare(strict_types=1);

namespace App\Enums;

use BenSampo\Enum\Enum;

/**
 * @method static static OptionOne()
 * @method static static OptionTwo()
 * @method static static OptionThree()
 */
final class ProductStockEnum extends Enum
{
    const TYPE_MASUK = 1;
    const TYPE_KELUAR = 2;
    
    public static function type()
    {
        $data = [
            self::TYPE_MASUK => 'Stok Masuk',
            self::TYPE_KELUAR => 'Stok Keluar',
        ];

        return $data;
    }
}
