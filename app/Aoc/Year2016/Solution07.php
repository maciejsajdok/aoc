<?php

declare(strict_types=1);

namespace App\Aoc\Year2016;

use App\Services\Aoc\SolutionInterface;
use App\Utilities\Arr;
use App\Utilities\Text;
use function array_count_values;
use function array_map;
use function explode;
use function in_array;
use function str_contains;
use function str_split;
use function substr;

class Solution07 implements SolutionInterface
{
    public function p1(string $input): mixed
    {
        $data = explode("\n", trim($input));
        $result = 0;
        foreach ($data as $line) {
            $segments = Text::explode($line, '[]');
            $notHypernets = Arr::everyNth($segments, 2);
            $hypernets = Arr::everyNth($segments, 2, 1);

            $setOne = array_map(fn ($segment) => $this->hasAbba($segment), $notHypernets);
            $setTwo = array_map(fn ($segment) => $this->hasAbba($segment), $hypernets);

            if (
                in_array(true, $setOne) &&
                !in_array(true, $setTwo)
            ){
                $result++;
            }
        }

        return $result;
    }

    public function p2(string $input): mixed
    {
        $data = explode("\n", trim($input));
        $result = 0;
        foreach ($data as $line) {
            $segments = Text::explode($line, '[]');
            $notHypernets = Arr::everyNth($segments, 2);
            $hypernets = Arr::everyNth($segments, 2, 1);

            foreach ($notHypernets as $notHypernet) {
                $abas = $this->getAbaList($notHypernet);
                $found = false;
                if(!empty($abas)){
                    foreach ($hypernets as $hypernet) {
                        foreach ($abas as $aba) {
                            $corresponding = $aba[1].$aba[0].$aba[1];
                            if (str_contains($hypernet, $corresponding)) {
                                $found = true;
                            }
                        }
                    }
                }
                if ($found) {
                    $result++;
                    break;
                }
            }
        }

        return $result;
    }

    private function hasAbba(string $input): bool
    {
        for($i = 0; $i < strlen($input)-3; $i++) {
            $one = substr($input, $i, 2);
            $two = strrev(substr($input, $i + 2, 2));
            if ($one == $two && count(array_count_values(str_split($one)))>1) {
                return true;
            }
        }
        return false;
    }

    private function getAbaList(string $input): array
    {
        $aba = [];
        for($i = 0; $i < strlen($input)-2; $i++) {
            $one = substr($input, $i, 2);
            $two = strrev(substr($input, $i + 1, 2));
            if ($one == $two && count(array_count_values(str_split($one)))>1) {
                $aba[] =
                    $input[$i].
                    $input[$i + 1].
                    $input[$i + 2];
            }
        }
        return $aba;
    }
}
