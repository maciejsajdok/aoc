<?php

declare(strict_types=1);

namespace App\Aoc\Year2024;

use App\Services\Aoc\SolutionInterface;
use function array_reverse;
use function count;
use function str_split;

class Solution09 implements SolutionInterface
{
    public function p1(string $input): mixed
    {
        $digits = str_split($input);
        $segments = [];
        $currIndex = 0;
        $fileIndex = 0;
        foreach ($digits as $i => $digit) {
            for ($j = 0; $j < $digit; $j++) {
                if ($i % 2 !== 0) {
                    $segments[$currIndex++] = '.';
                } else {
                    $segments[$currIndex++] = $fileIndex;
                }
            }
            if ($i % 2 !== 0) {
                $fileIndex++;
            }
        }
        $leftmostFreeSpaceIndex = 0;
        $rightmostFile = count($segments) - 1;

        while (true) {
            while ($segments[$leftmostFreeSpaceIndex] !== '.') {
                $leftmostFreeSpaceIndex++;
            }
            while ($segments[$rightmostFile] === '.') {
                $rightmostFile--;
            }
            if ($leftmostFreeSpaceIndex > $rightmostFile) {
                break;
            }

            try {
                $this->array_swap($segments, $leftmostFreeSpaceIndex, $rightmostFile);
            } catch (\Exception $e) {
                break;
            }
        }

        $result = 0;

        foreach ($segments as $i => $segment) {
            if ($segment !== '.') {
                $result += $i * $segment;
            }
        }

        return $result;
    }

    public function p2(string $input): mixed
    {
        $digits = str_split($input);
        $blocks = [];
        $files = [];
        $freeSegments = [];
        foreach ($digits as $i => $digit) {
            $type = $i % 2;
            $blocks[] = [$type, (int)$digit, $type === 0 ? $i/2 : null];
            if ($type === 0) {
                $files[] = (int)$digit;
            } else {
                $freeSegments[] = (int)$digit;
            }
        }

        $reverseFiles = array_reverse($files);

        $amountOfFiles = count($files) - 1;
        foreach ($reverseFiles as $fileIndex => $file) {
            $fsi = null;
            foreach ($freeSegments as $freeSegmentIndex => $freeSegment) {
                if ($file <= $freeSegment) {
                    $fsi = $freeSegmentIndex;
                    break;
                }
            }

            if ($fsi === null) {
                continue;
            }

            $remainingSpace = $freeSegments[$fsi] - $file;
            if ($remainingSpace === 0) {
                $blocksToInsertBeforeFreeSpace[($fsi * 2) + 1][] = [$amountOfFiles - $fileIndex, $file];
                $blocks[($fsi * 2) + 1][1] = $remainingSpace;
                $blocks[($amountOfFiles - $fileIndex) * 2] = [
                    1,$file, null
                ];
                unset($freeSegments[$fsi]);
            } else if ($remainingSpace > 0) {
                $freeSegments[$fsi] = $remainingSpace;
                $blocksToInsertBeforeFreeSpace[($fsi * 2) + 1][] = [$amountOfFiles - $fileIndex, $file];
                $blocks[($fsi * 2) + 1][1] = $remainingSpace;
                $blocks[($amountOfFiles - $fileIndex) * 2] = [
                    1,$file, null
                ];
            }

        }
        $expanded = '';
        foreach ($blocks as $blockIndex => $block) {
            if ($block[0] === 1) {
                if (isset($blocksToInsertBeforeFreeSpace[$blockIndex])) {
                    foreach ($blocksToInsertBeforeFreeSpace[$blockIndex] as $preBlock){
                        for($i = 0 ; $i < $preBlock[1]; $i++){
                            $expanded .= $preBlock[0];
                        }
                    }
                }
                for ($i = 0; $i < $block[1]; $i++) {
                    $expanded .= '.';
                }
            } else {
                for ($i = 0; $i < $block[1]; $i++) {
                    $expanded .= $block[2];
                }
            }
        }

        $segments = str_split($expanded);
        $result = 0;

        foreach ($segments as $i => $segment) {
            if ($segment !== '.') {
                $result += $i * (int) $segment;
            }
        }

        return $result;
    }

    function array_swap(array &$array, int $swap_a, int $swap_b)
    {
        list($array[$swap_a], $array[$swap_b]) = array($array[$swap_b], $array[$swap_a]);
    }
}
