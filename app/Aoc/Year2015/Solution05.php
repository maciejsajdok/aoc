<?php

declare(strict_types=1);

namespace App\Aoc\Year2015;

use App\Services\Aoc\SolutionInterface;
use Illuminate\Support\Str;
use function count;
use function explode;
use function preg_match;
use function preg_match_all;

class Solution05 implements SolutionInterface
{
    public function p1(string $input): mixed
    {
        $strings = explode("\n", $input);
        $okAmount = 0;
        foreach ($strings as $string) {
            $isOk = $this->hasThreeVowels($string)
            && $this->containsDoubleLetter($string)
            && $this->doesNotContainStrings($string);
            if ($isOk) {
                $okAmount++;
            }
        }

        return $okAmount;
    }

    public function p2(string $input): mixed
    {
        $strings = explode("\n", $input);
        $okAmount = 0;
        foreach ($strings as $string) {
            $isOk = $this->containsLetterPairThatOccursTwice($string)
            && $this->containsOneLetterRepeatWithLetterInside($string);
            if ($isOk) {
                $okAmount++;
            }
        }

        return $okAmount;
    }

    private function hasThreeVowels(string $input): bool
    {
        if (preg_match_all('/[aeiou]/', $input, $matches)) {
            return count($matches[0]) >= 3;
        }
        return false;
    }

    private function containsDoubleLetter(string $input): bool
    {
        if (preg_match_all('/(.)\1+/', $input, $matches))
        {
            if (count($matches) > 1){
                return true;
            }
        }

        return false;
    }

    private function doesNotContainStrings(string $input): bool
    {
        $needle = ['ab','cd','pq','xy'];

        return !Str::contains($input, $needle);
    }

    private function containsLetterPairThatOccursTwice(string $input): bool
    {
        return preg_match('/(\w\w).*\1/', $input) === 1;
    }

    private function containsOneLetterRepeatWithLetterInside(string $input): bool
    {
        return preg_match('/(\w).\1/', $input) === 1;
    }
}
