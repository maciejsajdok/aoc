<?php

declare(strict_types=1);

namespace App\Aoc\Year2024;

use App\Services\Aoc\SolutionInterface;
use function explode;
use function str_split;
use function strlen;
use function trim;

class Solution04 implements SolutionInterface
{
    private array $directions = [
        [0, 1],
        [1, 0],
        [1, 1],
        [1, -1],
        [0, -1],
        [-1, 0],
        [-1, -1],
        [-1, 1],
    ];
    public function p1(string $input): mixed
    {
        $lines = explode("\n", trim($input));
        $grid = [];
        foreach ($lines as $line) {
            $grid[] = str_split(trim($line));
        }

        return $this->countWords($grid, 'XMAS');
    }

    public function p2(string $input): mixed
    {
        $lines = explode("\n", trim($input));
        $grid = [];
        foreach ($lines as $line) {
            $grid[] = str_split(trim($line));
        }

        return $this->findWordsInXShape($grid);
    }

    private function countWords(array $grid, string $word): int
    {
        $rows = count($grid);
        $cols = count($grid[0]);
        $wordLength = strlen($word);
        $count = 0;
        for ($i = 0; $i < $rows; $i++) {
            for ($j = 0; $j < $cols; $j++) {
                foreach ($this->directions as [$dx, $dy]) {
                    $found = true;
                    for ($z = 0; $z < $wordLength; $z++) {
                        $x = $i + $dx * $z;
                        $y = $j + $dy * $z;
                        if ($x < 0 || $x >= $rows || $y < 0 || $y >= $cols || $grid[$x][$y] !== $word[$z]) {
                            $found = false;
                            break;
                        }
                    }
                    if ($found) {
                        $count++;
                    }
                }
            }
        }
        return $count;
    }

    private function findWordsInXShape(array $grid): int
    {
        $rows = count($grid);
        $cols = count($grid[0]);
        $count = 0;

        $validMAS = [['M', 'A', 'S'], ['S', 'A', 'M']];

        for ($i = 0; $i < $rows; $i++) {
            for ($j = 0; $j < $cols; $j++) {
                if ($grid[$i][$j] !== 'A') {
                    continue;
                }
                $topLeft = $grid[$i - 1][$j - 1] ?? null;
                $topRight = $grid[$i - 1][$j + 1] ?? null;
                $bottomLeft = $grid[$i + 1][$j - 1] ?? null;
                $bottomRight = $grid[$i + 1][$j + 1] ?? null;

                $topMAS = [$topLeft, 'A', $bottomRight];
                $bottomMAS = [$bottomLeft, 'A', $topRight];

                if (in_array($topMAS, $validMAS) && in_array($bottomMAS, $validMAS)) {
                    $count++;
                }
            }
        }

        return $count;
    }
}
