<?php

declare(strict_types=1);

namespace App\Aoc\Year2024;

use App\Services\Aoc\SolutionInterface;
use Spatie\Fork\Fork;
use function array_chunk;
use function array_sum;
use function count;
use function in_array;
use function intdiv;
use function str_split;
use const PHP_EOL;

class Solution06 implements SolutionInterface
{
    private array $directions = [
        [-1, 0],
        [0, 1],
        [1, 0],
        [0, -1]
    ];

    public function p1(string $input): mixed
    {
        $visited = [];
        $grid = [];
        $lines = explode("\n", trim($input));
        $guardStartingPoint = [];
        foreach ($lines as $x => $line) {
            $cells = str_split(trim($line));
            foreach ($cells as $y => $cell) {
                if ($cell === '^') {
                    $guardStartingPoint = [$x, $y];
                    $grid[$x][$y] = '.';
                } else {
                    $grid[$x][$y] = $cell;
                }
            }
        }
        return count($this->getUniqueVisitedCoords($grid, $guardStartingPoint));
    }

    public function p2(string $input): mixed
    {

        $grid = [];
        $lines = explode("\n", trim($input));

        $guardStartingPoint = [];
        foreach ($lines as $x => $line) {
            $cells = str_split(trim($line));
            foreach ($cells as $y => $cell) {
                if ($cell === '^') {
                    $guardStartingPoint = [$x, $y];
                    $grid[$x][$y] = '.';
                } else {
                    $grid[$x][$y] = $cell;
                }
            }
        }

        $visited = $this->getUniqueVisitedCoords($grid, $guardStartingPoint);
        $visitedChunks = array_chunk($visited, intdiv(count($visited), 12));
        $callables = [];

        foreach ($visitedChunks as $visitedChunk){
            $callables[] = function () use ($visitedChunk, $guardStartingPoint, $grid) {
                return $this->getLoops($visitedChunk, $guardStartingPoint, $grid);
            };
        }

        $results = Fork::new()->run(
            ...$callables
        );

        return array_sum($results);
    }

    private function getLoops(array $coordsToReplace, array $guardStartingPoint, array $grid): int
    {
        $amountOfCombinations = 0;
        $height = count($grid);
        $width = count($grid[0]);
        foreach ($coordsToReplace as [$x, $y]) {
            if ($grid[$x][$y] === '#' || ($guardStartingPoint[0] === $x && $guardStartingPoint[1] === $y)) {
                continue;
            }

            $grid[$x][$y] = '#';

            $currentPosition = $guardStartingPoint;
            $direction = 0;
            $visitedRocks = [];

            while (true) {
                $newX = $currentPosition[0] + $this->directions[$direction][0];
                $newY = $currentPosition[1] + $this->directions[$direction][1];

                if ($newX < 0 || $newX >= $height || $newY < 0 || $newY >= $width) {
                    break;
                }

                if ($grid[$newX][$newY] === '#') {
                    $hash = "{$direction}-{$newX}-{$newY}";

                    if (isset($visitedRocks[$hash])) {
                        $amountOfCombinations++;
                        break;
                    } else {
                        $visitedRocks[$hash] = true;
                    }

                    $direction = ($direction + 1) % 4;
                    continue;
                }

                $currentPosition = [$newX, $newY];
            }

            $grid[$x][$y] = '.';
        }
        return $amountOfCombinations;
    }

    public function getUniqueVisitedCoords(array $grid, array $guardStartingPoint): array
    {
        $visited = [];
        $width = count($grid[0]);
        $height = count($grid);
        $currentPosition = $guardStartingPoint;
        $direction = 0;
        while (true) {
            if (!in_array($currentPosition, $visited)) {
                $visited[] = $currentPosition;
            }
            $newPosition = [$currentPosition[0] + $this->directions[$direction][0], $currentPosition[1] + $this->directions[$direction][1]];
            if ($newPosition[0] < 0 || $newPosition[0] >= $width || $newPosition[1] < 0 || $newPosition[1] >= $height) break;
            $newGrid = $grid;
            $newGrid[$newPosition[0]][$newPosition[1]] = 'P';
            if ($grid[$newPosition[0]][$newPosition[1]] === '#') {
                $direction = ($direction + 1) % 4;
                continue;
            }
            $currentPosition = $newPosition;
        }
        return $visited;
    }

    private function printGrid(array $grid)
    {
        foreach ($grid as $x => $row) {
            foreach ($row as $y => $cell) {
                echo($cell);
            }
            echo(PHP_EOL);
        }
    }

}
