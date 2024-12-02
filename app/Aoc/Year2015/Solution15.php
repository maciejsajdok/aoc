<?php

declare(strict_types=1);

namespace App\Aoc\Year2015;

use App\Services\Aoc\SolutionInterface;
use function array_product;
use function count;
use function explode;
use function in_array;
use function max;
use function str_replace;
use function trim;

class Solution15 implements SolutionInterface
{
    public function p1(string $input): mixed
    {
        $lines = explode("\n", trim($input));

        $ingredients = [];

        foreach ($lines as $line) {
            $els = explode(" ", str_replace([':', ','],'',$line));
            $ingredients[] = [
                (int) $els[2],
                (int) $els[4],
                (int) $els[6],
                (int) $els[8],
            ];
        }


        $ratios = $this->generateRatios(count($ingredients));
        $currentMaximum = 0;

        foreach ($ratios as $ratio){
            $attributes = [];
            foreach ($ingredients as $i => $ingredient) {
                foreach ($ingredient as $attribute){
                    $attributes[$i][] = $attribute * $ratio[$i];
                }
            }

            $scores = [];
            for ($i = 0; $i < count($attributes[0]); ++$i) {
                $attributeScore = 0;
                foreach ($attributes as $attribute){
                    $attributeScore += $attribute[$i];
                }
                if ($attributeScore < 0) $attributeScore = 0;
                $scores[] = $attributeScore;
            }

            $currentMaximum = max($currentMaximum, array_product($scores));
        }
        return $currentMaximum;
    }

    public function p2(string $input): mixed
    {
        $lines = explode("\n", trim($input));

        $ingredients = [];

        foreach ($lines as $line) {
            $els = explode(" ", str_replace([':', ','],'',$line));
            $ingredients[] = [
                (int) $els[2],
                (int) $els[4],
                (int) $els[6],
                (int) $els[8],
                (int) $els[10],
            ];
        }


        $ratios = $this->generateRatios(count($ingredients));

        $winningScore = 0;
        foreach ($ratios as $ratio){
            if (in_array(0, $ratio)) continue;
            $attributes = [];
            foreach ($ingredients as $i => $ingredient) {
                foreach ($ingredient as $attribute){
                    $attributes[$i][] = $attribute * $ratio[$i];
                }
            }

            $scores = [];
            for ($i = 0; $i < count($attributes[0])-1; ++$i) {
                $attributeScore = 0;
                foreach ($attributes as $attribute){
                    $attributeScore += $attribute[$i];
                }
                if ($attributeScore < 0) $attributeScore = 0;
                $scores[] = $attributeScore;
            }
            $calories = 0;

            foreach ($attributes as $attribute){
                $calories += $attribute[count($attribute)-1];
            }

            if ($calories !== 500){
                continue;
            }

            $score = array_product($scores);
            if ($score === 0){
                continue;
            }

            $winningScore = max($winningScore, $score);
        }

        return $winningScore;
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
