<?php

declare(strict_types=1);

namespace App\Aoc\Year2015;

use App\Services\Aoc\SolutionInterface;
use function array_map;
use function explode;
use function intval;
use function sort;
use function trim;

class Solution02 implements SolutionInterface
{
    public function p1(string $input): mixed
    {
        $elements = explode("\n", trim($input));

        $wrappingArea = 0;

        foreach ($elements as $element) {
            $sides = array_map(fn($item) => intval($item),explode('x', $element));
            [$area1, $area2, $area3] = [
                $sides[0]*$sides[1],
                $sides[1]*$sides[2],
                $sides[2]*$sides[0],
            ];

            sort($sides);
            $extra = $sides[0]*$sides[1];
            $wrappingArea += (2 * $area1) + (2 * $area2) + (2 * $area3) + $extra;
        }
        return $wrappingArea;
    }

    public function p2(string $input): mixed
    {
        $elements = explode("\n", trim($input));

        $totalRibbonLength = 0;

        foreach ($elements as $element) {
            $sides = array_map(fn($item) => intval($item),explode('x', $element));

            sort($sides);
            $ribbonLength = (2*$sides[0])+(2*$sides[1]);
            $bowLength = $sides[0]*$sides[1]*$sides[2];
            $totalRibbonLength += $ribbonLength + $bowLength;
        }
        return $totalRibbonLength;
    }
}
