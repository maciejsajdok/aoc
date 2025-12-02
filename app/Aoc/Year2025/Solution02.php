<?php

declare(strict_types=1);

namespace App\Aoc\Year2025;

use App\Services\Aoc\SolutionInterface;
use function array_reverse;
use function array_unique;
use function explode;
use function intdiv;
use function str_split;
use function strlen;
use function strpos;
use function substr;

class Solution02 implements SolutionInterface
{
    public function p1(string $input): mixed
    {
        $result = 0;
        $productRanges = explode(',', trim($input));
        foreach ($productRanges as $productRange) {
            [$start, $end] = explode('-', $productRange);
            for ($i = $start; $i <= $end; $i++) {
//                dump($i);
                $half = intdiv(strlen((string)$i), 2);
                [$left, $right] = [substr((string) $i, 0, $half), substr((string) $i, $half)];
                if($left === $right) {
                    $result += $i;
                }
            }
        }

        return $result;
    }

    public function p2(string $input): mixed
    {
        $result = 0;
        $productRanges = explode(',', trim($input));
        foreach ($productRanges as $productRange) {
            [$start, $end] = explode('-', $productRange);
            for ($i = $start; $i <= $end; $i++) {
                $intAsString = (string) $i;
                if($this->hasRepeatingPattern($intAsString)) {
                    $result += $i;
                }
            }
        }

        return $result;
    }

    private function getDivisorsArray(int $x): array
    {
        $divisors = [];
        for($i = 1; $i < $x; $i++) {
            if($x % $i === 0) {
                $divisors[] = $i;
            }
        }
        return $divisors;
    }

    private function hasRepeatingPattern(string $s): bool
    {
        $characterCount = strlen($s);

        $doubledString = $s.$s;
        $position = strpos($doubledString, $s, 1);

        return $position !== false && $position < $characterCount;
    }
}
