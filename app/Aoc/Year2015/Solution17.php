<?php

declare(strict_types=1);

namespace App\Aoc\Year2015;

use App\Services\Aoc\SolutionInterface;
use Macocci7\PhpCombination\Combination;
use function array_count_values;
use function array_sum;
use function explode;
use function min;
use function trim;

class Solution17 implements SolutionInterface
{
    public function p1(string $input): mixed
    {
        $containers = explode("\n", trim($input));
        $combinations = new Combination(); //cant be bothered with figuring algo for combinations, smarter people already did it

        $score = 0;
        foreach ($combinations->all($containers) as $combination){
            if (array_sum($combination) === 150) $score ++;
        }
        return $score;
    }

    public function p2(string $input): mixed
    {
        $containers = explode("\n", trim($input));
        $combinations = new Combination();

        $combinationsAmount = [];
        foreach ($combinations->all($containers) as $combination){
            if (array_sum($combination) === 150){
                $combinationsAmount[] = count($combination);
            };
        }

        $minimum = min($combinationsAmount);

        return array_count_values($combinationsAmount)[$minimum];
    }

}
