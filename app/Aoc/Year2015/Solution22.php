<?php

declare(strict_types=1);

namespace App\Aoc\Year2015;

use App\Services\Aoc\SolutionInterface;
use App\Utilities\Combinations;
use Illuminate\Log\Logger;
use Illuminate\Support\Facades\Log;
use Macocci7\PhpCombination\Combination;
use function array_keys;
use function count;
use function dd;
use function dump;
use function explode;
use function in_array;
use function min;
use const PHP_INT_MAX;

class Solution22 implements SolutionInterface
{
    /** @var array|Spell[]  */
    private array $spells;
    private array $winningCosts = [];
    private int $counter = 0;

    public function __construct()
    {
        $this->spells = [
            new Spell('Idle', 0, 0, 0, 0, 0, 0),
            new Spell('Magic Missile', 53, 0, 0, 4, 0, 0),
            new Spell('Drain', 73, 0, 0, 2, 2, 0),
            new Spell('Shield', 113, 6, 0, 0, 0, 7),
            new Spell('Poison', 173, 6, 0, 3, 0, 0),
            new Spell('Recharge', 229, 5, 101, 0, 0, 0),
        ];
    }

    public function p1(string $input): mixed
    {
        $lines = explode("\n", trim($input));
        $bossHp = (int) explode(": ", trim($lines[0]))[1];
        $bossDamage = (int) explode(": ", trim($lines[1]))[1];
        $playerHp = 10;
        $playerMana = 250;
        $this->winningCosts[] = PHP_INT_MAX;
        for ($i = 0; $i < count($this->spells); $i++) {
            $this->performFight(
                0,
                $bossHp,
                $playerHp,
                $bossDamage,
                $playerMana,
                0,
                0,
                0,
                $this->spells[$i],
                (string) $i
            );
        }
        dd($this->counter);
        dd($this->winningCosts);
        return null;
    }


    public function p2(string $input): mixed
    {
        return null;
    }

    private function performFight(int $manaUsed, int $bossHp, int $playerHp, int $bossDamage, int $currentMana, int $shieldTicks, int $poisonTicks, int $rechargeTicks, Spell $nextSpell, string $id)
    {
        $this->counter ++;
        $newManaUsed = $manaUsed;
        $newBossHp = $bossHp;
        $newPlayerHp = $playerHp;
        $newBossDamage = $bossDamage;
        $newCurrentMana = $currentMana;
        $newShieldTicks = $shieldTicks;
        $newPoisonTicks = $poisonTicks;
        $newRechargeTicks = $rechargeTicks;
        $playerArmor = 0;
        //Resolve ticks on player turn
        echo("- Player Turn - id = {$id}\n");
        echo("- Player has {$newPlayerHp} hit points, {$playerArmor} armor, {$newCurrentMana} mana\n");
        echo("- Boss has {$newBossHp} hit points\n");
        if ($nextSpell->name === 'Poison' && $newPoisonTicks > 0){
            $newPoisonTicks --;
            $newBossHp -= $nextSpell->damage;
            echo("Poison deals {$nextSpell->damage}; its timer is now {$newPoisonTicks}\n");
        }

        if ($nextSpell->name === 'Shield' && $newShieldTicks > 0){
            $newShieldTicks --;
            $playerArmor = $nextSpell->armor;
        }

        if ($nextSpell->name === 'Recharge' && $newRechargeTicks > 0){
            $newRechargeTicks --;
            $newCurrentMana += $nextSpell->mana;
            echo("Recharge provides {$nextSpell->mana} mana; its timer is now {$newRechargeTicks}\n");
        }

        if ($newBossHp <= 0){
            echo("Player wins\n");
            echo("\n\n");
            return;
        }

        // resolve spells

        if ($nextSpell->name === 'Magic Missile' && $nextSpell->cost <= $currentMana){
            $newManaUsed += $nextSpell->cost;
            $newBossHp -= $nextSpell->damage;
            $newCurrentMana -= $nextSpell->cost;
            echo("Player casts Magic Missile, dealing {$nextSpell->damage} damage.\n");
        }

        if ($nextSpell->name === 'Drain' && $nextSpell->cost <= $currentMana){
            $newManaUsed += $nextSpell->cost;
            $newBossHp -= $nextSpell->damage;
            $newCurrentMana -= $nextSpell->cost;
            $newPlayerHp += $nextSpell->heal;
            echo("Player casts Drain, dealing {$nextSpell->damage} damage, and healing {$nextSpell->heal} hit points.\n");
        }

        if($nextSpell->name === 'Shield' && $newShieldTicks === 0 && $nextSpell->cost <= $currentMana){
            $newManaUsed += $nextSpell->cost;
            $newCurrentMana -= $nextSpell->cost;
            $newShieldTicks += $nextSpell->duration;
            echo("Player casts Shield, increasing armor by 7.\n");
        }
        if($nextSpell->name === 'Recharge' && $newRechargeTicks === 0 && $nextSpell->cost <= $currentMana){
            $newManaUsed += $nextSpell->cost;
            $newCurrentMana -= $nextSpell->cost;
            $newRechargeTicks += $nextSpell->duration;
            echo("Player casts Recharge.\n");
        }
        if($nextSpell->name === 'Poison' && $newPoisonTicks === 0 && $nextSpell->cost <= $currentMana){
            echo("Player casts Poison. \n");
            $newCurrentMana -= $nextSpell->cost;
            $newManaUsed += $nextSpell->cost;
            $newPoisonTicks += $nextSpell->duration;
        }

        if ($newBossHp <= 0){
            echo("Player wins\n");
            echo("\n\n");
            return;
        }

        //Boss time

        //Resolve ticks on boss turn

        if ($nextSpell->name === 'Shield' && $newShieldTicks > 0){
            $newShieldTicks --;
            echo("Shield's timer is now {$newShieldTicks}.\n");
            $playerArmor = $nextSpell->armor;
        }
        echo("- Boss Turn - id = {$id}\n");
        echo("- Player has {$newPlayerHp} hit points, {$playerArmor} armor, {$newCurrentMana} mana\n");
        echo("- Boss has {$newBossHp} hit points\n");
        if ($nextSpell->name === 'Poison' && $newPoisonTicks > 0){
            $newPoisonTicks --;
            $newBossHp -= $nextSpell->damage;
            echo("Poison deals {$nextSpell->damage}; its timer is now {$newPoisonTicks}\n");
        }

        if ($nextSpell->name === 'Recharge' && $newRechargeTicks > 0){
            $newRechargeTicks --;
            $newCurrentMana += $nextSpell->mana;
            echo("Recharge provides {$nextSpell->mana} mana; its timer is now {$newRechargeTicks}\n");
        }

        if ($newBossHp <= 0){
            echo("Player wins\n");
            echo("\n\n");
            return;
        }

        $punch = $newBossDamage - $playerArmor;
        $newPlayerHp -= $punch;
        echo("Boss attacks for {$newBossDamage} damage!\n");

        if ($newPlayerHp <=0){
            echo("Boss wins\n");
            echo("\n\n");
            return;
        }
        for ($i = 0; $i <count($this->spells); $i++){
            $this->performFight(
                $newManaUsed,
                $newBossHp,
                $newPlayerHp,
                $newBossDamage,
                $newCurrentMana,
                $newShieldTicks,
                $newPoisonTicks,
                $newRechargeTicks,
                $this->spells[$i],
                $id.$i
            );
        }
    }
}

Class Spell
{
    public function __construct(
        public string $name,
        public int $cost,
        public int $duration,
        public int $mana,
        public int $damage,
        public int $heal,
        public int $armor
    )
    {
    }
}
