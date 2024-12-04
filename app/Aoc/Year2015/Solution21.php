<?php

declare(strict_types=1);

namespace App\Aoc\Year2015;

use App\Services\Aoc\SolutionInterface;
use Macocci7\PhpCombination\Combination;
use function array_keys;
use function array_map;
use function ceil;
use function count;
use function dd;
use function explode;
use function max;
use function min;
use function trim;

class Solution21 implements SolutionInterface
{
    private array $weapons = [
        [8, 4, 0],
        [10, 5, 0],
        [25, 6, 0],
        [40, 7, 0],
        [74, 8, 0],
    ];

    private array $armors = [
        [0, 0, 0],
        [13, 0, 1],
        [31, 0, 2],
        [53, 0, 3],
        [75, 0, 4],
        [102, 0, 5],
    ];

    private array $rings = [
        [0, 0, 0],
        [0, 0, 0],
        [25, 1, 0],
        [50, 2, 0],
        [100, 3, 0],
        [20, 0, 1],
        [40, 0, 2],
        [80, 0, 3],
    ];

    public function p1(string $input): mixed
    {
        $winningCosts = $this->generateCostsForEachCombination($input);

        return min($winningCosts);
    }

    public function p2(string $input): mixed
    {
        $losingCosts = $this->generateCostsForEachCombination($input, 2);

        return max($losingCosts);
    }

    private function generateCostsForEachCombination(string $input, int $p = 1): array
    {
        $costs = [];
        $playerHp = 100;
        $bossStats = array_map(fn($line) => explode(' ', trim($line))[count(explode(' ', trim($line))) - 1], explode("\n", trim($input)));

        $combination = new Combination();
        $ringIndexes = array_keys($this->rings);
        $armorIndexes = array_keys($this->armors);
        $weaponIndexes = array_keys($this->weapons);
        $armorCombinations = $combination->ofN($armorIndexes, 1);
        $weaponCombinations = $combination->ofN($weaponIndexes, 1);
        $ringCombinations = $combination->ofN($ringIndexes,2);
//dd($ringCombinations);
        foreach ($ringCombinations as $ringCombination) {
            foreach ($armorCombinations as $armorCombination){
                foreach ($weaponCombinations as $weaponCombination){
                    $ring1 = $this->rings[$ringCombination[0]];
                    $ring2 = $this->rings[$ringCombination[1]];
                    $weapon = $this->weapons[$weaponCombination[0]];
                    $armor = $this->armors[$armorCombination[0]];
                    $cost = $ring1[0] + $ring2[0] + $weapon[0] + $armor[0];
                    $damage = $ring1[1] + $ring2[1] + $weapon[1] + $armor[1];
                    $armor = $ring1[2] + $ring2[2] + $weapon[2] + $armor[2];

                    $playerStats = [
                        $playerHp,
                        $damage,
                        $armor
                    ];

                    if ($p === 1 ? $this->playerWins($playerStats, $bossStats) : !$this->playerWins($playerStats, $bossStats)) {
                        $costs[] =$cost;
                    }
                }
            }
        }
        return $costs;
    }

    private function playerWins(array $playerStats, array $bossStats): bool
    {
        $bossAttacks = ceil($bossStats[0] / max($playerStats[1] - $bossStats[2], 1));
        $playerAttacks = ceil($playerStats[0] / max($bossStats[1] - $playerStats[2], 1));

        return $playerAttacks >= $bossAttacks;
    }
}
