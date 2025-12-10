<?php

declare(strict_types=1);

namespace App\Aoc\Year2025;

use App\Services\Aoc\SolutionInterface;
use function array_fill;
use function array_map;
use function array_slice;
use function explode;
use function str_split;
use function strlen;

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
        return null;
    }
}
