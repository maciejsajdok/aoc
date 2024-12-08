<?php

declare(strict_types=1);

namespace App\Aoc\Year2016;

use App\Services\Aoc\SolutionInterface;
use function dd;
use function explode;
use function in_array;
use function preg_match;

class Solution01 implements SolutionInterface
{
    private array $directions = [
        [0,-1],[1,0],[0,1],[-1,0]
    ];
    public function p1(string $input): mixed
    {
        $cx = $cy = 0;

        $currDirection = 0;
        foreach (explode(', ', $input) as $direction){
            preg_match("/([LR])(\d+)/", $direction, $matches);
            [$dir, $steps] = [$matches[1], $matches[2]];

            $currDirection += match ($dir) {
                'R' => 1,
                'L' => -1,
                default => 0
            };
            $currDirection = $currDirection < 0 ? 3 : $currDirection % 4;

            $cx += $steps * $this->directions[$currDirection][0];
            $cy += $steps * $this->directions[$currDirection][1];
        }

        return abs($cx) + abs($cy);
    }

    public function p2(string $input): mixed
    {
        $cx = $cy = 0;

        $currDirection = 0;
        $visited = [];
        foreach (explode(', ', $input) as $direction){
            preg_match("/([LR])(\d+)/", $direction, $matches);
            [$dir, $steps] = [$matches[1], $matches[2]];

            $currDirection += match ($dir) {
                'R' => 1,
                'L' => -1,
                default => 0
            };

            $currDirection = $currDirection < 0 ? 3 : $currDirection % 4;

            for ($i = 0; $i < $steps; $i++) {
                if (in_array([$cx, $cy], $visited)){
                    break;
                } else {
                    $visited[] = [$cx, $cy];
                }
                $cx += $this->directions[$currDirection][0];
                $cy += $this->directions[$currDirection][1];
            }
        }

        return abs($cx) + abs($cy);
    }
}
