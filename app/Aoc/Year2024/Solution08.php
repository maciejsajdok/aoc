<?php

declare(strict_types=1);

namespace App\Aoc\Year2024;

use App\Services\Aoc\SolutionInterface;
use Macocci7\PhpCombination\Combination;
use function array_keys;
use function count;
use function in_array;
use function str_split;
use function strlen;

class Solution08 implements SolutionInterface
{
    public function p1(string $input): mixed
    {
        $lines = explode("\n", $input);
        $antenasGroups = [];
        $nodes = [];
        $width = count($lines);
        $height = strlen($lines[0]);

        foreach ($lines as $y => $line) {
            $characters = str_split($line);
            foreach ($characters as $x => $char) {
                if ($char !== '.') {
                    if (!isset($antenasGroups[$char])) {
                        $antenasGroups[$char] = [[$x, $y]];
                    } else {
                        $antenasGroups[$char][] = [$x, $y];
                    }
                }
            }
        }

        $c = new Combination();
        foreach ($antenasGroups as $antenas) {
            $keys = array_keys($antenas);
            foreach ($c->pairs($keys) as [$pi1, $pi2]) {
                $a1 = $antenas[$pi1];
                $a2 = $antenas[$pi2];

                $dx = abs($a1[0] - $a2[0]);
                $dy = abs($a1[1] - $a2[1]);

                if ($a1[0] === $a2[0]) {
                    $offset = $a1[1] < $a2[1] ? -$dy : $dy;
                    $nodes[] = [$a1[0], $a1[1] + $offset];
                    $nodes[] = [$a2[0], $a2[1] - $offset];
                } elseif ($a1[1] === $a2[1]) {
                    $offset = $a1[0] < $a2[0] ? -$dx : $dx;
                    $nodes[] = [$a1[0] + $offset, $a1[1]];
                    $nodes[] = [$a2[0] - $offset, $a2[1]];
                } else {
                    $dxOffset = $a1[0] < $a2[0] ? -$dx : $dx;
                    $dyOffset = $a1[1] < $a2[1] ? -$dy : $dy;

                    $nodes[] = [$a1[0] + $dxOffset, $a1[1] + $dyOffset];
                    $nodes[] = [$a2[0] - $dxOffset, $a2[1] - $dyOffset];
                }

            }

        }
        $goodNodes = [];

        foreach ($nodes as $md) {
            if (!in_array($md, $goodNodes) && $md[0] >= 0 && $md[0] < $width && $md[1] >= 0 && $md[1] < $height) {
                $goodNodes[] = $md;
            }
        }

        return count($goodNodes);
    }

    public function p2(string $input): mixed
    {
        $lines = explode("\n", $input);
        $antenasGroups = [];
        $nodes = [];
        $width = count($lines);
        $height = strlen($lines[0]);

        foreach ($lines as $y => $line) {
            $characters = str_split($line);
            foreach ($characters as $x => $char) {
                if ($char !== '.') {
                    if (!isset($antenasGroups[$char])) {
                        $antenasGroups[$char] = [[$x, $y]];
                    } else {
                        $antenasGroups[$char][] = [$x, $y];
                    }
                }
            }
        }

        $c = new Combination();
        foreach ($antenasGroups as $antenas) {
            $keys = array_keys($antenas);
            foreach ($c->pairs($keys) as [$pi1, $pi2]) {
                $a1 = $antenas[$pi1];
                $a2 = $antenas[$pi2];

                $dx = abs($a1[0] - $a2[0]);
                $dy = abs($a1[1] - $a2[1]);
                $tmpdx = $dx;
                $tmpdy = $dy;
                if ($a1[0] === $a2[0]) {
                    while($tmpdy < ($height * 2)) {
                        $offset = $a1[1] < $a2[1] ? -$tmpdy : $tmpdy;
                        $nodes[] = [$a1[0], $a1[1] + $offset];
                        $nodes[] = [$a2[0], $a2[1] - $offset];
                        $tmpdy += $dy;
                    }
                } elseif ($a1[1] === $a2[1]) {
                    while($tmpdx < ($width * 2)) {
                        $offset = $a1[0] < $a2[0] ? -$tmpdx : $tmpdx;
                        $nodes[] = [$a1[0] + $offset, $a1[1]];
                        $nodes[] = [$a2[0] - $offset, $a2[1]];
                        $tmpdx += $dx;
                    }
                } else {
                    while($tmpdy < ($height * 2) && $tmpdx < ($width * 2)) {
                        $dxOffset = $a1[0] < $a2[0] ? -$tmpdx : $tmpdx;
                        $dyOffset = $a1[1] < $a2[1] ? -$tmpdy : $tmpdy;

                        $nodes[] = [$a1[0] + $dxOffset, $a1[1] + $dyOffset];
                        $nodes[] = [$a2[0] - $dxOffset, $a2[1] - $dyOffset];
                        $tmpdx += $dx;
                        $tmpdy += $dy;
                    }
                }

            }

        }
        $antinodes = [];

        foreach ($nodes as $md) {
            if (!in_array($md, $antinodes) && $md[0] >= 0 && $md[0] < $width && $md[1] >= 0 && $md[1] < $height) {
                $antinodes[] = $md;
            }
        }

        $mergedAntenaGroups = 0;

        foreach ($antenasGroups as $antenaGroup) {
            foreach ($antenaGroup as $antena) {
                if (!in_array($antena, $antinodes)){
                    $mergedAntenaGroups++;
                }
            }
        }

        return count($antinodes) + $mergedAntenaGroups;
    }
}
