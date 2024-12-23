<?php

declare(strict_types=1);

namespace App\Utilities;

class Graph
{
    public static function getDOTFromAdjacencyArray(array $adjacencyList, $isDirected = false): string
    {
        $graphType = $isDirected ? 'digraph' : 'graph';
        $connector = $isDirected ? '->' : '--';
        $dot = "$graphType G {\n";

        foreach ($adjacencyList as $node => $connections) {
            foreach ($connections as $connectedNode) {
                $dot .= "    \"$node\" $connector \"$connectedNode\";\n";
            }
        }

        $dot .= "}\n";
        return $dot;
    }

    public static function bronKerbosch(array $currentlyGrowingClique, array $potentialNodes, array $processedNodes, array $graph, &$cliques): void
    {
        if (empty($potentialNodes) && empty($processedNodes)) {
            $cliques[] = $currentlyGrowingClique;
            return;
        }

        foreach ($potentialNodes as $node) {
            $newR = array_merge($currentlyGrowingClique, [$node]);
            $newP = array_intersect($potentialNodes, $graph[$node]);
            $newX = array_intersect($processedNodes, $graph[$node]);

            self::bronKerbosch($newR, $newP, $newX, $graph, $cliques);

            $potentialNodes = array_diff($potentialNodes, [$node]);
            $processedNodes = array_merge($processedNodes, [$node]);
        }
    }
}
