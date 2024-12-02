<?php

declare(strict_types=1);

namespace App\Aoc\Year2015;

use App\Services\Aoc\SolutionInterface;
use function explode;
use function preg_match;
use function trim;

class Solution16 implements SolutionInterface
{

    private array $clues = [
        'children' => 3,
        'cats' => 7,
        'samoyeds' => 2,
        'pomeranians' => 3,
        'akitas' => 0,
        'vizslas' => 0,
        'goldfish' => 5,
        'trees' => 3,
        'cars' => 2,
        'perfumes' => 1,
    ];

    public function p1(string $input): mixed
    {
        $lines = explode("\n", trim($input));

        $aunts = [];

        foreach ($lines as $line) {
            preg_match('/Sue (.+): (.+): (.+), (.+): (.+), (.+): (.+)/', $line, $matches);
            $aunt = $matches[1];
            $aunts[$aunt] = [
                $matches[2] => (int) $matches[3],
                $matches[4] => (int) $matches[5],
                $matches[6] => (int) $matches[7],
            ];
        }

        foreach ($aunts as $name => $aunt) {
            if($this->check($aunt, 1)) return $name;
        }
    }

    public function p2(string $input): mixed
    {
        $lines = explode("\n", trim($input));

        $aunts = [];

        foreach ($lines as $line) {
            preg_match('/Sue (.+): (.+): (.+), (.+): (.+), (.+): (.+)/', $line, $matches);
            $aunt = $matches[1];
            $aunts[$aunt] = [
                $matches[2] => (int) $matches[3],
                $matches[4] => (int) $matches[5],
                $matches[6] => (int) $matches[7],
            ];
        }

        foreach ($aunts as $name => $aunt) {
            if($this->check($aunt, 2)) return $name;
        }
    }

    private function check(array $auntAttributes, int $p): bool
    {
        $matches = true;
        foreach ($auntAttributes as $name => $attribute) {
            if ($p === 1 ? !$this->p1Check($name, $attribute) : !$this->p2Check($name, $attribute)) {
                $matches = false;
                break;
            }
        }

        return $matches;
    }

    private function p1Check($attributeName, $value)
    {
        return $this->clues[$attributeName] === $value;
    }

    private function p2Check($attributeName, $value)
    {
        return match($attributeName){
            'cats', 'trees' => $this->clues[$attributeName] < $value,
            'pomeranians', 'goldfish' => $this->clues[$attributeName] > $value,
            default => $this->clues[$attributeName] === $value
        };
    }

}
