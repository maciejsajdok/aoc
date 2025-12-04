<?php

declare(strict_types=1);

namespace App\Aoc\Year2025;

use App\Services\Aoc\SolutionInterface;
use App\Utilities\Grid;
use function array_merge;
use function serialize;
use function str_split;

class Solution04 implements SolutionInterface
{
    public function p1(string $input): mixed
    {
        $rows = explode("\n", trim($input));
        $maxY = count($rows) - 1;
        $grid = [];
        foreach ($rows as $y => $row) {
            $grid[$y] = str_split($row);
        }
        $maxX = count($grid[0]) - 1;
        $result = 0;
        $adjacencyMatrix = array_merge(Grid::$diagonalAdjacencyMatrix, Grid::$straightAdjacencyMatrix);
        foreach ($grid as $y => $row) {
            foreach ($row as $x => $cell) {
                $adjacentPapers = 0;
                if($grid[$y][$x] !== '@') {
                    continue;
                }
                foreach ($adjacencyMatrix as [$dx, $dy]) {
                    $nx = $x + $dx;
                    $ny = $y + $dy;

                    if($nx < 0 || $ny < 0 || $nx > $maxX || $ny > $maxY) {
                        continue;
                    }
                    if($grid[$ny][$nx] === '@') {
                        $adjacentPapers += 1;
                    }

                    if ($adjacentPapers >= 4)
                    {
                        break;
                    }
                }
                if ($adjacentPapers < 4) {
                    $result++;
                }
            }
        }
        return $result;
    }

    public function p2(string $input): mixed
    {
        $rows = explode("\n", trim($input));
        $maxY = count($rows) - 1;
        $grid = [];
        foreach ($rows as $y => $row) {
            $grid[$y] = str_split($row);
        }
        $maxX = count($grid[0]) - 1;
        $result = 0;
        $adjacencyMatrix = array_merge(Grid::$diagonalAdjacencyMatrix, Grid::$straightAdjacencyMatrix);
        $oldGridSignature = '';
        $newGridSignature = md5(serialize($grid));

        while($oldGridSignature !== $newGridSignature) {
            foreach ($grid as $y => $row) {
                foreach ($row as $x => $cell) {
                    $adjacentPapers = 0;
                    if ($grid[$y][$x] !== '@') {
                        continue;
                    }
                    foreach ($adjacencyMatrix as [$dx, $dy]) {
                        $nx = $x + $dx;
                        $ny = $y + $dy;

                        if ($nx < 0 || $ny < 0 || $nx > $maxX || $ny > $maxY) {
                            continue;
                        }
                        if ($grid[$ny][$nx] === '@') {
                            $adjacentPapers += 1;
                        }

                        if ($adjacentPapers >= 4) {
                            break;
                        }
                    }
                    if ($adjacentPapers < 4) {
                        $grid[$y][$x] = '.';
                        $result++;
                    }
                }
            }
            $oldGridSignature = $newGridSignature;
            $newGridSignature = md5(serialize($grid));
        }
        return $result;
    }
}
