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
    const CUSTOMER = "Customer";
    const ADMIN_AGEN = "Admin Agen";

    public static function roles()
    {
        $roles = [
            self::OWNER,
            self::AGEN,
            self::CUSTOMER,
            self::ADMIN_AGEN,
        ];

        if (Auth::user()->hasRole([RoleEnum::AGEN])) {
            unset($roles[0]);
            unset($roles[1]);
        }

        return $roles;
    }
}
