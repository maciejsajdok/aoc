<?php

declare(strict_types=1);

namespace App\Aoc\Year2015;

use App\Services\Aoc\SolutionInterface;
use function count;
use function explode;
use function in_array;
use function max;
use function min;
use function trim;
use const PHP_INT_MAX;

class Solution09 implements SolutionInterface
{
    public function p1(string $input): mixed
    {
        return $this->process($input);
    }

    public function p2(string $input): mixed
    {
        return $this->process($input, false);
    }

    private function process(string $input, bool $p = true): int
    {
        $lines = explode("\n", trim($input));
        $connections = [];
        foreach ($lines as $line) {
            $parts = explode(" ", $line);
            $connections[$parts[0]][$parts[2]] = (int) $parts[4];
            $connections[$parts[2]][$parts[0]] = (int) $parts[4];
        }

        $sums = [];
        foreach ($connections as $name => $count) {
            $sums[] = $this->getConnectionsLengths($name, [], $connections, $p);
        }
        if ($p) {
            return min($sums);
        } else {
            return max($sums);
        }
    }

    private function getConnectionsLengths(string $startingPoint, array $visited, array $connections, bool $p): int
    {
        if (in_array($startingPoint, $visited)) {
            if (count($connections) === count($visited)) {
                $sum = 0;
                $citiesAmount = count($connections);
                for ($i = 0; $i < $citiesAmount - 1; $i++) {
                    $sum += $connections[$visited[$i]][$visited[$i + 1]];
                }
                return $sum;
            } else {
                if ($p){
                    return PHP_INT_MAX;
                } else {
                    return 0;
                }
            }
        }
        $visited[] = $startingPoint;

        $sums = [];
        foreach ($connections[$startingPoint] as $name => $length) {
            $sums[] = $this->getConnectionsLengths($name, $visited, $connections, $p);
        }

        if ($p) {
            return min($sums);
        } else {
            return max($sums);
        }
    }

}
