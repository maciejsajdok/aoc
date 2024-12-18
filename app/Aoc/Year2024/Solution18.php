<?php

declare(strict_types=1);

namespace App\Aoc\Year2024;

use App\Services\Aoc\SolutionInterface;
use App\Utilities\Grid;
use SplQueue;
use function explode;
use function implode;

class Solution18 implements SolutionInterface
{
    public function p1(string $input): mixed
    {
        $bytesAmount = 1024;
        $bytes = [];
        foreach (explode("\n",$input) as $i => $dataSet) {
            $elements = explode(",",$dataSet);
            $bytes[(int)$elements[0]][(int)$elements[1]] = true;
            if ($i === $bytesAmount-1){
                break;
            }
        }

        $start = [0,0];
        $stop = [70,70];

        return $this->maze($start, $stop, $bytes, 70, 70);
    }

    public function p2(string $input): mixed
    {
        $bytes = [];
        $start = [0,0];
        $stop = [70,70];
        foreach (explode("\n",trim($input)) as $i => $dataSet) {
            $elements = explode(",",$dataSet);
            $bytes[(int)$elements[0]][(int)$elements[1]] = true;

            $result  = $this->maze($start, $stop, $bytes, 70, 70);
            if ($result === -1){
                return implode(',', $elements);
            }
        }
        return 'NOT FOUND';
    }

    private function maze(array $start, array $stop, array $bytes, int $w, int $h): int
    {

        $queue = new SplQueue();
        $queue->enqueue([$start, 0]);
        $visited = [];

        while (!$queue->isEmpty()) {
            $item = $queue->shift();

            [$x, $y] = $item[0];
            $steps = $item[1];
            if ($x === $stop[0] && $y === $stop[1]) {
                return $steps;
            }

            if (isset($visited[$x][$y])){
                continue;
            }

            $visited[$x][$y] = true;

            foreach (Grid::$straightAdjacencyMatrix as $adj){
                [$dx, $dy] = $adj;
                $nx = $x + $dx;
                $ny = $y + $dy;

                if (!isset($bytes[$nx][$ny]) && $nx >=0 && $ny >= 0 && $nx <= $w && $ny <= $h){
                    $queue->enqueue([[$nx, $ny], $steps+1]);
                }
            }

        }
        return -1;
    }
}
