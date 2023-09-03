<?php

namespace App\Helpers;

use App\Models\Product;
use Illuminate\Support\Str;

class CodeHelper
{
    public static function generateUserCode()
    {
        $code = date("YmdHis").random_int(100,900);
        return $code;
    }

    public static function generateOrder()
    {
        $code = date("YmdHis").random_int(100,900);
        return $code;
    }
}
