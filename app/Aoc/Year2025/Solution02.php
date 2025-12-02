<?php

declare(strict_types=1);

namespace App\Aoc\Year2025;

use App\Services\Aoc\SolutionInterface;
use function array_unique;
use function explode;
use function intdiv;
use function str_split;
use function strlen;
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
                $divisors = $this->getDivisorsArray(strlen((string)$i));
                $parts = [];
                foreach ($divisors as $divisor) {
                    $segments = str_split((string)$i, $divisor);
                    $parts[$divisor] = $segments;
                }
                $found = false;
                foreach ($parts as $part) {
                    if(count(array_unique($part)) === 1){
                        $result += $i;
                        $found = true;
                    }
                    if ($found === true) {
                        break;
                    }
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
}
