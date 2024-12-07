<?php

declare(strict_types=1);

namespace App\Aoc\Year2015;

use App\Services\Aoc\SolutionInterface;
use Spatie\Fork\Fork;
use function dump;
use function explode;
use function min;
use function rand;
use function sprintf;
use const PHP_INT_MAX;

class Solution22 implements SolutionInterface
{
    private int $minimumWinningCost = PHP_INT_MAX;
    private int $counter = 0;
    private bool $debug = true;

    public function p1(string $input): mixed
    {
        $lines = explode("\n", trim($input));
        $bossHp = (int) explode(": ", trim($lines[0]))[1];
        $bossDamage = (int) explode(": ", trim($lines[1]))[1];
        $playerHp = 50;
        $playerMana = 500;
        $callables = [];
        for ($i = 0; $i < 12; $i++) {
            $callables[] = function () use ($playerMana, $bossDamage, $playerHp, $bossHp) {
                $minMana = PHP_INT_MAX;
                for ($i = 0; $i < 10000; $i++) {
                    $mana = $this->performFight(
                        $bossHp,
                        $playerHp,
                        $bossDamage,
                        $playerMana
                    );
                    $minMana = min($mana, $minMana);
                }
                return $minMana;
            };
        }
        $results = Fork::new()->run(...$callables);
        return min($results);
    }


    public function p2(string $input): mixed
    {
        $lines = explode("\n", trim($input));
        $bossHp = (int) explode(": ", trim($lines[0]))[1];
        $bossDamage = (int) explode(": ", trim($lines[1]))[1];
        $playerHp = 50;
        $playerMana = 500;
        $callables = [];
        for ($i = 0; $i < 12; $i++) {
            $callables[] = function () use ($playerMana, $bossDamage, $playerHp, $bossHp) {
                $minMana = PHP_INT_MAX;
                for ($i = 0; $i < 10000; $i++) {
                    $mana = $this->performFight(
                        $bossHp,
                        $playerHp,
                        $bossDamage,
                        $playerMana,
                        true
                    );
                    $minMana = min($mana, $minMana);
                }
                return $minMana;
            };
        }
        $results = Fork::new()->run(...$callables);
        return min($results);
    }

    private function performFight(int $bossHp, int $playerHp, int $bossDamage, int $currentMana, bool $second = false): int
    {
        $manaUsed =0;
        $rt = 0;
        $st = 0;
        $pt = 0;
        $pe = false;
        $se = false;
        $re = false;


        while(true){

            //PLAYER TURN
            if ($second === true){
                $playerHp -= 1;
                if ($playerHp <= 0 ) return PHP_INT_MAX;
            }
            //Ticks
            if ($pe === true){
                $pt --;
                $bossHp -=3;
            }
            if ($re === true){
                $currentMana += 101;
                $rt --;
            }
            if ($se === true){
                $st --;
            }
            if ($bossHp<=0) {
                return $manaUsed;
            }


            if ($st <=0){ $se = false;}
            if ($pt <=0){ $pe = false;}
            if ($rt <=0){ $re = false;}
            //Pick a spell
            $randomSpell = Spell::cases()[rand(0,count(Spell::cases())-1)];
            while(true){
                if ($randomSpell === Spell::SHIELD && $se === true){
                    $randomSpell = Spell::cases()[rand(0,count(Spell::cases())-1)];
                }
                else if ($randomSpell === Spell::POISON && $pe === true){
                    $randomSpell = Spell::cases()[rand(0,count(Spell::cases())-1)];
                }
                else if ($randomSpell === Spell::RECHARGE && $re === true){
                    $randomSpell = Spell::cases()[rand(0,count(Spell::cases())-1)];
                } else {
                    break;
                }
            }

            if ($randomSpell === Spell::MAGIC_MISSILE){
                $currentMana -= 53;
                $manaUsed += 53;
                $bossHp -=4;
            }
            if ($randomSpell === Spell::DRAIN){
                $currentMana -= 73;
                $manaUsed += 73;
                $bossHp -=2;
                $playerHp +=2;
            }
            if ($randomSpell === Spell::SHIELD){
                $currentMana -= 113;
                $manaUsed += 113;
                $se = true;
                $st = 6;
            }
            if ($randomSpell === Spell::POISON){
                $currentMana -= 173;
                $manaUsed += 173;
                $pe = true;
                $pt = 6;
            }
            if ($randomSpell === Spell::RECHARGE){
                $currentMana -= 229;
                $manaUsed += 229;
                $rt = 5;
                $re = true;
            }
            if ($currentMana <= 0){
                return PHP_INT_MAX;
            }

            if ($bossHp<=0){
                return $manaUsed;
            }

            if ($pe === true){
                $pt --;
                $bossHp -=3;
            }
            if ($re === true){
                $currentMana += 101;
                $rt --;
            }
            if ($se === true){
                $st --;
            }

            if ($bossHp<=0){
                return $manaUsed;
            }

            if ($se){
                $bossPunch = $bossDamage - 7;
                if ($bossPunch <= 0) $bossPunch = 1;
                $playerHp -= $bossPunch;
            } else {
                $playerHp -= $bossDamage;
            }

            if ($playerHp<=0) {
                return PHP_INT_MAX;
            };

            if ($st <=0){ $se = false;}
            if ($pt <=0){ $pe = false;}
            if ($rt <=0){ $re = false;}
        }
    }

