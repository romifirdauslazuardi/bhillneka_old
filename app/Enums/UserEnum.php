<?php declare(strict_types=1);

namespace App\Enums;

use BenSampo\Enum\Enum;

/**
 * @method static static OptionOne()
 * @method static static OptionTwo()
 * @method static static OptionThree()
 */
final class UserEnum extends Enum
{
    const AVATAR_EXT = ['jpeg','jpg','png'];

    const PROVIDER_MANUAL = 1;
    const PROVIDER_GOOGLE = 2;
}
