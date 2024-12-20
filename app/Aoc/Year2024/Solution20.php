<?php

declare(strict_types=1);

namespace App\Aoc\Year2024;

use App\Services\Aoc\SolutionInterface;
use App\Utilities\Grid;
use SplQueue;
use function array_search;
use function array_slice;
use function count;
use function explode;
use function in_array;
use function str_split;

class Solution20 implements SolutionInterface
{
    public function p1(string $input): mixed
    {
        $grid = [];
        $start = $end = [];

        foreach (explode("\n", $input) as $y => $line) {
            foreach (str_split(trim($line)) as $x =>  $cell) {
                $grid[$x][$y] = $cell;
                if ($cell === 'E'){
                    $end = [$x, $y];
                    $grid[$x][$y] = '.';
                }
                if ($cell === 'S'){
                    $start= [$x, $y];
                    $grid[$x][$y] = '.';
                }
            }
        }

        $path = $this->solveMaze($start, $end, $grid);
        $sum = 0;
        foreach ($path as $steps => $coordinates) {
            $restOfPath = array_slice($path, $steps + 1, null, true);
            $c = explode(',', $coordinates);
            foreach (Grid::$straightAdjacencyMatrix as $adj){
                [$dx, $dy] = $adj;
                $nx = (int) $c[0] + $dx;
                $ny = (int) $c[1] + $dy;
                if ($grid[$nx][$ny] === '#') {
                    $nx = $nx + $dx;
                    $ny = $ny + $dy;
                } else {
                    continue;
                }
                if (in_array($nx.','.$ny, $restOfPath)){
                    $dest = array_search($nx.','.$ny, $restOfPath);
                    $diff = $dest - $steps - 2;
                    if ($diff >= 100) {
                        $sum ++;
                    }
                }
            }
        }
        return $sum;
    }

    private function solveMaze(array $start, array $stop, array $grid): array
    {
        $queue = new SplQueue();
        $width = count($grid[0]);
        $height = count($grid);
        $visited = [];
        $path = [];

        $queue->enqueue([...$start, 0]);

        while (!$queue->isEmpty()) {
            [$x, $y, $steps] = $queue->shift();

            if (isset($visited[$x][$y])){
                continue;
            }

            $visited[$x][$y] = true;
            $path[$steps] = $x.','.$y;

            if ($x === $stop[0] && $y === $stop[1]) {
                return $path;
            }

            foreach (Grid::$straightAdjacencyMatrix as $adj) {
                [$dx, $dy] = $adj;
                [$nx, $ny] = [$x + $dx, $y + $dy];

                if ($nx < 0 || $ny < 0 || $nx>=$width || $ny>=$height) {
                    continue;
                }

                $cell = $grid[$nx][$ny];

                if ($cell !== '#'){
                    $queue->enqueue([$nx, $ny, $steps +1]);
                }
            }
        }

        return [];
    }

    public function p2(string $input): mixed
    {
        return null;
    }
}
