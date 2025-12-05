<?php

declare(strict_types=1);

namespace App\Aoc\Year2025;

use App\Services\Aoc\SolutionInterface;
use function explode;
use function usort;

class Solution05 implements SolutionInterface
{
    public function p1(string $input): mixed
    {
        $data = explode("\n\n", trim($input));
        $ranges = explode("\n", trim($data[0]));
        $products = explode("\n", trim($data[1]));
        $fresh = 0;

        foreach($products as $product){
            $isSpoiled = true;
            foreach($ranges as $range){
                [$min, $max] = explode('-', $range);

                if($product >= $min && $product <= $max){
                    $isSpoiled = false;
                }
                if($isSpoiled === false){
                    $fresh++;
                    break;
                }
            }
        }
        return $fresh;
    }

    public function p2(string $input): mixed
    {
        $data = explode("\n\n", trim($input));
        $ranges = explode("\n", trim($data[0]));
        $fresh = 0;
        $rangesList = [];
        foreach($ranges as $range){
            [$min, $max] = explode('-', $range);
            $rangesList[] = [(int) $min, (int) $max];
        }
        usort($rangesList, function ($a, $b) {
            return $a[0] <=> $b[0];
        });

        $mergedRanges = [$rangesList[0]];
        unset($rangesList[0]);
        foreach($rangesList as $i => [$min, $max]){
            [$prevMin, $prevMax] = $mergedRanges[count($mergedRanges) - 1];

            if($min > $prevMax){
                $mergedRanges[] = [$min, $max];
            } else {
                $mergedRanges[count($mergedRanges) - 1][1] = max($prevMax, $max);
            }
        }

        foreach($mergedRanges as [$min, $max]){
            $fresh += $max - $min + 1;
        }

        return $fresh;
    }
}
