<?php

declare(strict_types=1);

namespace App\Aoc\Year2015;

use App\Services\Aoc\SolutionInterface;
use function array_filter;
use function array_map;
use function array_reverse;
use function array_unique;
use function count;
use function explode;
use function in_array;
use function preg_match_all;
use function str_replace;
use function strlen;
use function strpos;
use function substr_replace;
use function trim;
use function usort;

class Solution19 implements SolutionInterface
{
    public function p1(string $input): mixed
    {
        $lines = explode("\n", trim($input));

        $rules = [];
        for ($i = 0; $i < count($lines)-2; $i++) {
            preg_match_all('/(.+) => (.+)/', $lines[$i], $matches);
            $rules[$i] = [$matches[1][0], $matches[2][0]];
        }

        $string = $lines[count($lines) - 1];

        $combinations = [];

        foreach ($rules as $rule) {
            $indexes = [];
            $lastPos = 0;
            while (($lastPos = strpos($string, $rule[0], $lastPos)) !== false) {
                $indexes[] = $lastPos;
                $lastPos = $lastPos + strlen($rule[0]);
            }

            foreach ($indexes as $index) {
                $combinations[] = substr_replace($string, $rule[1], $index, strlen($rule[0]));
            }
        }


        return count(array_unique($combinations));
    }

    public function p2(string $input): mixed
    {
        $lines = explode("\n", trim($input));

        $rules = [];
        for ($i = 0; $i < count($lines)-2; $i++) {
            preg_match_all('/(.+) => (.+)/', $lines[$i], $matches);
            $rules[$i] = [$matches[1][0], $matches[2][0]];
        }

        $sentence = $lines[count($lines) - 1];
        preg_match_all('/([A-Z][a-z]*)/', $sentence, $sentenceWords);

        return $this->simplifiedCyk($rules, $sentence);
    }

    // It seems the molecule from input has only one path to reach e, so I won't be trying all possible patterns
    private function simplifiedCyk(array $dictionary, string $sentence): int
    {
        $rules = [];
        $reverseRules = [];
        foreach ($dictionary as $rule) {
            $rules[$rule[0]][] = $rule[1];
            $reverseRules[$rule[1]][] = $rule[0];
        }

        $sortedDictionary = array_filter(array_map(function ($el){
            if ($el[0] !== 'e') return $el;
            else return null;
        },$dictionary));
        usort($sortedDictionary, function ($left, $right)
        {
            return strlen($left[1]) <=> strlen($right[1]);
        });

        $tmpSentence = $sentence;

        $sortedDictionary = array_reverse($sortedDictionary);
        $destinations = array_map(fn ($element) => $element[0], $sortedDictionary);
        $sources = array_map(fn ($element) => $element[1], $sortedDictionary);

        $steps = 0;
        while (!$this->canGoBackToE($rules, $tmpSentence))
        {
            $tmpSentence = str_replace($sources, $destinations, $tmpSentence, $count);
            $steps += $count;
        }
        $steps++;

        return $steps;

    }

    private function canGoBackToE(array $dictionary, string $sentence):bool
    {
        return in_array($sentence, $dictionary['e']);
    }
}
