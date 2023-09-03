<?php declare(strict_types=1);

namespace App\Enums;

use BenSampo\Enum\Enum;

/**
 * @method static static OptionOne()
 * @method static static OptionTwo()
 * @method static static OptionThree()
 */
final class SettingFeeEnum extends Enum
{
    const MARK_KURANG_DARI = "<=";
    const MARK_LEBIH_DARI = ">";

    public static function mark()
    {
        $data = [
            self::MARK_KURANG_DARI => 'Kurang Dari',
            self::MARK_LEBIH_DARI => 'Lebih Dari',
        ];

        return $data;
    }
}
