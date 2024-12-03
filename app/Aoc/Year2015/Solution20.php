<?php

declare(strict_types=1);

namespace App\Aoc\Year2015;

use App\Services\Aoc\SolutionInterface;
use function sqrt;
use function trim;

class Solution20 implements SolutionInterface
{
    public function p1(string $input): mixed
    {
        $target = (int) trim($input);
        $houses = [];
        $max = 1000000;

        for ($elf = 1; $elf <= $max; $elf++){
            for($step = $elf; $step <= $max; $step += $elf) {
                if (!isset($houses[$step])) {
                    $houses[$step] = 0;
                }
                $houses[$step] += $elf * 10;
            }
        }

        foreach ($houses as $houseNumber => $house){
            if ($house > $target){
                return $houseNumber;
            }
        }
    }

    public function p2(string $input): mixed
    {
        $target = (int) trim($input);
        $houses = [];
        $max = 1000000;

        for ($elf = 1; $elf <= $max; $elf++){
            for($step = $elf; $step <= $elf*50 && $step <= $max; $step += $elf) {
                if (!isset($houses[$step])) {
                    $houses[$step] = 0;
                }
                $houses[$step] += $elf * 11;
            }
        }

        foreach ($houses as $houseNumber => $house){
            if ($house > $target){
                return $houseNumber;
            }
        }
    }

    private function getAmountOfPresents(int $number): int
    {
        $amountOfPresents = 0;

        $i = 2;
        while($i <= sqrt($number)) {
            if ($number % $i === 0) {
                $amountOfPresents += $i * 10;
                if ($i !== ($number / $i)){
                    $amountOfPresents += 10 * ($number / $i);
                }
            }
            $i++;
        }

        return $amountOfPresents + 10 + ($number * 10);
    }
}
