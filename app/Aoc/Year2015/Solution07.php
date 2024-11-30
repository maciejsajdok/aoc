<?php

declare(strict_types=1);

namespace App\Aoc\Year2015;

use App\Services\Aoc\SolutionInterface;
use Illuminate\Support\Arr;
use function array_pop;
use function explode;
use function in_array;

class Solution07 implements SolutionInterface
{
    public function p1(string $input): mixed
    {
        $operations = explode("\n", $input);
        $circuit = [];

        foreach ($operations as $operation) {
            if (empty($operation)) continue;
            $elements = explode(" ", $operation);
            $register = Arr::last($elements);
            array_pop($elements);
            array_pop($elements);
            $circuit[$register] = $elements;
        }

        return $this->solveCircuit($circuit, 'a');
    }


    public function p2(string $input): mixed
    {
        global $knownValues;
        $operations = explode("\n", $input);
        $circuit = [];

        foreach ($operations as $operation) {
            if (empty($operation)) continue;
            $elements = explode(" ", $operation);
            $register = Arr::last($elements);
            array_pop($elements);
            array_pop($elements);
            $circuit[$register] = $elements;
        }

        $registerA = $this->solveCircuit($circuit, 'a');
        $circuit['b'] = [$registerA];
        $knownValues = [];
        return $this->solveCircuit($circuit, 'a');
    }

    private function solveCircuit(array $circuit, $value)
    {
        global $knownValues;
        if (ctype_digit($value)) return $value;
        if (isset($knownValues[$value])) return $knownValues[$value];

        $operator = $circuit[$value];

        if (count($operator) === 1){
            $knownValues[$value] = $this->solveCircuit($circuit, $operator[0]);
        } else if (in_array('NOT', $operator)){
            $knownValues[$value] = (~ $this->solveCircuit($circuit,$operator[1])) & 0xFFFF;
        } else if (in_array('AND', $operator)){
            $knownValues[$value] = $this->solveCircuit($circuit, $operator[0]) & $this->solveCircuit($circuit, $operator[2]);
        } else if (in_array('OR', $operator)){
            $knownValues[$value] = $this->solveCircuit($circuit, $operator[0]) | $this->solveCircuit($circuit, $operator[2]);
        } else if (in_array('LSHIFT', $operator)){
            $knownValues[$value] = $this->solveCircuit($circuit, $operator[0]) << $this->solveCircuit($circuit, $operator[2]);
        } else if (in_array('RSHIFT', $operator)){
            $knownValues[$value] = $this->solveCircuit($circuit, $operator[0]) >> $this->solveCircuit($circuit, $operator[2]);
        }

        return $knownValues[$value];
    }
}
