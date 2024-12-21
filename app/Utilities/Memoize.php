<?php

declare(strict_types=1);

namespace App\Utilities;

class Memoize
{
    public static function make($func): \Closure
    {
        return function () use ($func) {
            static $cache = [];

            $args = func_get_args();
            $key = md5(serialize($args));

            if (!isset($cache[$key])) {
                $cache[$key] = call_user_func_array($func, $args);
            }

            return $cache[$key];
        };
    }
}
