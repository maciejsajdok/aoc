<?php

declare(strict_types=1);

namespace App\Aoc\Year2015;

use App\Services\Aoc\SolutionInterface;
use function array_keys;
use function count;
use function explode;
use function intdiv;
use function max;
use function min;
use function trim;

class Solution14 implements SolutionInterface
{
    //Example
//    private int $runtime = 1000;
    private int $runtime = 2503;

    public function p1(string $input): mixed
    {
        $lines = explode("\n", trim($input));

        $reindeers = [];

        foreach ($lines as $line) {
            $els = explode(" ", $line);
            $reindeers[$els[0]] = [
                'speed' => $els[3],
                'duration' => $els[6],
                'resting' => $els[count($els) - 2]
            ];
        }

        $scoreboard = [];
        foreach ($reindeers as $name => $reindeer) {
            $scoreboard[$name] = $this->getPointsAtSecond($reindeer, $this->runtime);
        }

        return max($scoreboard);
    }

    public function p2(string $input): mixed
    {
        $lines = explode("\n", trim($input));

        $reindeers = [];

        foreach ($lines as $line) {
            $els = explode(" ", $line);
            $reindeers[$els[0]] = [
                'speed' => $els[3],
                'duration' => $els[6],
                'resting' => $els[count($els) - 2]
            ];
        }

        $scoreboard = [];
        for ($i = 1; $i < $this->runtime + 1; $i++) {
            $reindeerResults = [];
            foreach ($reindeers as $name => $reindeer) {
                $reindeerResults[$name] = $this->getPointsAtSecond($reindeer, $i);
            }

            $winningScore = max($reindeerResults);
            $winners = array_keys( $reindeerResults, $winningScore, true);

            foreach ($winners as $winner) {
                if (!isset($scoreboard[$winner])) {
                    $scoreboard[$winner] = 1;
                } else {
                    $scoreboard[$winner]++;
                }
            }
        }

        return max($scoreboard);
    }

    private function getPointsAtSecond(array $reindeer, $time): float
    {
        $travel = (int) $reindeer['duration'];
        $rest = (int) $reindeer['resting'];
        $speed = (int) $reindeer['speed'];

        $cycle = $travel + $rest;
        [$q, $r] = [
            intdiv($time, $cycle),
            $time % $cycle
        ];


        return ($q*$travel + min($r, $travel)) * $speed;
    }
}
