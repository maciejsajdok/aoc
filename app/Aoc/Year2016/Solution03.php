<?php

declare(strict_types=1);

namespace App\Aoc\Year2016;

use App\Services\Aoc\SolutionInterface;
use App\Utilities\Arr;
use function array_chunk;
use function array_filter;
use function array_map;
use function explode;
use function sort;

class Solution03 implements SolutionInterface
{
    public function p1(string $input): mixed
    {
        $edges = array_map(function (string $s){
            $edges = array_filter(explode(' ', $s));
            sort($edges);
            return $edges;
        }, explode("\n", $input));

        $res = 0;

        foreach ($edges as $edgeSet) {
            if ((int)$edgeSet[0] + (int)$edgeSet[1] > (int)$edgeSet[2]){
                $res ++;
            }
        }
        return $res;
    }

    public function p2(string $input): mixed
    {

        $edges = array_map(function (string $s){
            $edges = array_filter(explode(' ', $s));
            return $edges;
        }, explode("\n", $input));
        Arr::rotate2DArray($edges, 1);

        $result = 0;
        foreach ($edges as $edgeSet) {
            foreach (array_chunk($edgeSet, 3) as $chunk) {
                sort($chunk);

                if ((int)$chunk[0] + (int)$chunk[1] > (int)$chunk[2]){
                    $result ++;
                }
            }
        }

        return $result;
    }
}
