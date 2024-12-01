<?php

declare(strict_types=1);

namespace App\Aoc\Year2015;

use App\Services\Aoc\SolutionInterface;
use function array_is_list;
use function explode;
use function in_array;
use function is_array;
use function is_int;
use function json_decode;
use function trim;

class Solution12 implements SolutionInterface
{
    public function p1(string $input): mixed
    {
        $lines = explode("\n", trim($input));
        $result = 0;
        foreach ($lines as $line) {
            $decodedJson = json_decode($line, true);
            $sum = $this->calculateSumFromArray($decodedJson);
            $result += $sum;
        }
        return $result;
    }

    public function p2(string $input): mixed
    {
        $lines = explode("\n", trim($input));
        $result = 0;
        foreach ($lines as $line) {
            $decodedJson = json_decode($line, true);
            $sum = $this->calculateSumFromArray($decodedJson, false);
            $result += $sum;
        }
        return $result;
    }

    private function calculateSumFromArray(array $array, bool $p = true): int
    {
        $sum = 0;
        if ($p === false) {
            if (!array_is_list($array) && in_array('red', $array)) {
                return 0;
            }
        }
        foreach ($array as $item) {
            if (is_array($item)) {
                $sum += $this->calculateSumFromArray($item, $p);
            } else if(is_int($item)) {
                $sum += $item;
            }
        }

        return $sum;
    }
}
