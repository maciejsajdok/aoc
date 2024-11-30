<?php

declare(strict_types=1);

namespace App\Aoc\Year2015;

use App\Services\Aoc\SolutionInterface;
use function str_split;

class Solution01 implements SolutionInterface
{
    public function p1(string $input): mixed
    {
        $parenthesis = str_split($input);
        $count = 0;
        foreach ($parenthesis as $part) {
            $count += match ($part){
                '(' => 1,
                ')' => -1
            };
        }

        return $count;
    }

    public function p2(string $input): mixed
    {
        $parenthesis = str_split($input);
        $count = 0;
        foreach ($parenthesis as $index => $part) {
            $count += match ($part){
                '(' => 1,
                ')' => -1
            };

            if ($count === -1){
                return $index + 1;
            }
        }

        return $count;
    }
}
