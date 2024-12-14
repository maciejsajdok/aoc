<?php

declare(strict_types=1);

namespace App\Aoc\Year2024;

use App\Services\Aoc\SolutionInterface;
use function array_map;
use function array_product;
use function array_unique;
use function count;
use function floor;
use function preg_match_all;

class Solution14 implements SolutionInterface
{
    public function p1(string $input): mixed
    {
        $lines = explode("\n", $input);
        $robots = [];
        $seconds = 100;
        $width = 101;
        $height = 103;
        foreach ($lines as $i => $line) {
            preg_match_all("/-?\d+/", $line, $matches);
            $robots[] = [[(int) $matches[0][0], (int) $matches[0][1]],[(int) $matches[0][2], (int) $matches[0][3]]];
        }

        $results = [];
        foreach ($robots as $robot) {
            $x = ($robot[0][0] + ($robot[1][0] * $seconds)) % $width;
            $y = ($robot[0][1] + ($robot[1][1] * $seconds)) % $height;
            if ($x < 0){
                $x += $width;
            }

            if ($y < 0){
                $y += $height;
            }

            $results[] = [$x, $y];
        }

        $quadrants = [0,0,0,0];
        $widthHalf = floor($width / 2);
        $heightHalf = floor($height / 2);
        foreach ($results as $result) {
            [$x, $y] = $result;
            if ($x == $widthHalf || $y == $heightHalf) {
                continue;
            }

            if ($x < $widthHalf && $y < $heightHalf){
                $quadrants[0] ++;
            }
            else if ($x >= $widthHalf && $y < $heightHalf){
                $quadrants[1] ++;
            }
            else if ($x < $widthHalf && $y >= $heightHalf){
                $quadrants[2] ++;
            }
            else {
                $quadrants[3] ++;
            }
        }

        return array_product($quadrants);
    }

    public function p2(string $input): mixed
    {
        $lines = explode("\n", $input);
        $robots = [];
        $width = 101;
        $height = 103;
        foreach ($lines as $i => $line) {
            preg_match_all("/-?\d+/", $line, $matches);
            $robots[] = [[(int) $matches[0][0], (int) $matches[0][1]],[(int) $matches[0][2], (int) $matches[0][3]]];
        }

        $passed = 1;
        while($passed ++) {
            foreach ($robots as $i => &$robot) {
                $x = ($robot[0][0] + $robot[1][0]) % $width;
                $y = ($robot[0][1] + $robot[1][1]) % $height;
                if ($x < 0) {
                    $x += $width;
                }

                if ($y < 0) {
                    $y += $height;
                }

                $robot[0][0] = $x;
                $robot[0][1] = $y;
            }

            $resultPlaces = array_map(function (array $r){
                return $r[0][0].','.$r[0][1];
            }, $robots);
            $unique = array_unique($resultPlaces);
            //We have no idea what shape it should have so we will assume that it will form once all robots are not
            // on top of each other in all unique places, the assumption did work
            if (count($unique) === count($resultPlaces)) {
                return $passed - 1;
            }
        }


        return $passed;
    }
}
