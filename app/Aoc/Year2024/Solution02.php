<?php

declare(strict_types=1);

namespace App\Aoc\Year2024;

use App\Services\Aoc\SolutionInterface;
use function abs;
use function array_splice;
use function explode;
use function in_array;
use function trim;

class Solution02 implements SolutionInterface
{
    public function p1(string $input): mixed
    {
        $lines = explode("\n", trim($input));
        $safeReports = 0;
        foreach ($lines as $line) {
            $numbers = explode(" ", trim($line));
            if ($this->isSafe($numbers)) {
                $safeReports +=1;
            }
        }
        return $safeReports;
    }

    public function p2(string $input): mixed
    {
        $lines = explode("\n", trim($input));
        $safeReports = 0;
        foreach ($lines as $line) {
            $numbers = explode(" ", trim($line));
            $isSafe = $this->isSafe($numbers);

            if ($isSafe == false){
                for ($i = 0; $i < count($numbers); $i++) {
                       $tmpNumbers = $numbers;
                       array_splice($tmpNumbers,$i, 1);
                       $isSafeAfterRemoval = $this->isSafe($tmpNumbers);
                       if ($isSafeAfterRemoval == true){
                           $isSafe = true;
                           break;
                       }
                }
            }
            if ($isSafe) {
                $safeReports +=1;
            }
        }

        return $safeReports;
    }

    private function isSafe(array $numbers): bool
    {
        $isIncreasing = null;
        $isSafe = true;
        for ($i = 0; $i < count($numbers) - 1; $i++) {
            if ($isIncreasing === null) {
                $isIncreasing = ((int)$numbers[$i] - (int)$numbers[$i + 1]) < 0;
            } else {
                $newIsIncreasing = ((int)$numbers[$i] - (int)$numbers[$i + 1]) < 0;
                if ($newIsIncreasing !== $isIncreasing) {
                    $isSafe = false;
                    break;
                }
            }
            if (!in_array(abs((int)$numbers[$i] - (int)$numbers[$i + 1]), [1, 2, 3])) {
                $isSafe = false;
            }
        }

        return $isSafe;
    }

}
