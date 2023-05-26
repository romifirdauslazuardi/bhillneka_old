<?php

declare(strict_types=1);

namespace App\Enums;

use BenSampo\Enum\Enum;
use Auth;

/**
 * @method static static OptionOne()
 * @method static static OptionTwo()
 * @method static static OptionThree()
 */
final class RoleEnum extends Enum
{
    const OWNER = "Owner";
    const AGEN = "Agen";
    const USER = "User";

    public static function roles()
    {
        $roles = [
            self::OWNER,
            self::AGEN,
            self::USER,
        ];

        return $roles;
    }
}
