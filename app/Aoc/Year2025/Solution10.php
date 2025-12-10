<?php

declare(strict_types=1);

namespace App\Aoc\Year2025;

use App\Services\Aoc\SolutionInterface;
use Kerigard\LPSolve\Constraint;
use Kerigard\LPSolve\Problem;
use Kerigard\LPSolve\Solver;
use function array_fill;
use function array_map;
use function array_slice;
use function explode;
use function print_r;
use function str_split;
use function strlen;
use const EQ;

class Solution10 implements SolutionInterface
{
    public function p1(string $input): mixed
    {
        $data = explode("\n", trim($input));
        $result = 0;
        foreach ($data as $i => $line) {
            $buttonMasks = [];
            $segments = explode(" ", $line);
            $lightsSlice = str_split(trim($segments[0],'[]'));
            $amountOfLights = count($lightsSlice);
            $initialLight = 0;
            foreach ($lightsSlice as $lightIndex => $light) {
                if($light === '#'){
                    $initialLight |= (1 << $lightIndex);
                }
            }

            $lightMask = $initialLight;

            foreach (array_slice($segments, 1, count($segments)-2) as $segment){
                $buttonsSlice = array_map(fn(string $val) => (int)$val, explode(',', trim($segment,'()')));
                $buttonMask = 0;
                foreach ($buttonsSlice as $button) {
                    $buttonMask |= (1 << $button);
                }
                $buttonMasks[] = $buttonMask;
            }

            if($lightMask === 0){
                continue;
            }

            $maxState = 1 << $amountOfLights;
            $dist = array_fill(0, $maxState, -1);
            $queue = [];

            $start = 0;
            $dist[$start] = 0;
            $queue[] = $start;
            $head = 0;
            $localResult = null;

            while($head < count($queue)){
                $state = $queue[$head++];
                $d = $dist[$state];

                if ($state === $lightMask){
                    $localResult = $d;
                    break;
                }

                foreach ($buttonMasks as $buttonMask){
                    $nextState = $state ^ $buttonMask;
                    if($dist[$nextState] === -1){
                        $dist[$nextState] = $d + 1;
                        $queue[] = $nextState;
                    }
                }
            }
            $result += $localResult;
        }

        return $result;
    }

    public function p2(string $input): mixed
    {
        $data = explode("\n", trim($input));
        $result = 0;
        foreach ($data as $lineIndex => $line) {
            $line = trim($line);
            if ($line === '') {
                continue;
            }

            $segments = preg_split('/\s+/', $line);

            $dataJoltage = array_pop($segments);

            array_shift($segments);

            $targetJoltage = array_map('intval', explode(',', trim($dataJoltage, '{}')));
            $numCounters   = count($targetJoltage);

            $buttons = [];
            foreach ($segments as $button) {
                $btnIndices = array_filter(explode(',', trim($button, '()')), 'strlen');
                $vec = array_fill(0, $numCounters, 0);
                foreach ($btnIndices as $idx) {
                    $vec[(int)$idx] = 1;
                }
                $buttons[] = $vec;
            }

            $numButtons = count($buttons);
            if ($numButtons === 0) {
                if (array_sum($targetJoltage) !== 0) {
                    throw new \RuntimeException("No buttons but non-zero targets for line: $line");
                }
                continue;
            }

            $constraints = [];
            for ($i = 0; $i < $numCounters; $i++) {
                $row = [];
                for ($j = 0; $j < $numButtons; $j++) {
                    $row[] = $buttons[$j][$i];
                }
                $constraints[] = new Constraint($row, EQ, (float)$targetJoltage[$i]);
            }

            $objective = array_fill(0, $numButtons, 1.0);
            $intVars = array_fill(0, $numButtons, 1);
            $lowerBounds = array_fill(0, $numButtons, 0.0);
            $problem = new Problem(
                objective: $objective,
                constraints: $constraints,
                lowerBounds: $lowerBounds,
                integerVariables: $intVars
            );

            $solver   = new Solver(Solver::MIN);
            $solution = $solver->throw()->solve($problem);
            $result += (int) round($solution->getObjective());
            //Had to add 1 because of some weird rounding error somewhere
        }

        return $result;
    }
}
