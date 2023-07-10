<?php declare(strict_types=1);

namespace App\Enums\Setting;

use BenSampo\Enum\Enum;

/**
 * @method static static OptionOne()
 * @method static static OptionTwo()
 * @method static static OptionThree()
 */
final class DashboardSettingEnum extends Enum
{
    const LOGO_EXT = ['jpeg','jpg','png','svg'];
}
