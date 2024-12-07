<?php

declare(strict_types=1);

namespace App\Aoc\Year2015;

use App\Services\Aoc\SolutionInterface;
use function explode;
use function str_replace;

class Solution23 implements SolutionInterface
{
    public function p1(string $input): mixed
    {
        $lines = explode("\n", str_replace(',','',trim($input)));
        $registers = ['a' => 0, 'b' => 0];
        $instructions = [];
        foreach ($lines as $line) {
            $instructions[] = explode(" ", trim($line));
        }
        $instructionIndex = 0;
        while(true){
            if ($instructionIndex >= count($instructions)){
                break;
            }
            $instruction = $instructions[$instructionIndex];
            $op = $instruction[0];

            if ($op === 'hlf'){
                $registers[$instruction[1]] /=2;
                $instructionIndex++;
                continue;
            }

            if ($op === 'tpl'){
                $registers[$instruction[1]] *=3;
                $instructionIndex++;
                continue;
            }

            if ($op === 'inc'){
                $registers[$instruction[1]] += 1;
                $instructionIndex++;
                continue;
            }

            if ($op === 'jmp'){
                $instructionIndex += $instruction[1];
                continue;
            }

            if ($op === 'jie'){
                if ($registers[$instruction[1]] %2 === 0){
                    $instructionIndex += $instruction[2];
                } else {
                    $instructionIndex++;
                }
                continue;
            }

            if ($op === 'jio'){
                if ($registers[$instruction[1]] === 1){
                    $instructionIndex += $instruction[2];
                } else {
                    $instructionIndex++;
                }
            }
        }

        return $registers['b'];
    }

    public function p2(string $input): mixed
    {
        $lines = explode("\n", str_replace(',','',trim($input)));
        $registers = ['a' => 1, 'b' => 0];
        $instructions = [];
        foreach ($lines as $line) {
            $instructions[] = explode(" ", trim($line));
        }
        $instructionIndex = 0;
        while(true){
            if ($instructionIndex >= count($instructions)){
                break;
            }
            $instruction = $instructions[$instructionIndex];
            $op = $instruction[0];

            if ($op === 'hlf'){
                $registers[$instruction[1]] /=2;
                $instructionIndex++;
                continue;
            }

            if ($op === 'tpl'){
                $registers[$instruction[1]] *=3;
                $instructionIndex++;
                continue;
            }

            if ($op === 'inc'){
                $registers[$instruction[1]] += 1;
                $instructionIndex++;
                continue;
            }

            if ($op === 'jmp'){
                $instructionIndex += $instruction[1];
                continue;
            }

            if ($op === 'jie'){
                if ($registers[$instruction[1]] %2 === 0){
                    $instructionIndex += $instruction[2];
                } else {
                    $instructionIndex++;
                }
                continue;
            }

            if ($op === 'jio'){
                if ($registers[$instruction[1]] === 1){
                    $instructionIndex += $instruction[2];
                } else {
                    $instructionIndex++;
                }
            }
        }

        return $registers['b'];
    }
}
