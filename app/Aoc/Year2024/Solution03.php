<?php

declare(strict_types=1);

namespace App\Aoc\Year2024;

use App\Services\Aoc\SolutionInterface;
use function dump;
use function preg_match_all;
use function str_split;
use function substr;

class Solution03 implements SolutionInterface
{
    public function p1(string $input): mixed
    {
        dump($input);
        preg_match_all('/mul\((\d+),(\d+)\)/', $input, $matches);

        $pairs = [];

        foreach ($matches[1] as $key => $match) {
            $pairs[] = [$match, $matches[2][$key]];
        }

        $sum = 0;

        foreach ($pairs as $pair) {
            $sum += $pair[0] * $pair[1];
        }
        return $sum;
    }

    public function p2(string $input): mixed
    {
        $segments = '';

        $read = true;
        $chars = str_split($input);
        for ($i = 0; $i < count($chars); $i++) {
            if (substr($input, $i, 7) === "don't()") {
                $read = false;
            }
            if (substr($input, $i, 4) === "do()") {
                $read = true;
            }
            if ($read) {
                $segments .= $chars[$i];
            }
        }


        preg_match_all('/mul\((\d+),(\d+)\)/', $segments, $matches);

        $pairs = [];

        foreach ($matches[1] as $key => $match) {
            $pairs[] = [$match, $matches[2][$key]];
        }

        $sum = 0;

        foreach ($pairs as $pair) {
            $sum += $pair[0] * $pair[1];
        }
        return $sum;
    }
}
