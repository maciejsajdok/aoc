<?php

declare(strict_types=1);

namespace App\Aoc\Year2015;

use App\Services\Aoc\SolutionInterface;
use function count;
use function explode;
use function in_array;
use function str_split;
use function trim;

class Solution18 implements SolutionInterface
{
    private array $adjacencyArray = [
        [-1, -1], [0,-1], [1, -1],
        [-1, 0], [1, 0],
        [-1, 1], [0, 1], [1, 1]
    ];

    private function countAdjacentLights(array $grid, int $x, int $y) : int
    {
        $found = 0;

        foreach ($this->adjacencyArray as [$dx, $dy]){
            if( isset($grid[$x + $dx][$y + $dy]) && $grid[$x + $dx][$y + $dy] === '#'){
                $found++;
            }
        }
        return $found;
    }

    private function simulateGrid(array $grid, int $p = 1): array
    {
        $newGrid = [];

        $max = count($grid[0])-1;

        foreach ($grid as $x => $row){
            foreach ($row as $y => $cell){
                $adjacentValue = $this->countAdjacentLights($grid, $x, $y);

                if ($p !== 1){
                    if (in_array(
                        $x.'-'.$y,[
                            '0-0', '0-'.$max, $max.'-0', $max.'-'.$max
                        ]
                    )){
                        $newGrid[$x][$y] = '#';
                        continue;
                    };
                }
                if ($adjacentValue === 3 && $cell === '.'){
                    $newGrid[$x][$y] = '#';
                } else if (in_array($adjacentValue, [2,3]) && $cell === '#'){
                    $newGrid[$x][$y] = '#';
                } else {
                    $newGrid[$x][$y] = '.';
                }
            }
        }
        return $newGrid;
    }

    private function countLights(array $grid) : int
    {
        $found = 0;
        foreach ($grid as $x => $row) {
            foreach ($row as $y => $cell) {
                if ($cell === '#') {
                    $found++;
                }
            }
        }
        return $found;
    }
    public function p1(string $input): mixed
    {
        $grid = [];
        $rows = explode("\n", trim($input));
        foreach ($rows as $i => $row){
            $cells = str_split(trim($row));
            $grid[$i] = $cells;
        }

        for ($i = 0; $i < 100; $i++){
            $grid = $this->simulateGrid($grid, 1);
        }


        return $this->countLights($grid);
    }

    public function p2(string $input): mixed
    {
        $grid = [];
        $rows = explode("\n", trim($input));
        foreach ($rows as $i => $row){
            $cells = str_split(trim($row));
            $grid[$i] = $cells;
        }

        for ($i = 0; $i < 100; $i++){
            $grid = $this->simulateGrid($grid, 2);
        }


        return $this->countLights($grid);
    }
}
