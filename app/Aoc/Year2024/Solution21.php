<?php

declare(strict_types=1);

namespace App\Aoc\Year2024;

use App\Services\Aoc\SolutionInterface;
use function abs;
use function count;
use function dump;
use function implode;
use function min;
use function str_repeat;
use function str_replace;
use function str_split;
use function trim;
use const PHP_EOL;

class Solution21 implements SolutionInterface
{
    private array $numericalKeyPad = [
        ['7', '8', '9'],
        ['4', '5', '6'],
        ['1', '2', '3'],
        [null, '0', 'A'],
    ];

    private array $numericalKeyPadCoordinates = [
        7 => [0, 0],
        8 => [1, 0],
        9 => [2, 0],
        4 => [0, 1],
        5 => [1, 1],
        6 => [2, 1],
        1 => [0, 2],
        2 => [1, 2],
        3 => [2, 2],
        0 => [1, 3],
        'A' => [2, 3]
    ];
    private array $robotKeypad = [
        [null, '^', 'A'],
        ['<', 'v', '>'],
    ];

    private array $robotKeypadCoordinates = [
        '^' => [1, 0],
        'A' => [2, 0],
        '<' => [0, 1],
        'v' => [1, 1],
        '>' => [2, 1],
    ];
    private array $memo = [];
    private function solve(mixed $startCharacter, mixed $targetCharacter, array $keypad, int $level, int $maxLevel)
    {
        $cacheKey = "{$startCharacter}-{$targetCharacter}-{$level}";
        if (isset($this->memo[$cacheKey])) {
            return $this->memo[$cacheKey];
        }
        //Best move is one that has the longest chain of same direction since it results in many A presses
        $s = $keypad[$startCharacter];
        $t = $keypad[$targetCharacter];

        $dx = $s[0] - $t[0];
        $dy = $s[1] - $t[1];

        //we create moves, straight horizontal and vertical lines since these are most efficient in the long run
        $verticalMove = $dy < 0 ? 'v' : '^';
        $horizontalMove = $dx < 0 ? '>' : '<';
        $verticalMoves = str_repeat($verticalMove, abs($dy));
        $horizontalMoves = str_repeat($horizontalMove, abs($dx));

        //and possible combinations, only 2 of these
        $combination1 = str_split('A' . $verticalMoves. $horizontalMoves . 'A');
        $combination2 = str_split('A' . $horizontalMoves. $verticalMoves . 'A');
        //if we reached the player, we return his moves + A keypress (1)
        if ($level === $maxLevel) {
            return $this->memo[$cacheKey] = abs($dx) + abs($dy) + 1;
        }
//        echo implode('', $combination1).' '.implode('', $combination2).PHP_EOL;
        $combination1MovesAmount = 0;
        for ($i = 0; $i < count($combination1) - 1; ++$i) {
            $combination1MovesAmount += $this->solve($combination1[$i], $combination1[$i + 1], $this->robotKeypadCoordinates, $level + 1, $maxLevel);
        }

        $combination2MovesAmount = 0;
        for ($i = 0; $i < count($combination2) - 1; ++$i) {
            $combination2MovesAmount += $this->solve($combination2[$i], $combination2[$i + 1], $this->robotKeypadCoordinates, $level + 1, $maxLevel);
        }

//        if ($combination1MovesAmount === 22 || $combination2MovesAmount === 22){
//            dump([
//                's' => $s,
//                't' => $t,
//                'sc' => $startCharacter,
//                'tc' => $targetCharacter,
//                'level' => $level,
//                [$s[0], $t[1]],
//                [$t[0], $s[1]]
//            ]);
//            dump($combination1MovesAmount, $combination2MovesAmount);
//            dump('here');
//        }
        //Numpad
        $targetCorner = ($level === 0) ? [0, 3] : [0, 0]; // Define target corner based on level

        if ($horizontalMove === '>' && $verticalMove === (($level === 0) ? 'v' : '^')) {
            $coordinate = ($level === 0) ? [$s[0], $t[1]] : [$s[0], $t[1]];
            if ($coordinate === $targetCorner) {
                return $this->memo[$cacheKey] = $combination2MovesAmount;
            }
        }

        if ($horizontalMove === '<' && $verticalMove === (($level === 0) ? '^' : 'v')) {
            $coordinate = ($level === 0) ? [$t[0], $s[1]] : [$s[1], $t[0]];
            if ($coordinate === $targetCorner) {
                return $this->memo[$cacheKey] = $combination1MovesAmount;
            }
        }

        return $this->memo[$cacheKey] = min($combination1MovesAmount, $combination2MovesAmount);
    }

    public function p1(string $input): mixed
    {
        $this->memo = [];
        $lines = explode("\n", trim($input));
        $result = 0;
        foreach ($lines as $line) {
            $code = str_split('A' . trim($line));
            $intPart = (int)str_replace('A', '', trim($line));
            $sum = 0;
            //Here be the second monster, instead of 1 i had 2 all the time and was angrily wondering why it could not work
            for ($i = 0; $i < count($code) - 1; ++$i) {
                $sum += $this->solve($code[$i], $code[$i + 1], $this->numericalKeyPadCoordinates, 0, 2);
            }
            $result += $sum * $intPart;
        }

        return $result;
    }

    public function p2(string $input): mixed
    {
        $this->memo = [];
        $lines = explode("\n", trim($input));
        $result = 0;
        foreach ($lines as $line) {
            $code = str_split('A' . trim($line));
            $intPart = (int)str_replace('A', '', trim($line));
            $sum = 0;
            //Here be the second monster, instead of 1 i had 2 all the time and was angrily wondering why it could not work
            for ($i = 0; $i < count($code) - 1; ++$i) {
                $sum += $this->solve($code[$i], $code[$i + 1], $this->numericalKeyPadCoordinates, 0, 25);
            }
            $result += $sum * $intPart;
        }
        return $result;
    }
}
