<?php

declare(strict_types=1);

namespace App\Aoc\Year2025;

use App\Services\Aoc\SolutionInterface;
use function array_shift;
use function explode;

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
}
