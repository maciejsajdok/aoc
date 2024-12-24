<?php

declare(strict_types=1);

namespace App\Aoc\Year2024;

use App\Services\Aoc\SolutionInterface;
use Illuminate\Support\Str;
use function array_reverse;
use function bindec;
use function explode;
use function ksort;
use function strlen;
use const SORT_ASC;

class Solution24 implements SolutionInterface
{
    public function p1(string $input): mixed
    {
        global $knownValues;
        $circuit = [];
        [$knownRegisters, $operations] = explode("\n\n", $input);

        foreach (explode("\n",$knownRegisters) as $knownRegister){
            [$reg, $val] = explode(": ", $knownRegister);
            $circuit[$reg] = [(int) $val];
        }

        $zRegisters = [];
        foreach (explode("\n",$operations) as $operation){
            [$parents, $reg] = explode(' -> ', $operation);
            if (Str::startsWith($reg,'z') && strlen($reg) === 3)
            {
                $zRegisters[$reg] = null;
            }
            $circuit[$reg] = explode(' ', $parents);
        }
        foreach ($zRegisters as $reg => $val){
            $this->solveCircuit($circuit, $reg);
            $zRegisters[$reg] = $knownValues[$reg];
        }

        ksort($zRegisters, SORT_ASC);
        return bindec(implode("" ,array_reverse($zRegisters)));
    }

    public function p2(string $input): mixed
    {
        return null;
    }

    private function solveCircuit(array $circuit, $value)
    {
        global $knownValues;
        if (ctype_digit($value)) return $value;
        if (isset($knownValues[$value])) return $knownValues[$value];

        $operator = $circuit[$value];

        if (count($operator) === 1){
            $knownValues[$value] = $operator[0];
        } else if (in_array('XOR', $operator)){
            $knownValues[$value] = $this->solveCircuit($circuit, $operator[0]) ^ $this->solveCircuit($circuit, $operator[2]);
        } else if (in_array('AND', $operator)){
            $knownValues[$value] = $this->solveCircuit($circuit, $operator[0]) & $this->solveCircuit($circuit, $operator[2]);
        } else if (in_array('OR', $operator)){
            $knownValues[$value] = $this->solveCircuit($circuit, $operator[0]) | $this->solveCircuit($circuit, $operator[2]);
        }

        return $knownValues[$value];
    }
}
