<?php

declare(strict_types=1);

namespace App\Aoc\Year2025;

use App\Services\Aoc\SolutionInterface;
use function array_splice;
use function count;
use function explode;

class Solution06 implements SolutionInterface
{
    public function p1(string $input): mixed
    {
        $data = explode("\n", trim($input));
        $numbers = [];
        $len = count($data);
        for ($i = 0; $i < $len-1; $i++) {
            $numbers[] = preg_split('/\s+/', trim($data[$i]));
        }
        $signs = preg_split('/\s+/', $data[$len - 1]);
        $result = 0;
        foreach ($signs as $i => $sign) {
            $val = $sign === '*' ? 1 : 0;
            foreach ($numbers as $numberList) {
                $val = match($sign){
                    '*' => $val * (int) $numberList[$i],
                    '+' => $val + (int) $numberList[$i],
                };
            }
            $result += $val;
        }
        return $result;
    }

    public function p2(string $input): mixed
    {
        $data = explode("\n", $input);
        $len = count($data);
        $signs = preg_split('/\s+/', $data[$len - 1]);
        $numberLines = array_splice($data, 0, $len - 1);
        $result = 0;
        $maxSize = max(array_map('strlen', $numberLines));

        $numbers = [];
        for ($i = 0; $i < $maxSize; $i++) {
            $num = '';
            foreach ($numberLines as $numberLine) {
                $num .= $numberLine[$i] ?? '';
            }
            $numbers[] = $num;
        }
        $numberGroups = [];

        $g = 0;
        foreach ($numbers as $number) {
            if(!empty(trim($number))){
                $numberGroups[$g][] = (int) trim($number);
            } else {
                $g++;
            }
        }

        foreach ($signs as $i => $sign) {
            $val = $sign === '*' ? 1 : 0;
                foreach ($numberGroups[$i] as $number) {
                    $val = match ($sign) {
                        '*' => $val * $number,
                        '+' => $val + $number,
                    };
            }
            $result += $val;
        }

        return $result;

    }
}
