<?php

declare(strict_types=1);

namespace App\Aoc\Year2016;

use App\Services\Aoc\SolutionInterface;
use function array_filter;
use function array_map;
use function dd;
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
        $data = array_map(function (string $s){
            $edges = array_filter(explode(' ', $s));
            return $edges;
        }, explode("\n", $input));

        foreach ($data as $i)
        dd($data);
        $res = 0;

        foreach ($edges as $edgeSet) {
            if ((int)$edgeSet[0] + (int)$edgeSet[1] > (int)$edgeSet[2]){
                $res ++;
            }
        }
        return $res;
    }
}
