<?php

declare(strict_types=1);

namespace App\Utilities;

class Arr extends \Illuminate\Support\Arr
{
    public static function arraySwap(array &$array, int $swap_a, int $swap_b): void
    {
        list($array[$swap_a], $array[$swap_b]) = array($array[$swap_b], $array[$swap_a]);
    }

    public static function fromInput(string $input): array
    {

    }
}
