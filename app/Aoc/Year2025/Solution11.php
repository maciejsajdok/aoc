<?php

declare(strict_types=1);

namespace App\Aoc\Year2025;

use App\Services\Aoc\SolutionInterface;
use App\Utilities\Memoize;
use function array_shift;
use function explode;
use function in_array;

class Solution11 implements SolutionInterface
{
    public function p1(string $input): mixed
    {
        $data = explode("\n", trim($input));
        $servers = [];

        foreach ($data as $line) {
            $segments = explode(" ", $line);
            $source = trim($segments[0], ':');
            array_shift($segments);
            $servers[$source] = $segments;
        }

        $start = 'you';
        $queue = [
            [
                $start,
                []
            ]
        ];
        $paths = [];
        $head = 0;
        while($head < count($queue)){
            $queueItem = $queue[$head++];
            $nextNode = $queueItem[0];
            $pathSoFar = $queueItem[1];
            $pathSoFar[] = $nextNode;
            if($nextNode === 'out'){
                $paths[] = $pathSoFar;
                continue;
            }

            foreach ($servers[$nextNode] as $target) {
                $queue[] = [
                    $target, $pathSoFar
                ];
            }
        }

        return count($paths);
    }

    public function p2(string $input): mixed
    {
        $data = explode("\n", trim($input));
        $servers = [];

        foreach ($data as $line) {
            $segments = explode(" ", $line);
            $source = trim($segments[0], ':');
            array_shift($segments);
            $servers[$source] = $segments;
        }

        $solver = Memoize::make(function (string $node, bool $dac, bool $fft) use (&$solver, $servers){
           if($node === 'out'){
               if ($dac && $fft){
                   return 1;
               } else {
                   return 0;
               }
           }

           $pathsCount = 0;
           foreach ($servers[$node] as $target) {
               $pathsCount += $solver($target, $dac || $target==='dac', $fft || $target==='fft');
           }

           return $pathsCount;
        });

        return $solver('svr', false, false);
    }
}
