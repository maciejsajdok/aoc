<?php

declare(strict_types=1);

namespace App\Aoc\Year2024;

use App\Services\Aoc\SolutionInterface;
use function array_map;
use function array_slice;
use function count;
use function explode;
use function implode;
use function intdiv;
use function pow;

class Solution17 implements SolutionInterface
{
    private bool $debug = false;
    public function p1(string $input): mixed
    {
        return $this->runProgram(
            37283687,
            0,
            0,
            array_map('intval',explode(',', '2,4,1,3,7,5,4,1,1,3,0,3,5,5,3,0'))
        );
    }

    public function p2(string $input): mixed
    {
        $instructions = '2,4,1,3,7,5,4,1,1,3,0,3,5,5,3,0';

        $program = array_map('intval',explode(',', $instructions));

        //Registers B and C does not matter at all, what matters is that A is divided by 8 A = A / 2^3 as it came out
        //of printing out every operation. Also there is some pattern where first digit changes every iteration, second every 8th
        // 3rd every 64th etc
        return  $this->findCorrectValue($program, $program);
    }

    private function findCorrectValue(array $program, array $programRest): int
    {
        if (count($programRest) === 1){
            $regABoundary = 0;
        } else {
            $regABoundary = 8 * $this->findCorrectValue($program, array_slice($programRest, 1));
        }

        while($this->runProgram($regABoundary, 0, 0, $program) !== implode(',', $programRest))
        {
            $regABoundary ++;
        }
        return $regABoundary;
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
                    $this->debug && print("A = A // (2 << $value)\n");
                    break;

                case 1:
                    $B = $B ^ $operand;
                    $this->debug && print("B = B ^ $operand\n");
                    break;

                case 2:
                    $value = $this->getComboValue($operand, $A, $B, $C);
                    $B = $value % 8;
                    $this->debug && print("B = $value % 8\n");
                    break;

                case 3:
                    if ($A != 0) {
                        $this->debug && print("jmp $operand\n");
                        $instructionPointer = $operand;
                    }
                    break;

                case 4:
                    $B = $B ^ $C;
                    $this->debug && print("B = B ^ C\n");
                    break;

                case 5:
                    $value = $this->getComboValue($operand, $A, $B, $C);
                    $output[] = $value % 8;
                    $v = $value %8;
                    $this->debug && print("out $value % 8 = $v \n\n ");
                    break;

                case 6:
                    $value = $this->getComboValue($operand, $A, $B, $C);
                    $B = intdiv($A, pow(2, $value));
                    $this->debug && print("B = A // (2 << $value)\n");
                    break;

                case 7:
                    $value = $this->getComboValue($operand, $A, $B, $C);
                    $C = intdiv($A, pow(2, $value));
                    $this->debug && print("C = A // (2 << $value)\n");
                    break;

                default:
                    return implode(",", $output);
            }
        }

        return implode(",", $output);
    }
}
