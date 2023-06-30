<?php declare(strict_types=1);

namespace App\Enums;

use BenSampo\Enum\Enum;

/**
 * @method static static OptionOne()
 * @method static static OptionTwo()
 * @method static static OptionThree()
 */
final class OrderMikrotikEnum extends Enum
{
    const DISABLED_TRUE = "yes";
    const DISABED_NO = "no";

    const TYPE_PPPOE = 1;
    const TYPE_HOTSPOT = 2;

    const AUTO_USERPASSWORD_TRUE = 1;
    const AUTO_USERPASSWORD_FALSE = 0;

    public static function disabled()
    {
        $data = [
            self::DISABLED_TRUE => 'Yes',
            self::DISABED_NO => 'No',
        ];

        return $data;
    }

    public static function type()
    {
        $data = [
            self::TYPE_PPPOE => 'PPPOE',
            self::TYPE_HOTSPOT => 'Hotspot',
        ];

        return $data;
    }

    public static function auto_userpassword()
    {
        $data = [
            self::AUTO_USERPASSWORD_TRUE => 'Auto',
            self::AUTO_USERPASSWORD_FALSE => 'Input Manual',
        ];

        return $data;
    }
}
