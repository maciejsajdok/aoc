<?php

declare(strict_types=1);

namespace App\Aoc\Year2024;

use App\Services\Aoc\SolutionInterface;
use App\Utilities\Grid;
use function array_search;
use function array_shift;
use function array_sum;
use function explode;
use function in_array;
use function str_split;

class Solution12 implements SolutionInterface
{
    public function p1(string $input): mixed
    {
        $visited = [];
        $grid = [];
        $lines = explode("\n", $input);

        foreach ($lines as $y => $line) {
            foreach (str_split(trim($line)) as $x => $cell) {
                $grid[$x][$y] = $cell;
            }
        }
        $results = [];
        foreach ($grid as $x => $row) {
            foreach ($row as $y => $cell) {
                if (in_array([$x,$y], $visited)){
                    continue;
                }
                $plants = 0;
                $perimeter = 0;
                $queue = [[$x,$y, $cell]];
                while(!empty($queue)){
                    [$qx, $qy, $qp] = array_shift($queue);
                    if (in_array([$qx,$qy], $visited)) {
                        continue;
                    }
                    $visited[] = [$qx,$qy];
                    $plants +=1;

                    foreach (Grid::$straightAdjacencyMatrix as $adj){
                        $nx = $qx + $adj[0];
                        $ny = $qy + $adj[1];

                        if (isset($grid[$nx][$ny])) {
                            if ($grid[$nx][$ny] === $qp) {
                                $queue[] = [$nx, $ny, $qp];
                            } else {
                                $perimeter +=1;
                            }
                        } else {
                            $perimeter +=1;
                        }
                    }
                }
                $results[] = $plants * $perimeter;
            }
        }

        return array_sum($results);
    }

    public function p2(string $input): mixed
    {
        $visited = [];
        $areaVisited = [];
        $grid = [];
        $lines = explode("\n", $input);

        foreach ($lines as $y => $line) {
            foreach (str_split(trim($line)) as $x => $cell) {
                $grid[$x][$y] = $cell;
            }
        }
        $results = [];
        foreach ($grid as $x => $row) {
            foreach ($row as $y => $cell) {
                if (in_array([$x,$y], $visited)){
                    continue;
                }
                $plants = 0;
                $fences = [];
                $sides = 0;
                $queue = [[$x,$y, $cell]];
                while(!empty($queue)){
                    [$qx, $qy, $qp] = array_shift($queue);
                    if (in_array([$qx,$qy], $areaVisited)) {
                        continue;
                    }
                    $visited[] = [$qx,$qy];
                    $areaVisited[] = [$qx,$qy];
                    $plants +=1;

                    foreach (Grid::$straightAdjacencyMatrix as $adj){
                        $nx = $qx + $adj[0];
                        $ny = $qy + $adj[1];

                        if (isset($grid[$nx][$ny]) && $grid[$nx][$ny] === $qp) {
                                $queue[] = [$nx, $ny, $qp];
                        } else {
                            $fences[] = $nx.'SEP'.$ny;
                        }
                    }
                }

                while(!empty($fences)){
                    $fence = array_shift($fences);
                    $els = explode('SEP', $fence);
                    $fx = (int) $els[0];
                    $fy = (int) $els[1];

                    $nfxl = $fx;
                    $nfxr = $fx;
                    while(true){
                        $nfxl -= 1;
                        $nfxr += 1;
                        if (in_array($nfxl.'SEP'.$fy, $fences)){
                            unset($fences[array_search($nfxl.'SEP'.$fy, $fences)]);
                        } else if (in_array($nfxr.'SEP'.$fy, $fences)){
                            unset($fences[array_search($nfxr.'SEP'.$fy, $fences)]);
                        } else {
                            break;
                        }
                    }


                    $nfyu = $fy;
                    $nfyd = $fy;
                    while(true){
                        $nfyu += 1;
                        $nfyd -= 1;

                        if (in_array($fx.'SEP'.$nfyu, $fences)){
                            unset($fences[array_search($fx.'SEP'.$nfyu, $fences)]);
                        } else if (in_array($fx.'SEP'.$nfyd, $fences)){
                            unset($fences[array_search($fx.'SEP'.$nfyd, $fences)]);
                        } else {
                            break;
                        }
                    }
                    $sides ++;
                }
                $results[] = $plants * $sides;
            }
        }

        return array_sum($results);
    }
}
