<?php declare(strict_types=1);

namespace App\Enums;

use BenSampo\Enum\Enum;

/**
 * @method static static OptionOne()
 * @method static static OptionTwo()
 * @method static static OptionThree()
 */
final class BusinessCategoryEnum extends Enum
{
    const JASA = "Jasa";
    const BARANG = "Barang";
    const MIKROTIK = "Mikrotik";
    const FNB = "Food & Beverage";
}
