<?php

declare(strict_types=1);

namespace App\Aoc\Year2015;

use App\Services\Aoc\SolutionInterface;
use Illuminate\Support\Str;
use function ord;
use function preg_match_all;
use function strlen;
use function trim;

class Solution11 implements SolutionInterface
{
    public function p1(string $input): mixed
    {
        $line = trim($input);
        $isValid = $this->isValid($line);
        while (!$isValid){
            $line ++;
            $isValid = $this->isValid($line);
        }
        return $line;
    }

    public function p2(string $input): mixed
    {
        $line = trim($input);
        $isValid = $this->isValid($line);
        while (!$isValid){
            $line ++;
            $isValid = $this->isValid($line);
        }
        $isValid = false;
        while (!$isValid){
            $line ++;
            $isValid = $this->isValid($line);
        }
        return $line;
    }

    private function isValid(string $input): bool
    {
        return $this->containsSequenceOfLetters($input)
            && $this->containsAtLeastDoublePairOfLetters($input)
            && $this->containsAtLeastDoublePairOfLetters($input);
    }
    private function doesNotContainBannedLetters(string $input): bool
    {
        return !Str::contains($input, ['i', 'o', 'l']);
    }

    private function containsAtLeastDoublePairOfLetters(string $input): bool
    {
        if (preg_match_all('/(\w)\1+/', $input, $matches, PREG_SET_ORDER)) {
            $count = 0;
            foreach ($matches as $match) {
                if (strlen($match[0]) === 2) {
                    $count++;
                }
            }
            return $count >= 2;
        }
        return false;
    }

    private function containsSequenceOfLetters(string $input): bool
    {
        $letters = str_split($input);
        for ($i = 0; $i < count($letters) - 2; $i++) {
            [$first, $second, $third] = [
                ord($letters[$i + 2]),
                ord($letters[$i + 1]) + 1,
                ord($letters[$i]) + 2
            ];
            if (($first === $second) && ($second === $third)) {
                return true;
            }
        }
        return false;
    }
}
