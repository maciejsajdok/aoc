<?php

declare(strict_types=1);

namespace App\Aoc\Year2024;

use App\Services\Aoc\SolutionInterface;
use Spatie\Fork\Fork;
use function array_search;
use function array_slice;
use function array_sum;
use function count;
use function fgets;
use function floor;
use function in_array;
use function str_split;
use const PHP_EOL;
use const STDIN;

class Solution06 implements SolutionInterface
{
    private array $directions = [
       [-1, 0],
       [0,1],
       [1, 0],
       [0,-1]
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
                    $guardStartingPoint= [$x,$y];
                    $grid[$x][$y] = '.';
                } else {
                    $grid[$x][$y] = $cell;
                }
            }
        }
        $width = count($grid[0]);
        $height = count($lines);
        $currentPosition = $guardStartingPoint;
        $direction = 0;
        while(true) {
            if(!in_array($currentPosition, $visited))
            {
                $visited[] = $currentPosition;
            }
            $newPosition = [$currentPosition[0] + $this->directions[$direction][0], $currentPosition[1] + $this->directions[$direction][1]];
            if ($newPosition[0]<0 || $newPosition[0]>=$width || $newPosition[1]<0 || $newPosition[1]>=$height) break;
            $newGrid = $grid;
            $newGrid[$newPosition[0]][$newPosition[1]] = 'P';
//            $this->printGrid($newGrid);
//            fgets(STDIN);
            if ($grid[$newPosition[0]][$newPosition[1]] === '#') {
                $direction = ($direction + 1) % 4;
                continue;
            }
            $currentPosition = $newPosition;
        }

        return count($visited);
    }

    public function p2(string $input): mixed
    {

        $visited = [];
        $grid = [];
        $lines = explode("\n", trim($input));

        $guardStartingPoint = [];
        foreach ($lines as $x => $line) {
            $cells = str_split(trim($line));
            foreach ($cells as $y => $cell) {
                if ($cell === '^') {
                    $guardStartingPoint= [$x,$y];
                    $grid[$x][$y] = '.';
                } else {
                    $grid[$x][$y] = $cell;
                }
            }
        }

        $callables = [];

        $parts = 5;
        $widthSegmentSize = (int) floor((count($grid[0])/$parts));
        $heightSegmentSize = (int) floor((count($grid)/$parts));

        for ($i = 0; $i <= $widthSegmentSize * $parts; $i += $widthSegmentSize){
            for ($j = 0; $j <= $heightSegmentSize * $parts; $j+=$heightSegmentSize){
                $callables[] = function () use ($i, $j, $grid, $guardStartingPoint, $heightSegmentSize, $widthSegmentSize) {
                    return $this->getLoops([$i, $j],[$i+$widthSegmentSize,$j + $heightSegmentSize], $guardStartingPoint, $grid);
                };
            }
        }

        $results = Fork::new()->run(
            ...$callables
        );

        return array_sum($results);
    }

    private function getLoops(array $upperLeftBoundary, array $bottomRightBoundary, array $guardStartingPoint, array $grid): int
    {
        $amountOfCombinations = 0;
        $height = count($grid);
        $width = count($grid[0]);
        for($x = $upperLeftBoundary[0]; $x<$bottomRightBoundary[0]; $x++){
            for($y = $upperLeftBoundary[1]; $y<$bottomRightBoundary[1]; $y++){
                $newGrid = $grid;
                if ($newGrid[$x][$y] === '#' || $guardStartingPoint[0] === $x && $guardStartingPoint[1] === $y) {
                    continue;
                }
                $newGrid[$x][$y] = '#';

                $currentPosition = $guardStartingPoint;
                $direction = 0;
                $visitedRocks = [];
                while(true) {
                    $newPosition = [$currentPosition[0] + $this->directions[$direction][0], $currentPosition[1] + $this->directions[$direction][1]];
                    if ($newPosition[0]<0 || $newPosition[0]>=$width || $newPosition[1]<0 || $newPosition[1]>=$height) break;
                    if ($newGrid[$newPosition[0]][$newPosition[1]] === '#') {
                        $hash = "{$direction}-{$newPosition[0]}-{$newPosition[1]}";
                        if (in_array($hash, $visitedRocks)){
                            $amountOfCombinations++;
                            $loops[] = array_slice($visitedRocks, array_search($hash, $visitedRocks));
                            break;
                        } else {
                            $visitedRocks[] = $hash;
                        }
                        $direction = ($direction + 1) % 4;
                        continue;
                    }
                    $currentPosition = $newPosition;
                }
            }
        }


        return $amountOfCombinations;
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
