<?php

declare(strict_types=1);

namespace App\Aoc\Year2024;

use App\Services\Aoc\SolutionInterface;
use function count;
use function fgets;
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
        $width = count($grid[0]);
        $height = count($lines);
        $amountOfCombinations = 0;
        foreach ($grid as $x => $row){
            foreach ($row as $y => $cell){
                $newGrid = $grid;
                if ($newGrid[$x][$y] === '#' || $guardStartingPoint[0] === $x && $guardStartingPoint[1] === $y) {
                    continue;
                }
                $newGrid[$x][$y] = '#';
                $count = 0;

                $currentPosition = $guardStartingPoint;
                $direction = 0;
                while(true) {
                    $count ++;
                    if($count > 5500){
                        $amountOfCombinations++;
                        break;
                    }
                    $newPosition = [$currentPosition[0] + $this->directions[$direction][0], $currentPosition[1] + $this->directions[$direction][1]];
                    if ($newPosition[0]<0 || $newPosition[0]>=$width || $newPosition[1]<0 || $newPosition[1]>=$height) break;
                    if ($newGrid[$newPosition[0]][$newPosition[1]] === '#') {
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
