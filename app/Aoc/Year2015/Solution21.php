<?php

declare(strict_types=1);

namespace App\Aoc\Year2015;

use App\Services\Aoc\SolutionInterface;
use function array_map;
use function array_splice;
use function ceil;
use function count;
use function explode;
use function max;
use function trim;

class Solution21 implements SolutionInterface
{
    private array $weapons = [
        [0,0,0],
        [8,4,0],
        [10,5,0],
        [25,6,0],
        [40,7,0],
        [74,8,0],
    ];

    private array $armors = [
        [0,0,0],
        [13,0,1],
        [31,0,2],
        [53,0,3],
        [75,0,4],
        [102,0,5],
    ];

    private array $rings =[
        [0,0,0],
        [25, 1, 0],
        [50, 2, 0],
        [100, 3, 0],
        [20, 0, 1],
        [40, 0, 2],
        [80, 0, 3],
    ];
    public function p1(string $input): mixed
    {
//        $playerStats = [
//            8, 5, 5
//        ];
//        $bossStats = [
//            12, 7, 2
//        ];

        $bossStats = array_map(fn($line) => explode(' ', trim($line))[count(explode(' ', trim($line))) - 1], explode("\n", trim($input)));

        $winningCosts = [];

        for ($amountOfRings = 0; $amountOfRings <= 2; $amountOfRings++) {
            $rings = $this->rings;
            for ($ring = 0; $ring < $amountOfRings; $ring++) {
                for ($actualRing = 0; $actualRing <= count($rings); $actualRing++) {
                    array_splice($rings, $actualRing, 1);
                }
            }
        }

        for ($weapon = 0; $weapon < count($this->weapons); $weapon++){
            for ($armor = 0; $armor < count($this->armors); $armor++){
            }
        }


    }

    public function p2(string $input): mixed
    {
        return null;
    }

    private function playerWins(array $playerStats, array $bossStats): bool
    {
        $bossAttacks = ceil($bossStats[0] / max($playerStats[1] - $bossStats[2], 1));
        $playerAttacks = ceil($playerStats[0] / max($bossStats[1] - $playerStats[2], 1));

        return $playerAttacks >= $bossAttacks;
    }
}
