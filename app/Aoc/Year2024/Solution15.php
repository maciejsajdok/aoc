<?php

declare(strict_types=1);

namespace App\Aoc\Year2024;

use App\Services\Aoc\SolutionInterface;
use App\Utilities\Grid;
use SplQueue;
use function array_map;
use function explode;
use function implode;
use function in_array;
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
                    $currPos = [$x, $y];
                }
            }
        }

        $grid[$currPos[0]][$currPos[0]] = '.';
        foreach (str_split($path) as $direction) {
            [$dx, $dy] = match ($direction) {
                '^' => [0, -1],
                '>' => [1, 0],
                'v' => [0, 1],
                '<' => [-1, 0],
            };
            $nx = $currPos[0] + $dx;
            $ny = $currPos[1] + $dy;
            if ($grid[$nx][$ny] === "#") {
                continue;
            }
            if ($grid[$nx][$ny] === "O") {
                $pushAmount = 1;
                [$nextBoxX, $nextBoxY] = [$nx + $dx, $ny + $dy];
                $nextBox = $grid[$nextBoxX][$nextBoxY];
                while ($nextBox === "O") {
                    $pushAmount++;
                    [$nextBoxX, $nextBoxY] = [$nextBoxX + $dx, $nextBoxY + $dy];
                    $nextBox = $grid[$nextBoxX][$nextBoxY];
                }
                if ($nextBox === "#") {
                    continue;
                }
                $px = $nx;
                $py = $ny;
                if ($pushAmount > 0) {
                    $grid[$px][$py] = '.';
                }

                for ($i = 0; $i < $pushAmount; $i++) {
                    $grid[$px + $dx][$py + $dy] = 'O';
                    $px += $dx;
                    $py += $dy;
                }
            }
            $currPos = [$nx, $ny];
        }

        $sum = 0;
        foreach ($grid as $y => $row) {
            foreach ($row as $x => $char) {
                if ($char === "O") {
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
                if ($char === 'O') {
                    $grid[(2 * $x)][] = '[';
                    $grid[(2 * $x) + 1][] = ']';
                } else if ($char === '.') {
                    $grid[(2 * $x)][] = '.';
                    $grid[(2 * $x) + 1][] = '.';
                } else if ($char === '#') {
                    $grid[(2 * $x)][] = '#';
                    $grid[(2 * $x) + 1][] = '#';
                } else {
                    $grid[(2 * $x)][] = '.';
                    $grid[(2 * $x) + 1][] = '.';
                }
                if ($char === '@') {
                    $currPos = [2 * $x, $y];
                }
            }
        }

        foreach (str_split($path) as $direction) {
//            echo "\nMove ".$direction.":\n";
//            Grid::prettyArray($grid, $currPos);
            [$dx, $dy] = match ($direction) {
                '^' => [0, -1],
                '>' => [1, 0],
                'v' => [0, 1],
                '<' => [-1, 0],
            };
            $nx = $currPos[0] + $dx;
            $ny = $currPos[1] + $dy;

            if ($grid[$nx][$ny] === "#") {
                continue;
            }
            if (in_array($grid[$nx][$ny], ['[', ']'])) {
                $canPush = true;
                $queue = new SplQueue();
                $queue->enqueue([$nx, $ny]);
                $containersToPushKeys = [];
                while(!$queue->isEmpty()) {
                    [$x, $y] = $queue->shift();

                    if (in_array($this->key($x,$y), $containersToPushKeys)) {
                        continue;
                    }


                    $char = $grid[$x][$y];
                    if ($char === '#'){
                        $canPush = false;
                        break;
                    }
                    if (in_array($char, ['[',']'])) {
                        if ($char === '[' && !in_array($this->key($x + 1, $y), $containersToPushKeys)) {
                            $queue->enqueue([$x + 1, $y]);
                        }

                        if ($char === ']' && !in_array($this->key($x - 1, $y), $containersToPushKeys)) {
                            $queue->enqueue([$x - 1, $y]);
                        }
                        $containersToPushKeys[] = $this->key($x,$y);
                        $queue->enqueue([$x + $dx, $y + $dy]);
                    }

                }

                $newGrid = $grid;

                if (!$canPush){
                    continue;
                }

                foreach ($containersToPushKeys as $containerToPushKey) {
                    [$cx, $cy] = array_map('intval', explode(',', $containerToPushKey));
                    $newGrid[$cx][$cy] = '.';
                }

                foreach ($containersToPushKeys as $containerToPushKey) {
                    [$cx, $cy] = array_map('intval', explode(',', $containerToPushKey));
                    $newGrid[$cx + $dx][$cy + $dy] = $grid[$cx][$cy];
                }

                $grid = $newGrid;
            }
            $currPos = [$nx, $ny];
        }

//        Grid::prettyArray($grid, $currPos);
        $sum = 0;
        foreach ($grid as $y => $row) {
            foreach ($row as $x => $char) {
                if ($char === "[") {
                    $sum += 100 * $x + $y;
                }
            }
        }
        return $sum;
    }

    private function key($x, $y): string
    {
        return $x.','.$y;
    }
}
