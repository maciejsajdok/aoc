<?php

declare(strict_types=1);

namespace App\Utilities;

class ArrayUtilities
{
    public static function array_swap(array &$array, int $swap_a, int $swap_b): void
    {
        list($array[$swap_a], $array[$swap_b]) = array($array[$swap_b], $array[$swap_a]);
    }
}
