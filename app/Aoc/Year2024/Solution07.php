<?php

declare(strict_types=1);

namespace App\Aoc\Year2024;

use App\Services\Aoc\SolutionInterface;
use App\Utilities\Combinations;
use Spatie\Fork\Fork;
use function array_chunk;
use function array_merge;
use function array_sum;
use function array_unique;
use function explode;
use function head;
use function intdiv;

class Solution07 implements SolutionInterface
{
    public function p1(string $input): mixed
    {
        $lines = explode("\n", trim($input));
        $results = [];
        $elements = [];

        foreach ($lines as $line) {
            [$result, $values] = explode(':', trim($line));
            $results[] = (int)$result;
            $elements[] = explode(' ', trim($values));
        }
        $goodValues = [];
        $combinations = new Combinations(['*', '+']);

        foreach ($elements as $i => $element) {
            $result = $results[$i];
            $operatorSet = $combinations->getPermutations(count($element) - 1, true);

            foreach ($operatorSet as $set) {

                $sum = head($element);

                foreach ($set as $y => $operator) {
                    if ($operator === '+') {
                        $sum += $element[$y + 1];
                    } else {
                        $sum *= $element[$y + 1];
                    }
                }

                if ($sum === $result) {
                    $goodValues[] = $sum;
                }

            }

        }
        return array_sum(array_unique($goodValues));
    }

    public function p2(string $input): mixed
    {

        $lines = explode("\n", trim($input));
        $results = [];
        $elements = [];

        foreach ($lines as $line) {
            [$result, $values] = explode(':', trim($line));
            $results[] = (int)$result;
            $elements[] = explode(' ', trim($values));
        }
        $combinations = new Combinations(['*', '+', '||']);

        $callables = [];

        $elementsChunks = array_chunk($elements, intdiv(count($elements), 6), true);


        foreach ($elementsChunks as $chunk) {
            $callables[] = function () use ($chunk, $results, $combinations) {
                $goodValues = [];
                foreach ($chunk as $i => $element) {
                    $result = $results[$i];
                    $operatorSet = $combinations->getPermutations(count($element) - 1, true);

                    foreach ($operatorSet as $set) {
                        $sum = head($element);

                        foreach ($set as $y => $operator) {
                            if ($operator === '+') {
                                $sum += $element[$y + 1];
                            } else if ($operator === '*') {
                                $sum *= $element[$y + 1];
                            } else if ($operator === '||') {
                                $sum = (int)($sum . $element[$y + 1]);
                            }
                        }

                        if ($sum === $result) {
                            $goodValues[] = $sum;
                        }

                    }

                }

                return ($goodValues);
            };
        }

        $results = (new Fork())->run(...$callables);

        return array_sum(array_unique(array_merge(...$results)));
    }
}
