<?php declare(strict_types=1);

namespace App\Enums;

use BenSampo\Enum\Enum;

/**
 * @method static static OptionOne()
 * @method static static OptionTwo()
 * @method static static OptionThree()
 */
final class NewsRecipientEnum extends Enum
{
    const IS_CUSTOMER_GENERAL_YES = 1;
    const IS_CUSTOMER_GENERAL_NO = 0;
}
