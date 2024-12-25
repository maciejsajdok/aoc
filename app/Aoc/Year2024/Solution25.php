<?php

declare(strict_types=1);

namespace App\Aoc\Year2024;

use App\Services\Aoc\SolutionInterface;
use function array_slice;
use function count;
use function str_split;

class Solution25 implements SolutionInterface
{
    public function p1(string $input): mixed
    {
        $elements = explode("\n\n", $input);

        $keys = [];
        $locks = [];

        foreach ($elements as $element) {
            $rows = explode("\n", trim($element));
            $isLock = $rows[0] === '#####';
            $segment = [0,0,0,0,0];
            foreach ($isLock ? array_slice($rows, 1) : array_slice($rows, 0, count($rows) - 1 ) as $row) {
                foreach (str_split($row) as $key => $value) {
                    if ($value === '#') {
                        $segment[$key]++;
                    }
                }
            }

            $isLock ? $locks[] = $segment : $keys[] = $segment;
        }

        $fitAmount = 0;

        foreach ($locks as $lock) {
            foreach ($keys as $key) {
                $doesFit = true;
                for ($i = 0; $i < count($lock); $i++) {
                    if ($lock[$i] + $key[$i] > 5) {
                        $doesFit = false;
                    }
                }
                if ($doesFit) {
                    $fitAmount ++;
                }
            }
        }

        return $fitAmount;
    }

    public function p2(string $input): mixed
    {
        return null;
    }
}
