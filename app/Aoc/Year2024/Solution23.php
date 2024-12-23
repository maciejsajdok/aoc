<?php

declare(strict_types=1);

namespace App\Aoc\Year2024;

use App\Services\Aoc\SolutionInterface;
use App\Utilities\Graph;
use Illuminate\Support\Str;
use function array_filter;
use function array_intersect;
use function array_keys;
use function implode;
use function max;
use function sort;

class Solution23 implements SolutionInterface
{
    public function p1(string $input): mixed
    {
        $lines = explode("\n", trim($input));
        $graph = [];

        foreach ($lines as $line) {
            [$nodeA, $nodeB] = explode("-", trim($line));
            $graph[$nodeA][] = $nodeB;
            $graph[$nodeB][] = $nodeA;
        }

        $triplets = [];

        foreach ($graph as $node => $neighbours){
            foreach ($neighbours as $neighbour) {
                $common = array_intersect($neighbours, $graph[$neighbour]);
                foreach ($common as $third){
                    $triplet = [$node, $neighbour, $third];
                    sort($triplet);
                    $triplets[implode(',', $triplet)] = $triplet;
                }
            }
        }

        $triplets = array_filter($triplets, function (array $triplet): bool {
            foreach ($triplet as $machine) {
                if (Str::startsWith($machine, 't')){
                    return true;
                }
            }
            return false;
        });

        return count($triplets);
    }

    public function p2(string $input): mixed
    {
        $lines = explode("\n", trim($input));
        $graph = [];
        foreach ($lines as $line) {
            [$nodeA, $nodeB] = explode("-", trim($line));
            $graph[$nodeA][] = $nodeB;
            $graph[$nodeB][] = $nodeA;
        }

        Graph::bronKerbosch([], array_keys($graph), [], $graph,$cliques);

        $biggestClique = max($cliques);
        sort($biggestClique);
        return implode(',', $biggestClique);
    }
}
