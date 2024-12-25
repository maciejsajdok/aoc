<?php

declare(strict_types=1);

namespace App\Aoc\Year2024;

use App\Services\Aoc\SolutionInterface;
use App\Utilities\Arr;
use Illuminate\Support\Str;
use function array_reverse;
use function bindec;
use function dd;
use function decbin;
use function dump;
use function explode;
use function implode;
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
        global $knownValues;
        $circuit = [];
        [$knownRegisters, $operations] = explode("\n\n", $input);
        $xRegisters = [];
        $yRegisters = [];
        foreach (explode("\n",$knownRegisters) as $knownRegister){
            [$reg, $val] = explode(": ", $knownRegister);
            $circuit[$reg] = [(int) $val];
            if (Str::startsWith($reg,'y')){
                $yRegisters[$reg] = (int) $val;
            }
            if (Str::startsWith($reg,'x')){
                $xRegisters[$reg] = (int) $val;
            }
        }
        ksort($yRegisters, SORT_ASC);
        ksort($xRegisters, SORT_ASC);
        $zRegisters = [];
        foreach (explode("\n",$operations) as $operation){
            [$parents, $reg] = explode(' -> ', $operation);
            if (Str::startsWith($reg,'z') && strlen($reg) === 3)
            {
                $zRegisters[$reg] = null;
            }
            $circuit[$reg] = explode(' ', $parents);
        }

//        Arr::arraySwapAssociative($circuit, 'z07', 'bjm');

        foreach ($zRegisters as $reg => $val){
            $this->solveCircuit($circuit, $reg);
            $zRegisters[$reg] = $knownValues[$reg];
        }
        ksort($zRegisters, SORT_ASC);
        $xBin = implode('',array_reverse($xRegisters));
        $yBin = implode('',array_reverse($yRegisters));
        $zBin = implode('',array_reverse($zRegisters));

        dump([
            $xBin,
            $yBin,
            decbin(bindec($xBin) + bindec($yBin)),
            $zBin
        ]);
        $this->generateDotDiagram($circuit);
        return bindec($zBin);
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

    private function generateDotDiagram(array $data)
    {
        $dot = "digraph WireDiagram {\n";
        $dot .= "    rankdir=LR;\n";
        $dot .= "    node [shape=rectangle];\n";

        foreach ($data as $register => $info) {
            if (count($info) === 3) {
                [$input1, $operation, $input2] = $info;
                $dot .= "    \"$input1\" -> \"$register\" [label=\"$operation\"];\n";
                $dot .= "    \"$input2\" -> \"$register\" [label=\"$operation\"];\n";
            } else {
                $dot .= "    \"$register\" [label=\"$register\\n{$info[0]}\", shape=circle];\n";
            }
        }

        $dot .= "}\n";

        file_put_contents("wire_diagram.dot", $dot);
    }
}
