<?php

declare(strict_types=1);

namespace App\Aoc\Year2015;

use App\Services\Aoc\SolutionInterface;
use function bcpowmod;

class Solution25 implements SolutionInterface
{
    public function p1(string $input): mixed
    {
        $firstCode = 20151125;
        $mul = 252533;
        $mod = 33554393;
// test data
//        $tc = 6;
//        $tr = 6;

        $tc = 3019;
        $tr = 3010;
        //Here are calculations to calculate how many numbers there are
        $targetNumberIndex = ($tc + $tr - 2) * ($tc + $tr - 1) / 2 + $tc - 1;

        //the task says to multiply by something and modulo b it so it looks like a^b mod c and
        // there is already something for that called modular exponentation, and php has neat function for that

        $modexp = bcpowmod((string)$mul, (string)$targetNumberIndex, (string)$mod);

        //Once we have all the calculations we just need to calculate it for our number
        return ($modexp * $firstCode) %$mod;
        return $result;
    }

    public function p2(string $input): mixed
    {
        return null;
    }
}
