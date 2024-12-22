<?php

declare(strict_types=1);

namespace App\Aoc\Year2024;

use App\Services\Aoc\SolutionInterface;
use function array_shift;
use function count;
use function implode;
use function max;

class Solution22 implements SolutionInterface
{
    public function p1(string $input): mixed
    {
        $iterations = 2000;
        $sum = 0;

        $lines = explode("\n", trim($input));
        foreach ($lines as $line) {
            $secret = $this->calculateNextSecret((int) $line, $iterations);
            $sum += $secret;
        }
        return $sum;
    }

    public function p2(string $input): mixed
    {
        $iterations = 2000;
        $sum = 0;

        $priceChanges = [];

        $lines = explode("\n", trim($input));
        $sequences = [];
        foreach ($lines as $i => $line) {
            $subSequences = [];
            $lastFour = [];
            $prevSecret = (int) $line;
            $prevPrice = $prevSecret % 10;
            for ($j = 0; $j < $iterations; $j++) {
                $newSecret = $this->calculateNextSecret($prevSecret, 1);
                $newPrice = $newSecret % 10;
                $diff = $newPrice - $prevPrice;

                if (count($lastFour) !== 4){
                    $lastFour[] = $diff;
                } else {
                    array_shift($lastFour);
                    $lastFour[] = $diff;
                    $key = implode(',', $lastFour);
                    if (!isset($subSequences[$key])) {
                        $subSequences[$key] = $newPrice;
                    }
                }

                $prevSecret = $newSecret;
                $prevPrice = $newPrice;
            }

            foreach ($subSequences as $subSequenceKey => $subSequencePrice) {
                if (isset($sequences[$subSequenceKey])) {
                    $sequences[$subSequenceKey] += $subSequencePrice;
                } else {
                    $sequences[$subSequenceKey] = $subSequencePrice;
                }
            }
        }


        return max($sequences);
    }

    private function calculateNextSecret(int $secret, int $iterations): int
    {
        $result = $secret;
        for ($j = 0; $j < $iterations; $j++) {
            $result ^= ($result * 64);
            $result %= 16777216;

            $result ^= (int)($result / 32);
            $result %= 16777216;

            $result ^= ($result * 2048);
            $result %= 16777216;
        }

        return $result;
    }
}
