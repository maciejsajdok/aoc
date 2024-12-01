<?php

declare(strict_types=1);

namespace App\Aoc\Year2015;

use App\Services\Aoc\SolutionInterface;
use Generator;
use function array_merge;
use function count;
use function dd;
use function dump;
use function str_replace;
use function substr;
use function trim;

class Solution15 implements SolutionInterface
{
    public function p1(string $input): mixed
    {

        for ($i = 0; $i < 3; ++$i) {

        }
        $lines = explode("\n", trim($input));

        $ingredients = [];

        foreach ($lines as $line) {
            $els = explode(" ", str_replace([':', ','],'',$line));
            $ingredients[$els[0]] = [
                (int) $els[2],
                (int) $els[4],
                (int) $els[6],
                (int) $els[8],
                (int) $els[10],
            ];
        }


        $ratios = $this->generateRatios(count($ingredients));
        $currentMaximum = 0;
        foreach ($ratios as $ratio){
            $sum = 0;
            foreach ($ratio as $i => $spoon){
                $attributeSum = 0;
                foreach ($ingredients as $ingredient){
                    $attributeSum += $ingredient[$i]*$spoon;
                    dump([$attributeSum,$ingredient[$i],$spoon]);
                }
                $sum += $attributeSum;
            }
            dd($sum);
        }
        return null;
    }

    public function p2(string $input): mixed
    {
        return null;
    }

    public function generateRatios(int $amount, int $total = 100, array $current = []): array
    {
        static $results = [];

        if ($amount === 1){
            $current[] = $total;
            $results[] = $current;
            return [];
        }
        for ($i = 0; $i <= $total; $i++) {
            $newCurrent = $current;
            $newCurrent[] = $i;
            $this->generateRatios($amount - 1, $total -$i, $newCurrent);
        }

        return $results;
    }
}
