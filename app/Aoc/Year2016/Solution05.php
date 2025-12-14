<?php

declare(strict_types=1);

namespace App\Aoc\Year2016;

use App\Services\Aoc\SolutionInterface;
use function array_fill;
use function implode;
use function md5;
use function str_starts_with;
use function trim;

class Solution05 implements SolutionInterface
{
    public function p1(string $input): mixed
    {
        $doorId = trim($input);
        $found = 0;
        $index = 0;
        $password = '';

        while ($found != 8) {
            $index ++;
            $hash = md5($doorId.$index);
            if (str_starts_with($hash, '00000')) {
                $password .= $hash[5];
                $found++;
            }
        }

        return $password;
    }

    public function p2(string $input): mixed
    {
        $doorId = trim($input);
        $found = 0;
        $index = 0;
        $password = array_fill(0, 8, '');

        while ($found != 8) {
            $index ++;
            $hash = md5($doorId.$index);
            if (str_starts_with($hash, '00000')) {
                $position = $hash[5];
                if ($position < 8 && $password[$position] == ''){
                    $password[$position] .= $hash[6];
                    $found++;
                }
            }
        }

        return implode('', $password);
    }
}
