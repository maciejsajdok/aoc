<?php

declare(strict_types=1);

namespace App\Aoc\Year2015;

use App\Services\Aoc\SolutionInterface;
use function preg_match_all;
use function strlen;
use function trim;

class Solution10 implements SolutionInterface
{
    public function p1(string $input): mixed
    {
        $newString = $this->parseString($input);
        for($i = 1; $i < 40; $i++) {
            $newString = $this->parseString($newString);
        }
        return strlen($newString);
    }

    public function p2(string $input): mixed
    {
        $newString = $this->parseString($input);
        for($i = 1; $i < 50; $i++) {
            $newString = $this->parseString($newString);
        }
        return strlen($newString);
    }

    private function parseString(string $input): string
    {
        if (preg_match_all('/(.)\1*/', trim($input), $matches)){
            $newString = '';
            foreach($matches[0] as $match){
                $newString .= strlen($match).$match[0];
            }

            return $newString;
        }

        return '';
    }
}
