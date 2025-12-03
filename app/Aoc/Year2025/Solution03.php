<?php

declare(strict_types=1);

namespace App\Aoc\Year2025;

use App\Services\Aoc\SolutionInterface;
use App\Utilities\Combinations;
use App\Utilities\Memoize;
use function array_slice;
use function array_splice;
use function implode;
use function range;
use function str_split;

class Solution03 implements SolutionInterface
{
    public function p1(string $input): mixed
    {
        $banks = explode("\n", trim($input));
        $result = 0;
        foreach ($banks as $bank) {
            $batteries = str_split($bank);
            $max = 0;
            for($i = 0; $i < count($batteries)-1; $i++) {
                for ($j = $i + 1 ; $j < count($batteries); $j++) {
                    $currentCombination = $batteries[$i].$batteries[$j];
                    if((int) $currentCombination > $max) {
                        $max = (int) $currentCombination;
                    }
                }
            }
            $result += (int) $max;
        }
        return $result;
    }

    public function p2(string $input): mixed
    {
        $banks = explode("\n", trim($input));
        $result = 0;
        foreach ($banks as $bank) {
            $batteries = str_split($bank);
            $index = 0;
            $highestValue = '';
            foreach (range(11, 0, -1) as $step){
                [$highest, $index] = $this->findMaximumValueAndIndex($batteries, $index, count($batteries) - $step);
                $highestValue = $highestValue.$highest;
                $index++;
            }

            $result += (int) $highestValue;
        }
        return $result;
    }

    private function findMaximumValueAndIndex(array $batteries, int $start, int $end): array
    {
        $index = null;
        $max = 0;

        for ($i = $start; $i < $end; $i++) {
            $battery = (int) $batteries[$i];

            if ($index === null) {
                $max = (int) $battery;
                $index = $i;
            } elseif ($battery > $max) {
                $max = $battery;
                $index = $i;
            }

            if ($max === 9){
                break;
            }
        }

        return [$max, $index];
    }
}
