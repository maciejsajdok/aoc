<?php

declare(strict_types=1);

namespace App\Aoc\Year2015;

use App\Services\Aoc\SolutionInterface;
use function array_keys;
use function array_push;
use function array_splice;
use function count;
use function explode;
use function max;
use function substr;
use function trim;

class Solution13 implements SolutionInterface
{
    public function p1(string $input): mixed
    {
        $lines = explode("\n", trim($input));

        $moods = [];
        foreach ($lines as $line) {
            $elements = explode(" ", $line);
            $moods[$elements[0]][substr($elements[count($elements)-1],0,-1)] = $elements[2] === 'gain' ? $elements[3] : $elements[3]*-1;
        }

        $names = array_keys($moods);

        $permutations = $this->permutate($names);

        $sums = [];

        foreach ($permutations as $permutation) {
            $sum = 0;
            foreach ($permutation as $index => $value) {
                $currentPerson = $value;
                $nextPerson = $permutation[($index+1) % (count($permutation))];
                $sum += $moods[$currentPerson][$nextPerson] + $moods[$nextPerson][$currentPerson];
            }
            $sums[] = $sum;
        }

        return max($sums);
    }

    public function p2(string $input): mixed
    {
        $lines = explode("\n", trim($input));

        $moods = [];
        foreach ($lines as $line) {
            $elements = explode(" ", $line);
            $moods[$elements[0]][substr($elements[count($elements)-1],0,-1)] = $elements[2] === 'gain' ? $elements[3] : $elements[3]*-1;
        }

        //Here we insert neutral person

        foreach ($moods as $name => $mood) {
            $moods[$name]['Me'] = 0;
            $moods['Me'][$name] = 0;
        }

        $names = array_keys($moods);

        $permutations = $this->permutate($names);

        $sums = [];

        foreach ($permutations as $permutation) {
            $sum = 0;
            foreach ($permutation as $index => $value) {
                $currentPerson = $value;
                $nextPerson = $permutation[($index+1) % (count($permutation))];
                $sum += $moods[$currentPerson][$nextPerson] + $moods[$nextPerson][$currentPerson];
            }
            $sums[] = $sum;
        }

        return max($sums);
    }

    private function permutate(array $items, array $permutations = []): array
    {
        $result = [];

        if (empty($items)){
            $result[] = $permutations;
        } else {
            for($i = 0; $i < count($items); $i++){
                $newItems = $items;
                $newPermutations = $permutations;
                array_push($newPermutations, $newItems[$i]);
                array_splice($newItems, $i, 1);
                $result = array_merge($result, $this->permutate($newItems, $newPermutations));
            }
        }

        return $result;
    }

}
