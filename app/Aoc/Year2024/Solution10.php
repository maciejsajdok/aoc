<?php

declare(strict_types=1);

namespace App\Aoc\Year2024;

use App\Services\Aoc\SolutionInterface;
use function array_shift;
use function in_array;
use function str_split;

class Solution10 implements SolutionInterface
{
    private array $adjacent = [
        [0, -1], [1, 0], [0, 1], [-1, 0],
    ];

    public function p1(string $input): mixed
    {
        $lines = explode("\n", $input);
        $grid = [];
        $start = [];
        foreach ($lines as $y => $line) {
            foreach (str_split($line) as $x => $char) {
                $grid[$x][$y] = (int)$char;
                if ((int)$char === 0) {
                    $start[] = [$x, $y];
                }
            }
        }

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

    public function p2(string $input): mixed
    {
        $lines = explode("\n", $input);
        $grid = [];
        $start = [];
        foreach ($lines as $y => $line) {
            foreach (str_split($line) as $x => $char) {
                $grid[$x][$y] = (int)$char;
                if ((int)$char === 0) {
                    $start[] = [$x, $y];
                }
            }
        }

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
