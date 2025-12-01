<?php

declare(strict_types=1);

namespace App\Aoc\Year2025;

use App\Services\Aoc\SolutionInterface;
use function dump;
use function explode;
use function round;
use function str_split;
use function substr;
use const PHP_ROUND_HALF_DOWN;
use const PHP_ROUND_HALF_UP;

class Solution01 implements SolutionInterface
{
    public function p1(string $input): mixed
    {
        $data = explode("\n", trim($input));
        $pos = 50;
        $result = 0;
        foreach ($data as $line) {
            $direction = str_split($line, 1)[0];
            $value = (int)substr($line, 1);
            if ($direction === 'L'){
                $pos = ($pos - $value) % 100;
            } else {
                $pos = ($pos + $value) % 100;
            }
            if($pos === 0){
                $result += 1;
            }
        }


        return $result;
    }

    public function p2(string $input): mixed
    {
        $data = explode("\n", trim($input));
        $pos = 50;
        $result = 0;
        foreach ($data as $line) {
            $direction = str_split($line, 1)[0];
            $value = (int)substr($line, 1);
            for($i = 0; $i < abs($value); $i++) {
                if ($direction === 'L') {
                    $pos--;
                    if ($pos === -1){
                        $pos = 99;
                    }
                } else {
                    $pos++;
                    if ($pos === 100){
                        $pos = 0;
                    }
                }
                if($pos === 0){
                    $result += 1;
                }
            }
        }


        return $result;
    }
}
