<?php

declare(strict_types=1);

namespace App\Aoc\Year2024;

use App\Services\Aoc\SolutionInterface;
use function dd;
use function dump;
use function explode;
use function implode;
use function str_split;

class Solution15 implements SolutionInterface
{
    public function p1(string $input): mixed
    {
        [$map, $path] = explode("\n\n", $input);

        $currPos = [];
        $path = implode("", explode("\n", $path));
        $grid = [];
        foreach (explode("\n", $map) as $y => $line) {
            foreach (str_split(trim($line)) as $x => $char) {
                    $grid[$x][$y] = $char;
                    if ($char === '@') {
                        $currPos = [$x,$y];
                    }
            }
        }

        $grid[$currPos[0]][$currPos[0]] = '.';
        foreach (str_split($path) as $direction){
            [$dx, $dy] = match($direction){
                '^' => [0,-1],
                '>' => [1, 0],
                'v' => [0,1],
                '<' => [-1, 0],
            };
            $nx = $currPos[0] + $dx;
            $ny = $currPos[1] + $dy;
            if ($grid[$nx][$ny] === "#"){
                continue;
            }

            if ($grid[$nx][$ny] === "O"){
                $pushAmount = 1;
                [$nextBoxX, $nextBoxY] = [$nx + $dx, $ny + $dy];
                $nextBox = $grid[$nextBoxX][$nextBoxY];
                while ($nextBox === "O"){
                    $pushAmount++;
                    [$nextBoxX, $nextBoxY] = [$nextBoxX + $dx, $nextBoxY + $dy];
                    $nextBox = $grid[$nextBoxX][$nextBoxY];
                }
                if ($nextBox === "#"){
                    continue;
                }
                $px = $nx;
                $py = $ny;
                if ($pushAmount > 0){
                    $grid[$px][$py] = '.';
                }

                for ($i = 0; $i < $pushAmount; $i++){
                    $grid[$px + $dx][$py + $dy] = 'O';
                    $px += $dx;
                    $py += $dy;
                }
            }
            $currPos = [$nx, $ny];
        }

        $sum = 0;
        foreach ($grid as $y => $row){
            foreach ($row as $x => $char){
                if ($char === "O"){
                    $sum += 100 * $x + $y;
                }
            }
        }
        return $sum;
    }

    public function p2(string $input): mixed
    {
        [$map, $path] = explode("\n\n", $input);

        $currPos = [];
        $path = implode("", explode("\n", $path));
        $grid = [];
        foreach (explode("\n", $map) as $y => $line) {
            foreach (str_split(trim($line)) as $x => $char) {
                $grid[$x][$y] = $char;
                if ($char === '@') {
                    $currPos = [$x,$y];
                }
            }
        }

        $grid[$currPos[0]][$currPos[0]] = '.';
        $this->resizeGrid($grid);
        dd();
        foreach (str_split($path) as $direction){
            [$dx, $dy] = match($direction){
                '^' => [0,-1],
                '>' => [1, 0],
                'v' => [0,1],
                '<' => [-1, 0],
            };
            $nx = $currPos[0] + $dx;
            $ny = $currPos[1] + $dy;
            if ($grid[$nx][$ny] === "#"){
                continue;
            }

            if ($grid[$nx][$ny] === "O"){
                $pushAmount = 1;
                [$nextBoxX, $nextBoxY] = [$nx + $dx, $ny + $dy];
                $nextBox = $grid[$nextBoxX][$nextBoxY];
                while ($nextBox === "O"){
                    $pushAmount++;
                    [$nextBoxX, $nextBoxY] = [$nextBoxX + $dx, $nextBoxY + $dy];
                    $nextBox = $grid[$nextBoxX][$nextBoxY];
                }
                if ($nextBox === "#"){
                    continue;
                }
                $px = $nx;
                $py = $ny;
                if ($pushAmount > 0){
                    $grid[$px][$py] = '.';
                }

                for ($i = 0; $i < $pushAmount; $i++){
                    $grid[$px + $dx][$py + $dy] = 'O';
                    $px += $dx;
                    $py += $dy;
                }
            }
            $currPos = [$nx, $ny];
        }

        $sum = 0;
        foreach ($grid as $y => $row){
            foreach ($row as $x => $char){
                if ($char === "O"){
                    $sum += 100 * $x + $y;
                }
            }
        }
        return $sum;
    }

    private function resizeGrid(array $grid): array
    {
        foreach ($grid as $y => $row){
            foreach ($row as $x => $char){
                echo $grid[$x][$y];
            }
            echo "\n";
        }
    }
}
