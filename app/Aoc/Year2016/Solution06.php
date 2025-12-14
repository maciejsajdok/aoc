<?php

declare(strict_types=1);

namespace App\Aoc\Year2016;

use App\Services\Aoc\SolutionInterface;
use App\Utilities\Arr;
use function array_count_values;
use function array_keys;
use function array_map;
use function asort;
use function str_split;

class Solution06 implements SolutionInterface
{
    public function p1(string $input): mixed
    {
        $data = explode("\n", trim($input));
        $words = array_map(fn(string $word) => str_split($word), $data);
        Arr::rotate2DArray($words, 1);
        $values = array_map(function (array $letters): string {
            $rank = array_count_values($letters);
            arsort($rank);
            return array_keys($rank)[0];
        }, $words);

        return implode("", $values);
    }

    public function p2(string $input): mixed
    {
        $data = explode("\n", trim($input));
        $words = array_map(fn(string $word) => str_split($word), $data);
        Arr::rotate2DArray($words, 1);
        $values = array_map(function (array $letters): string {
            $rank = array_count_values($letters);
            asort($rank);
            return array_keys($rank)[0];
        }, $words);

        return implode("", $values);
    }
}
