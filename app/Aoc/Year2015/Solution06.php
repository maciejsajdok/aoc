<?php

declare(strict_types=1);

namespace App\Aoc\Year2015;

use App\Services\Aoc\SolutionInterface;
use Illuminate\Support\Str;
use function count;
use function explode;

class Solution06 implements SolutionInterface
{
    public function p1(string $input): mixed
    {
        return $this->process($input);
    }

    public function p2(string $input): mixed
    {
        return $this->process($input, 2);
    }

    private function process(string $input, int $part = 1): mixed
    {

        $instructions = explode("\n", $input);
        $operations = [];

        /**
         * 0 turn on
         * 1 turn off
         * 2 toggle
         */
        foreach ($instructions as $instruction) {
            if (empty($instruction)) {
                continue;
            }

            $operations[] = $this->convertInstructionToOperation($instruction);
        }

        $grid = $this->initializeGrid($part);

        foreach ($operations as $operation) {
            switch ($operation[0]) {
                case 0:
                    $this->setLights($grid, (int)$operation[1][0][0], (int)$operation[1][0][1], (int)$operation[1][1][0], (int)$operation[1][1][1], true, $part);
                    break;
                case 1:
                    $this->setLights($grid, (int)$operation[1][0][0], (int)$operation[1][0][1], (int)$operation[1][1][0], (int)$operation[1][1][1], false, $part);
                    break;
                case 2:
                    $this->toggleLights($grid, (int)$operation[1][0][0], (int)$operation[1][0][1], (int)$operation[1][1][0], (int)$operation[1][1][1], $part);
            }
        }

        return $this->countLights($grid, $part);
    }

    private function initializeGrid(int $part = 1): array
    {
        $grid = [];
        for ($i = 0; $i <= 999; $i++) {
            for ($j = 0; $j <= 999; $j++) {
                if ($part === 1) {
                    $grid[$i][$j] = false;
                } else {
                    $grid[$i][$j] = 0;
                }
            }
        }

        return $grid;
    }

    private function setLight(array &$grid, int $x, int $y, bool $value = true, int $part = 1)
    {
        if ($part === 1) {
            $grid[$x][$y] = $value;
        } else {
            $newValue = $grid[$x][$y] + ($value === true ? 1 : -1);
            if ($newValue < 0) {
                $newValue = 0;
            }
            $grid[$x][$y] = $newValue;
        }
    }

    private function toggleLight(array &$grid, int $x, int $y, int $part = 1)
    {
        if ($part === 1) {
            if ($grid[$x][$y] === true) {
                $grid[$x][$y] = false;
            } else {
                $grid[$x][$y] = true;
            }
        } else {
            $grid[$x][$y] += 2;
        }
    }

    private function setLights(array &$grid, int $x1, int $y1, int $x2, int $y2, bool $value = true, int $part = 1): void
    {
        for ($i = $x1; $i <= $x2; $i++) {
            for ($j = $y1; $j <= $y2; $j++) {
                $this->setLight($grid, $i, $j, $value, $part);
            }
        }
    }

    private function toggleLights(array &$grid, int $x1, int $y1, int $x2, int $y2, int $part = 1): void
    {
        for ($i = $x1; $i <= $x2; $i++) {
            for ($j = $y1; $j <= $y2; $j++) {
                $this->toggleLight($grid, $i, $j, $part);
            }
        }
    }

    private function countLights(array $grid, int $part = 1): int
    {
        $amount = 0;
        foreach ($grid as $row) {
            foreach ($row as $cell) {
                if ($part === 1) {
                    if ($cell === true) {
                        $amount++;
                    }
                } else {
                    $amount += $cell;
                }
            }
        }


        return $amount;
    }

    private function convertInstructionToOperation(string $instruction): array
    {
        $elements = explode(" ", $instruction);
        $bounds = [explode(',', $elements[count($elements) - 3]), explode(',', $elements[count($elements) - 1])];
        $operation = null;
        if (Str::contains($instruction, 'turn on')) {
            $operation = 0;
        }
        if (Str::contains($instruction, 'turn off')) {
            $operation = 1;
        }
        if (Str::contains($instruction, 'toggle')) {
            $operation = 2;
        }

        return [
            $operation,
            $bounds
        ];
    }
}
