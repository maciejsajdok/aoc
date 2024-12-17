<?php

declare(strict_types=1);

namespace App\Aoc\Year2024;

use App\Services\Aoc\SolutionInterface;
use function array_map;
use function explode;
use function implode;
use function intdiv;
use function pow;

class Solution17 implements SolutionInterface
{
    public function p1(string $input): mixed
    {
//        return $this->runProgram(729, 0, 0, array_map('intval',explode(',', '0,1,5,4,3,0')));
        return $this->runProgram(
            37283687,
            0,
            0,
            array_map('intval',explode(',', '2,4,1,3,7,5,4,1,1,3,0,3,5,5,3,0'))
        );
    }

    public function p2(string $input): mixed
    {
        return null;
    }
    private function getComboValue($operand, $A, $B, $C): int
    {
        return match ($operand) {
            0, 1, 2, 3 => $operand,
            4 => $A,
            5 => $B,
            6 => $C,
            default => 0,
        };
    }
    private function runProgram($registerA, $registerB, $registerC, $program): string
    {
        $A = $registerA;
        $B = $registerB;
        $C = $registerC;
        $output = [];
        $instructionPointer = 0;

        while ($instructionPointer < count($program)) {
            $opcode = $program[$instructionPointer];
            $operand = $program[$instructionPointer + 1];
            $instructionPointer += 2;

            switch ($opcode) {
                case 0:
                    $value = $this->getComboValue($operand, $A, $B, $C);
                    $A = intdiv($A, pow(2, $value));
                    break;

                case 1:
                    $B = $B ^ $operand;
                    break;

                case 2:
                    $value = $this->getComboValue($operand, $A, $B, $C);
                    $B = $value % 8;
                    break;

                case 3:
                    if ($A != 0) {
                        $instructionPointer = $operand;
                    }
                    break;

                case 4:
                    $B = $B ^ $C;
                    break;

                case 5:
                    $value = $this->getComboValue($operand, $A, $B, $C);
                    $output[] = $value % 8;
                    break;

                case 6:
                    $value = $this->getComboValue($operand, $A, $B, $C);
                    $B = intdiv($A, pow(2, $value));
                    break;

                case 7:
                    $value = $this->getComboValue($operand, $A, $B, $C);
                    $C = intdiv($A, pow(2, $value));
                    break;

                default:
                    return implode(",", $output);
            }
        }

        return implode(",", $output);
    }
}
