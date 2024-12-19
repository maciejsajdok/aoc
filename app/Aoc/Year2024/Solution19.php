<?php

declare(strict_types=1);

namespace App\Aoc\Year2024;

use App\Services\Aoc\SolutionInterface;
use function explode;
use function str_starts_with;
use function substr;

class Solution19 implements SolutionInterface
{
    public function p1(string $input): mixed
    {
        [$possibleDesignsInput, $desiredDesignsInput] = explode("\n\n", $input);
        $patterns = explode(", ", $possibleDesignsInput);
        $desiredDesigns = explode("\n", $desiredDesignsInput);

        return $this->countPossibleDesigns($patterns, $desiredDesigns);
    }

    public function p2(string $input): mixed
    {
        [$possibleDesignsInput, $desiredDesignsInput] = explode("\n\n", $input);
        $patterns = explode(", ", $possibleDesignsInput);
        $desiredDesigns = explode("\n", $desiredDesignsInput);

        return $this->totalWaysToMakeDesigns($patterns, $desiredDesigns);
    }
    private function canMakeDesign($design, $patterns): bool
    {
        $queue = [$design];
        $visited = [];

        while (!empty($queue)) {
            $current = array_shift($queue);

            if (isset($visited[$current])) {
                continue;
            }
            $visited[$current] = true;

            foreach ($patterns as $pattern) {
                if (str_starts_with($current, $pattern)) {
                    $remaining = substr($current, strlen($pattern));
                    if ($remaining === "") {
                        return true;
                    }
                    $queue[] = $remaining;
                }
            }
        }

        return false;
    }

    private function countPossibleDesigns($patterns, $designs): int
    {
        $count = 0;
        foreach ($designs as $design) {
            if ($this->canMakeDesign($design, $patterns)) {
                $count++;
            }
        }

        return $count;
    }

    private function countWaysToMakeDesign($design, $patterns) {
        $dp = [];
        $dp[0] = 1;

        for ($i = 1; $i <= strlen($design); $i++) {
            $dp[$i] = 0;
            foreach ($patterns as $pattern) {
                $patternLength = strlen($pattern);
                if ($i >= $patternLength && substr($design, $i - $patternLength, $patternLength) === $pattern) {
                    $dp[$i] += $dp[$i - $patternLength];
                }
            }
        }

        return $dp[strlen($design)];
    }

    private function totalWaysToMakeDesigns($patterns, $designs) {
        $totalWays = 0;
        foreach ($designs as $design) {
            $totalWays += $this->countWaysToMakeDesign($design, $patterns);
        }

        return $totalWays;
    }

}
