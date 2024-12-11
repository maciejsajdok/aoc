<?php

declare(strict_types=1);

namespace App\Aoc\Year2024;

use App\Services\Aoc\SolutionInterface;
use function count;
use function dump;
use function explode;
use function fgets;
use function once;
use function strlen;
use function substr;
use const STDIN;

class Solution11 implements SolutionInterface
{
    public function p1(string $input): mixed
    {
        $stones = explode(" ", $input);

        $blinks = 25;
        $newStones = [];
        for ($i = 0; $i < $blinks; $i++) {
            foreach ($stones as $stone) {
                if ($stone === '0') {
                    $newStones[] = '1';
                } else if (strlen($stone) % 2 === 0) {
                    $len = strlen($stone) / 2;
                    $newStones[] = (string)((int)substr($stone, 0, $len));
                    $newStones[] = (string)((int)substr($stone, $len, $len));
                } else {
                    $newStones[] = (string)((int)$stone * 2024);
                }
            }
            $stones = $newStones;
            $newStones = [];
//            dump($stones);
//            fgets(STDIN);
        }
        return count($stones);
    }

    public function p2(string $input): mixed
    {
        $stones = explode(" ", $input);

        $blinks = 75;
        $result = 0;
        foreach ($stones as $stone) {
            $result += $this->transformStone($stone, $blinks);
        }
        return $result;
    }

    private function transformStone(string $stone, int $blinks): int
    {
        if ($blinks === 0) {
            return 1;
        }

        if ($stone === '0') {
            return once(function () use ($blinks) {
                return $this->transformStone('1', $blinks - 1);
            });
        }

        if (strlen($stone) % 2 === 0) {
            $len = strlen($stone) / 2;
            $l = (string)((int)substr($stone, 0, $len));
            $r = (string)((int)substr($stone, $len, $len));
            return once(function () use ($blinks, $l, $r) {
                return ($this->transformStone($l, $blinks - 1) + $this->transformStone($r, $blinks - 1));
            });
        }
        return once(function () use ($blinks, $stone) {
            return $this->transformStone((string)((int)$stone * 2024), $blinks - 1);
        });
    }
}
//}
