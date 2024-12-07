<?php

declare(strict_types=1);

namespace App\Aoc\Year2015;

use App\Services\Aoc\SolutionInterface;
use App\Utilities\Combinations;
use function array_product;
use function array_sum;
use function dd;
use function explode;
use const PHP_INT_MAX;

class Solution24 implements SolutionInterface
{
    public function p1(string $input): mixed
    {
        $weights = [];

        foreach (explode("\n", trim($input)) as $line) {
            $weights[] = (int)trim($line);
        }
        $part = 520;
        $combinations = new Combinations($weights);
        $minimum = PHP_INT_MAX;
        foreach ($combinations->getCombinations(count($weights)) as $combination) {
            dd('test');
            if (array_sum($combination) === $part){
                $minimum = min(array_product($combination), $minimum);
            }
        }

        return $minimum;
     }

    public function p2(string $input): mixed
    {
        return null;
    }
}
