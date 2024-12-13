<?php

declare(strict_types=1);

namespace App\Aoc\Year2024;

use App\Services\Aoc\SolutionInterface;
use function explode;
use function preg_match;
use const PHP_INT_MAX;

class Solution13 implements SolutionInterface
{
    public function p1(string $input): mixed
    {
        $machines = [];
        $segments = explode("\n\n", $input);

        foreach ($segments as $i => $segment) {
            foreach (explode("\n", $segment) as $line) {
                if (preg_match('/Button A: X\+([0-9]+), Y\+([0-9]+)/', trim($line), $matchesA)) {
                    $machines[$i]['aX'] = (int)$matchesA[1];
                    $machines[$i]['aY'] = (int)$matchesA[2];
                } elseif (preg_match('/Button B: X\+([0-9]+), Y\+([0-9]+)/', trim($line), $matchesB)) {
                    $machines[$i]['bX'] = (int)$matchesB[1];
                    $machines[$i]['bY'] = (int)$matchesB[2];
                } elseif (preg_match('/Prize: X=([0-9]+), Y=([0-9]+)/', trim($line), $matchesP)) {
                    $machines[$i]['prizeX'] = (int)$matchesP[1];
                    $machines[$i]['prizeY'] = (int)$matchesP[2];
                }
            }
        }

        $totalTokens = 0;

        foreach ($machines as $machine) {
            $tokens = $this->calculateMinimumTokens($machine);
            if ($tokens !== null) {
                $totalTokens += $tokens;
            }
        }

        return $totalTokens;
    }

    public function p2(string $input): mixed
    {
        $machines = [];
        $segments = explode("\n\n", $input);

        foreach ($segments as $i => $segment) {
            foreach (explode("\n", $segment) as $line) {
                if (preg_match('/Button A: X\+([0-9]+), Y\+([0-9]+)/', trim($line), $matchesA)) {
                    $machines[$i]['aX'] = (int)$matchesA[1];
                    $machines[$i]['aY'] = (int)$matchesA[2];
                } elseif (preg_match('/Button B: X\+([0-9]+), Y\+([0-9]+)/', trim($line), $matchesB)) {
                    $machines[$i]['bX'] = (int)$matchesB[1];
                    $machines[$i]['bY'] = (int)$matchesB[2];
                } elseif (preg_match('/Prize: X=([0-9]+), Y=([0-9]+)/', trim($line), $matchesP)) {
                    $machines[$i]['prizeX'] = (int)$matchesP[1];
                    $machines[$i]['prizeY'] = (int)$matchesP[2];
                }
            }
        }

        $totalTokens = 0;

        foreach ($machines as $machine) {
            $tokens = $this->calculateMinimumTokensPart2($machine);
            $totalTokens += $tokens;
        }

        return number_format($totalTokens);
    }


    private function calculateMinimumTokens($machine , $p = 1): ?int
    {
        $aX = $machine['aX'];
        $aY = $machine['aY'];
        $bX = $machine['bX'];
        $bY = $machine['bY'];
        $prizeX = $machine['prizeX'];
        $prizeY = $machine['prizeY'];

        $minTokens = PHP_INT_MAX;
        $foundSolution = false;

        for ($aPresses = 0; $aPresses <= 100; $aPresses++) {
            for ($bPresses = 0; $bPresses <= 100; $bPresses++) {
                $x = $aPresses * $aX + $bPresses * $bX;
                $y = $aPresses * $aY + $bPresses * $bY;

                if ($x === $prizeX && $y === $prizeY) {
                    $foundSolution = true;
                    $tokens = $aPresses * 3 + $bPresses;
                    $minTokens = min($minTokens, $tokens);
                }
            }
        }

        return $foundSolution ? $minTokens : null;
    }
    private function calculateMinimumTokensPart2($machine): int
    {
        $ax = $machine['aX'];
        $ay = $machine['aY'];
        $bx = $machine['bX'];
        $by = $machine['bY'];

        $px = $machine['prizeX'] + 10000000000000;
        $py = $machine['prizeY'] + 10000000000000;

        $determinant = $ax * $by - $ay * $bx;

        if ($determinant === 0){
            return 0;
        }

        $a = ($px * $by - $py * $bx)/ $determinant;
        $b = ($py * $ax - $px * $ay)/ $determinant;

        if ($a === (int) $a && $b === (int) $b) {
            return (int)($a * 3 + $b);
        }

        return 0;
    }
}
