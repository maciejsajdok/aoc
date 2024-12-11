<?php

declare(strict_types=1);

namespace App\Aoc\Year2024;

use App\Services\Aoc\SolutionInterface;
use function count;
use function explode;
use function strlen;
use function substr;

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
        static $cache;
        if (isset($cache[$stone][$blinks])){
            return $cache[$stone][$blinks];
        }
        if ($blinks === 0) {
            return 1;
        }

        if ($stone === '0') {
            $result = $this->transformStone('1', $blinks - 1);
        }else if (strlen($stone) % 2 === 0) {
            $len = strlen($stone) / 2;
            $l = ltrim(substr($stone, 0, $len), '0') ?: '0';
            $r = ltrim(substr($stone, $len), '0') ?: '0';

            $result = ($this->transformStone($l, $blinks - 1) + $this->transformStone($r, $blinks - 1));
        } else {
            $result = $this->transformStone((string)((int)$stone * 2024), $blinks - 1);
        }

        $cache[$stone][$blinks] = $result;
        return $result;
    }
}
