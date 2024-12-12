<?php

declare(strict_types=1);

namespace App\Aoc\Year2024;

use App\Services\Aoc\SolutionInterface;
use SplQueue;
use function explode;
use function in_array;
use function sprintf;
use function str_split;

class Solution12 implements SolutionInterface
{
    public function p1(string $input): mixed
    {
        $visited = [];
        $grid = [];
        $lines = explode("\n", $input);

        foreach ($lines as $y => $line) {
            foreach (str_split(trim($line)) as $x => $cell) {
                $grid[$x][$y] = $cell;
            }
        }
        $cost1 = 0;
        $cost2 = 0;
        foreach ($grid as $x => $row) {
            foreach ($row as $y => $cell) {
                if (isset($visited[$x][$y])) continue;
                [$plants, $fenceLength, $sides] = $this->solve($x, $y, $cell, $grid, $visited);
                $cost1 += $plants * $fenceLength;
                $cost2 += $plants * $sides;
            }
        }

        return sprintf("P1: %s; P2: %s;", $cost1, $cost2);
    }

    public function p2(string $input): mixed
    {
        return $this->p1($input);
    }

    private function solve(int $initX, int $initY, string $plantLetter, array $grid, &$visited): array
    {
        $queue = new SplQueue();
        $fences = [];
        $plants = 0;
        $fenceLength = 0;
        $fencesAmount = 0;
        $queue->enqueue([$initX, $initY, '']);
        $height = count($grid);
        $width = count($grid[0]);

        while (!$queue->isEmpty()) {
            [$x, $y, $direction] = $queue->dequeue();

            if ($x < 0 || $x >= $width || $y < 0 || $y >= $height || $grid[$x][$y] !== $plantLetter) {
                $fenceKeys = [
                    $this->fenceKey($x + 1, $y, $direction),
                    $this->fenceKey($x - 1, $y, $direction),
                    $this->fenceKey($x, $y + 1, $direction),
                    $this->fenceKey($x, $y - 1, $direction)
                ];

                $fencePresence = [
                    in_array($fenceKeys[0], $fences),
                    in_array($fenceKeys[1], $fences),
                    in_array($fenceKeys[2], $fences),
                    in_array($fenceKeys[3], $fences)
                ];

                //A bit of explaining
                // This if here checks if we already recorded the detected out of border cell like different plot or
                // point outside the map, if thats the fact, we know its one side
                if (!($fencePresence[0] || $fencePresence[1] || $fencePresence[2] || $fencePresence[3])) {
                    $fencesAmount++;
                }

                //This one in turn checks if we already recorded the different part of the same side, it basically
                //checks if there are connected fences, if there are, no need to increase the count of sides
                if (($fencePresence[0] && $fencePresence[1]) || ($fencePresence[2] && $fencePresence[3])) {
                    $fencesAmount--;
                }

                $fences[] = $this->fenceKey($x, $y, $direction);
                $fenceLength ++;
                continue;
            }

            if (isset($visited[$x][$y])){
                continue;
            }

            $visited[$x][$y] = true;
            $plants ++;

            foreach ([[1,0,'r'],[0,-1,'u'],[-1,0,'l'],[0,1,'d']] as $adj) {
                $queue->enqueue([$x + $adj[0], $y + $adj[1], $adj[2]]);
            }
        }
        return [$plants, $fenceLength, $fencesAmount];
    }

    private function fenceKey(int $x, int $y, string $direction): string
    {
        return sprintf('%s-%s-%s', $x, $y, $direction);
    }
}