    public function performTicks(int $rt, int $st, int $pt, int $currentMana, int $bossHp, int $playerArmor): array
    {
        if ($rt > 0) {
            $currentMana += 101;
            $rt--;
        }
        if ($pt > 0) {
            $bossHp -= 3;
            $pt--;
        }
        if ($st > 0) {
            $playerArmor = 7;
            $st--;
        }
        return array($rt, $st, $pt, $currentMana, $bossHp, $playerArmor);
    }
    private function testRun()
    {
        $spellsInOrder = [
            Spell::RECHARGE,
            Spell::SHIELD,
            Spell::DRAIN,
            Spell::POISON,
            Spell::MAGIC_MISSILE,
        ];
        $manaUsed =0;
        $rt = 0;
        $st = 0;
        $pt = 0;
        $pe = false;
        $se = false;
        $re = false;
        $playerHp = 10;
        $bossHp = 14;
        $currentMana = 250;
        $bossDamage = 8;
        $turn = 0;
        $spells = [];
        $spell =0;
        while(true){

            //PLAYER TURN

            $this->debug && dump(sprintf("\n-- Player turn --\n
- Player has %s hit points, %s armor, %s mana\n
- Boss has %s hit points\n", $playerHp, $se ? 7 : 0, $currentMana, $bossHp));
            //Ticks
            $turn ++;
            if ($pe === true){
                $pt --;
                $bossHp -=3;
                dump("Poison deals 3 damage; its timer is now {$pt}.\n");
            }
            if ($re === true){
                $currentMana += 101;
                $rt --;
                dump("Recharge provides 101 mana; its timer is now {$rt}.\n");
            }
            if ($se === true){
                $st --;
                dump("Shield's timer is now {$st}.\n");
            }
            if ($bossHp<=0) {
                dump("This kills the boss, and the player wins.\n");
                return $manaUsed;
            }


            if ($st <=0){
                $se = false;
                dump("Shield wears off\n");
            }
            if ($pt <=0){
                $pe = false;
                dump("Poison wears off\n");
            }
            if ($rt <=0){
                $re = false;
                dump("Recharge wears off\n");
            }
            //Pick a spell
            $randomSpell = $spellsInOrder[$spell++];
            $spells[] = $randomSpell->name;
            if ($randomSpell === Spell::MAGIC_MISSILE){
                $currentMana -= 53;
                $manaUsed += 53;
                $bossHp -=4;
                dump("Player casts Magic Missile, dealing 4 damage");
            }
            if ($randomSpell === Spell::DRAIN){
                $currentMana -= 73;
                $manaUsed += 73;
                $bossHp -=2;
                $playerHp +=2;
                dump("Player casts Drain, dealing 2 damage, and healing 2 hit points\n");
            }
            if ($randomSpell === Spell::SHIELD){
                $currentMana -= 113;
                $manaUsed += 113;
                $se = true;
                $st = 6;
                dump("Player casts Shield, increasing armor by 7\n");
            }
            if ($randomSpell === Spell::POISON){
                $currentMana -= 173;
                $manaUsed += 173;
                $pe = true;
                $pt = 6;
                dump("Player casts Poison\n");
            }
            if ($randomSpell === Spell::RECHARGE){
                $currentMana -= 229;
                $manaUsed += 229;
                $rt = 5;
                $re = true;
                dump("Player casts Recharge\n");
            }
            if ($currentMana <= 0){
                return PHP_INT_MAX;
            }

            if ($bossHp<=0){
                dump("This kills the boss, and the player wins.\n");
                return $manaUsed;
            }

            $this->debug && dump(sprintf("\n-- Boss turn --\n
- Player has %s hit points, %s armor, %s mana\n
- Boss has %s hit points\n", $playerHp, $se ? 7 : 0, $currentMana, $bossHp));
            if ($pe === true){
                $pt --;
                $bossHp -=3;
                dump("Poison deals 3 damage; its timer is now {$pt}.\n");
            }
            if ($re === true){
                $currentMana += 101;
                $rt --;
                dump("Recharge provides 101 mana; its timer is now {$rt}.\n");
            }
            if ($se === true){
                $st --;
                dump("Shield's timer is now {$st}.\n");
            }

            $turn ++;
            if ($bossHp<=0){
                dump("This kills the boss, and the player wins.\n");
                return $manaUsed;
            }

            if ($se){
                $bossPunch = $bossDamage - 7;
                if ($bossPunch <= 0) $bossPunch = 1;
                $playerHp -= $bossPunch;
                dump("Boss attacks for {$bossDamage} - 7 = {$bossPunch}\n");
            } else {
                $playerHp -= $bossDamage;
                dump("Boss attacks for {$bossDamage}\n");
            }

            if ($playerHp<=0) {
                return PHP_INT_MAX;
            };

            if ($st <=0){ $se = false;
                dump("Shield wears off\n");}
            if ($pt <=0){ $pe = false;
                dump("Poison wears off\n");}
            if ($rt <=0){ $re = false;
                dump("Recharge wears off\n");}
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
