<?php

declare(strict_types=1);

namespace App\Aoc\Year2015;

use App\Services\Aoc\SolutionInterface;
use function str_split;

class Solution03 implements SolutionInterface
{
    private array $map = [];
    public function p1(string $input): mixed
    {
        $directions = str_split($input);
        $this->map = [];
        $housesCount = 0;

        $santaCoordinates = [0,0];

        $this->markVisited($santaCoordinates[0],$santaCoordinates[1]);

        $housesCount += 1;

        foreach ($directions as $direction) {
            switch ($direction){
                case '>': $santaCoordinates[0]++; break;
                case '<': $santaCoordinates[0]--; break;
                case '^': $santaCoordinates[1]++; break;
                case 'v': $santaCoordinates[1]--; break;
            }

            if (!$this->wasVisited($santaCoordinates[0], $santaCoordinates[1])) {
                $housesCount++;
            }
            $this->markVisited($santaCoordinates[0], $santaCoordinates[1]);
        }

        return $housesCount;
    }

    public function p2(string $input): mixed
    {$directions = str_split($input);
        $this->map = [];
        $housesCount = 0;

        $santaCoordinates = [0,0];
        $roboSantaCoordinates = [0,0];

        $this->markVisited($santaCoordinates[0],$santaCoordinates[1]);
        $this->markVisited($roboSantaCoordinates[0],$roboSantaCoordinates[1]);
        $housesCount += 1;

        $turn = true; //true is for santa, false for robot

        foreach ($directions as $direction) {
            switch ($direction){
                case '>': $turn ? $santaCoordinates[0]++ : $roboSantaCoordinates[0]++ ; break;
                case '<': $turn ? $santaCoordinates[0]-- : $roboSantaCoordinates[0]-- ; break;
                case '^': $turn ? $santaCoordinates[1]++ : $roboSantaCoordinates[1]++ ; break;
                case 'v': $turn ? $santaCoordinates[1]-- : $roboSantaCoordinates[1]-- ; break;
            }

            $usedCoordinates = $turn ? $santaCoordinates : $roboSantaCoordinates;
            if (!$this->wasVisited($usedCoordinates[0], $usedCoordinates[1])) {
                $housesCount++;
            }
            $this->markVisited($usedCoordinates[0], $usedCoordinates[1]);
            $turn = !$turn;
        }

        return $housesCount;
    }

    private function markVisited(int $x, int $y, $flag = 's'): void
    {
        $this->map[$x.'-'.$y] = $flag;
    }

    private function wasVisited(int $x, int $y, $flag = 's'): bool
    {
        return isset($this->map[$x.'-'.$y]) && $this->map[$x.'-'.$y] === $flag;
    }
}
