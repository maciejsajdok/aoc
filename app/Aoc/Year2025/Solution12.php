<?php

declare(strict_types=1);

namespace App\Aoc\Year2025;

use App\Services\Aoc\SolutionInterface;
use function array_pop;
use function array_shift;
use function explode;
use function str_split;

class Solution12 implements SolutionInterface
{
    public function p1(string $input): mixed
    {
        $regions = [];
        $gifts = [];
        $data = explode("\n\n", trim($input));
        $r = explode("\n", array_pop($data));
        foreach ($r as $line) {
            $segments = explode(' ', $line);
            [$width, $height] = explode('x', trim(array_shift($segments),':'));
            $regions[] = [
                (int) $width,
                (int) $height,
                array_map('intval', $segments)
            ];
        }
        $giftDensities = [];
        foreach($data as $line) {
            $segments = explode("\n", $line);
            array_shift($segments);
            $gift = array_map(fn(string $row) => str_split($row), $segments);
            $giftDensity = 0;
            foreach ($gift as $i => $giftRow) {
                foreach ($giftRow as $y => $cell){
                    if($cell === '#'){
                        $giftDensity += 1;
                    }
                }
            }
            $giftDensities[] = $giftDensity;
        }
        $result = 0;
        foreach ($regions as $region) {
            if($this->canFitRegion($region[0], $region[1], $region[2], $giftDensities)) {
                $result++;
            }
        }

        return $result;
    }

    public function p2(string $input): mixed
    {
        return null;
    }

    private function canFitRegion(int $width, int $height, array $giftTargets, array $giftDensities): bool
    {
        $area = $width * $height;
        $totalSumOfReservedBoxes = 0;

        foreach($giftTargets as $i => $giftTarget) {
            $totalSumOfReservedBoxes += $giftTarget * $giftDensities[$i];
        }

        if ($totalSumOfReservedBoxes < $area) {
            return true;
        }

        return false;
    }
}
