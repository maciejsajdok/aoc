<?php

declare(strict_types=1);

namespace App\Aoc\Year2015;

use App\Services\Aoc\SolutionInterface;
use function dd;
use function explode;
use function min;
use function rand;
use const PHP_INT_MAX;

class Solution22 implements SolutionInterface
{
    private int $minimumWinningCost = PHP_INT_MAX;
    private int $counter = 0;


    public function p1(string $input): mixed
    {
        $lines = explode("\n", trim($input));
        $bossHp = (int) explode(": ", trim($lines[0]))[1];
        $bossDamage = (int) explode(": ", trim($lines[1]))[1];
        $playerHp = 50;
        $playerMana = 500;
        $minMana = PHP_INT_MAX;
        for ($i = 0; $i < 10000000; $i++) {
            $mana = $this->performFight(
                $bossHp,
                $playerHp,
                $bossDamage,
                $playerMana
            );

            $minMana = min($mana, $minMana);
        }
        return $minMana;
    }


    public function p2(string $input): mixed
    {
        return null;
    }

    private function performFight(int $bossHp, int $playerHp, int $bossDamage, int $currentMana): int
    {
        $manaUsed = $rt = $st = $pt = 0;

        while($playerHp > 0 && $bossHp > 0){
            if ($currentMana < 73) return PHP_INT_MAX;

            $spell = Spell::cases()[rand(0,4)];
            dd($spell);
        }
    }
}

enum Spell
{
    case MAGIC_MISSILE;
    case DRAIN;
    case SHIELD;
    case POISON;
    case RECHARGE;
}
