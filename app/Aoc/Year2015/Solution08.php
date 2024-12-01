<?php

declare(strict_types=1);

namespace App\Aoc\Year2015;

use App\Services\Aoc\SolutionInterface;
use function addslashes;
use function explode;
use function strlen;
use function trim;

class Solution08 implements SolutionInterface
{
    public function p1(string $input): mixed
    {
        $lines = explode("\n", trim($input));

        $sum = 0;
        foreach ($lines as $line) {
            $code = strlen($line);

            $string = strlen(eval("return $line;"));
            $sum += $code - $string;
        }
        return $sum;
    }

    public function p2(string $input): mixed
    {
        $lines = explode("\n", trim($input));

        $sum = 0;
        foreach ($lines as $line) {
            $code = strlen($line);
            $string = addslashes($line);
            $stringLen = strlen($string)+ 2;
            $sum += $stringLen - $code;
        }
        return $sum;
    }
}
