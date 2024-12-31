<?php

declare(strict_types=1);

namespace App\Aoc\Year2016;

use App\Services\Aoc\SolutionInterface;
use function array_map;
use function explode;
use function str_split;
use function trim;

class Solution02 implements SolutionInterface
{
    private array $keyboard = [
        [1, 2, 3],
        [4, 5, 6],
        [7, 8, 9],
    ];

    private array $strangeKeyboard = [
      [null, null, 1, null, null],
      [null, 2, 3, 4, null],
      [5, 6, 7, 8, 9],
      [null , 'A', 'B', 'C', null],
      [null, null, 'D', null, null]
    ];

    public function p1(string $input): mixed
    {
        $directions = array_map(function (string $str){
            return str_split($str);
        }, explode("\n", trim($input)));

        $result = '';
        $current = [1,1];
        foreach ($directions as $directionSet){
            foreach ($directionSet as $direction){
                $delta = match($direction){
                    'U' => [-1,0],
                    'D' => [1,0],
                    'L' => [0,-1],
                    'R' => [0, 1],
                };

                $newCurrent = [$current[0] + $delta[0], $current[1] + $delta[1]];

                if (
                    $newCurrent[0] < 0 || $newCurrent[1] < 0 || $newCurrent[0] > 2 || $newCurrent[1] > 2
                ){
                    continue;
                }
                $current = $newCurrent;
            }
            $result .= $this->keyboard[$current[0]][$current[1]];
        }

        return $result;
    }

    public function p2(string $input): mixed
    {
        $directions = array_map(function (string $str){
            return str_split($str);
        }, explode("\n", trim($input)));

        $result = '';
        $current = [2,0];
        foreach ($directions as $directionSet){
            foreach ($directionSet as $direction){
                $delta = match($direction){
                    'U' => [-1,0],
                    'D' => [1,0],
                    'L' => [0,-1],
                    'R' => [0, 1],
                };

                $newCurrent = [$current[0] + $delta[0], $current[1] + $delta[1]];

                if (
                    $newCurrent[0] < 0 || $newCurrent[1] < 0 || $newCurrent[0] > 4 || $newCurrent[1] > 4 || $this->strangeKeyboard[$newCurrent[0]][$newCurrent[1]] === null
                ){
                    continue;
                }
                $current = $newCurrent;
            }
            $result .= $this->strangeKeyboard[$current[0]][$current[1]];
        }

        return $result;
    }
}
