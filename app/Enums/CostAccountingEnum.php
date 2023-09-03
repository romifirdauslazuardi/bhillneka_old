<?php declare(strict_types=1);

namespace App\Enums;

use BenSampo\Enum\Enum;

/**
 * @method static static OptionOne()
 * @method static static OptionTwo()
 * @method static static OptionThree()
 */
final class CostAccountingEnum extends Enum
{
    const TYPE_PEMASUKAN = 1;
    const TYPE_PENGELUARAN = 2;
    
    public static function type()
    {
        $data = [
            self::TYPE_PEMASUKAN => 'Pemasukan',
            self::TYPE_PENGELUARAN => 'Pengeluaran',
        ];

        return $data;
    }
}
