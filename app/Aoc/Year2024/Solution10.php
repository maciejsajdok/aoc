<?php

declare(strict_types=1);

namespace App\Aoc\Year2024;

use App\Services\Aoc\SolutionInterface;
use App\Utilities\Grid;
use function array_shift;
use function in_array;

class Solution10 implements SolutionInterface
{
    private array $adjacent = [
        [0, -1], [1, 0], [0, 1], [-1, 0],
    ];

    public function p1(string $input): mixed
    {
        $start = [];
        $grid = Grid::fromInput($input, function ($p1, $p2, mixed $val) use (&$start){
            $res = (int) $val;
            if ($res === 0){
                $start[] = [$p1,$p2];
            }

            return $res;
        });

        $height = count($grid);
        $width = count($grid[0]);
        $trailHeads = 0;
        foreach ($start as $startPos) {
            $queue = [$startPos];
            $visited = [];
            while (!empty($queue)) {
                [$x, $y] = array_shift($queue);
                if (in_array([$x, $y], $visited)) {
                    continue;
                }
                $visited[] = [$x, $y];
                $prevVal = (int) $grid[$x][$y];
                if ($prevVal === 9) {
                    $trailHeads ++;
                    continue;
                }

                foreach ($this->adjacent as $adj) {
                    $nx = $x + $adj[0];
                    $ny = $y + $adj[1];

                    if ($nx >= 0 && $nx < $width && $ny >= 0 && $ny < $height) {
                        $newVal = (int)$grid[$nx][$ny];
                        $diff = $newVal - $prevVal;

                        if ($diff === 1) {
                            $queue[] = [$nx, $ny];
                        }
                    }
                }
            }
        }
        return $trailHeads;
    }

    public function p2(string $input): mixed
    {
        $start = [];
        $grid = Grid::fromInput($input, function ($p1, $p2, mixed $val) use (&$start){
            $res = (int) $val;
            if ($res === 0){
                $start[] = [$p1,$p2];
            }

            return $res;
        });

        $height = count($grid);
        $width = count($grid[0]);
        $trailHeads = 0;
        foreach ($start as $startPos) {
            $queue = [$startPos];
            while (!empty($queue)) {
                [$x, $y] = array_shift($queue);
                $prevVal = $grid[$x][$y];
                if ($prevVal === 9) {
                    $trailHeads ++;
                    continue;
                }

                foreach ($this->adjacent as $adj) {
                    $nx = $x + $adj[0];
                    $ny = $y + $adj[1];

                    if ($nx >= 0 && $nx < $width && $ny >= 0 && $ny < $height) {
                        $newVal = $grid[$nx][$ny];
                        $diff = $newVal - $prevVal;

                        if ($diff === 1) {
                            $queue[] = [$nx, $ny];
                        }
                    }
                }
            }
        }
        return $trailHeads;
    }
}
