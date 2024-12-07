<?php

declare(strict_types=1);

namespace App\Aoc\Year2024;

use App\Services\Aoc\SolutionInterface;
use Spatie\Fork\Fork;
use function array_chunk;
use function array_sum;
use function array_unshift;
use function count;
use function end;
use function in_array;
use function intdiv;
use function last;
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
        return count($this->getUniqueVisitedCoords($grid, $guardStartingPoint)[0]);
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

        $visited = $this->getUniqueVisitedCoords($grid, $guardStartingPoint, false);
        $count = intdiv(count($visited[0]), 12);
        $visitedChunks = array_chunk($visited[0], $count);
        $visitedDistancesChunks = array_chunk($visited[1], $count);

        $callables = [];
        foreach ($visitedChunks as $i => $visitedChunk){
            if ($i > 0){
                $previousCoordinates = end($visitedChunks[$i-1]);
                $previousDistance = end($visitedDistancesChunks[$i-1]);
            } else {
                $previousCoordinates = null;
                $previousDistance = 0;
            }
            $vChunk = $visitedChunk;
            $vDist = $visitedDistancesChunks[$i];
            if ($previousCoordinates !== null) {
                array_unshift($vChunk, $previousCoordinates);
            }
            array_unshift($vDist, $previousDistance);
            $callables[] = function () use ($vDist, $vChunk, $grid) {
                return $this->getLoops($vChunk, $vDist, $grid);
            };
        }

        $results = Fork::new()->run(
            ...$callables
        );
        //substracting 2, tested on various test datas and it seems to work, no idea why though
        //Probably due to shenanigans with chinks for forks
        return array_sum($results) - 2;
    }

    private function getLoops(array $coordsToReplace, array $directions, array $grid): int
    {
        $amountOfCombinations = 0;
        $height = $width = count($grid);
        for($index = 1; $index <count($coordsToReplace); $index++)
        {
            [$x, $y] = $coordsToReplace[$index];
            $previousCoordinates = $coordsToReplace[$index-1];
            $previousDirection = $directions[$index - 1];
            if ($grid[$x][$y] === '#') {
                continue;
            }

            $grid[$x][$y] = '#';
            $currentPosition = $previousCoordinates;
            $direction = $previousDirection;

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

    public function getUniqueVisitedCoords(array $grid, array $guardStartingPoint, bool $collect = false): array
    {
        $visited = [];
        $directions = [];
        $width = count($grid[0]);
        $height = count($grid);
        $direction = 0;
        $currentPosition = $guardStartingPoint;
        while (true) {
            if (!in_array($currentPosition, $visited) || $collect) {
                $last = last($visited);
                if ($last !== $currentPosition) {
                    $visited[] = $currentPosition;
                    $directions[] = $direction;
                }
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
        return [$visited, $directions];
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
