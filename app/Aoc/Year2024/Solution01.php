<?php

declare(strict_types=1);

namespace App\Aoc\Year2024;

use App\Services\Aoc\SolutionInterface;
use function array_count_values;
use function count;
use function explode;
use function trim;

class Solution01 implements SolutionInterface
{
    public function p1(string $input): mixed
    {
        $lines = explode("\n", trim($input));
        $list1 = [];
        $list2 = [];
        foreach ($lines as $line) {
            $pair = explode(" ", $line);
            $list1[] = $pair[0];
            $list2[] = $pair[count($pair)-1];
        }

        sort($list1);
        sort($list2);

        $totalDistance = 0;
        foreach ($list1 as $index => $item) {
            $totalDistance += abs($item - $list2[$index]);
        }
        return $totalDistance;
    }

    public function p2(string $input): mixed
    {
        $lines = explode("\n", trim($input));
        $list1 = [];
        $list2 = [];
        foreach ($lines as $line) {
            $pair = explode(" ", $line);
            $list1[] = $pair[0];
            $list2[] = $pair[count($pair)-1];
        }

        $similarityScore = 0;
        $occurrences = array_count_values($list2);
        foreach ($list1 as $item) {
            $itemOccurrences = $occurrences[$item] ?? 0;

            $similarityScore += $item * $itemOccurrences;
        }

        return $similarityScore;
    }
}
