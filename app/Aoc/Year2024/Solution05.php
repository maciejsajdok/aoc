<?php

declare(strict_types=1);

namespace App\Aoc\Year2024;

use App\Services\Aoc\SolutionInterface;
use function explode;
use function floor;
use function in_array;

class Solution05 implements SolutionInterface
{
    public function p1(string $input): mixed
    {
        [$pageOrderingData, $testData] = explode("\n\n", trim($input));

        $pageOrderAsc = [];
        $pageOrderDesc = [];
        foreach (explode("\n", trim($pageOrderingData)) as $pageOrdering) {
            [$p1, $p2] = explode('|', trim($pageOrdering));
            $pageOrderAsc[$p1][] = $p2;
            $pageOrderDesc[$p2][] = $p1;
        }

        $indexes = [];
        foreach (explode("\n", trim($testData)) as $testIndex => $test){
            $numbers = explode(",", trim($test));
            $isValid = true;
            for ($i = 0; $i < count($numbers); $i++) {
                $currNumber = $numbers[$i];
                for ($j = $i + 1; $j < count($numbers); $j++) {
                    if (
                        (isset($pageOrderAsc[$currNumber]) && !in_array($numbers[$j], $pageOrderAsc[$currNumber]))
                        || (isset($pageOrderDesc[$currNumber]) && in_array($numbers[$j], $pageOrderDesc[$currNumber]))
                    ) {
                        $isValid = false;
                    }
                }
            }

            if($isValid){
                $indexes[] = $testIndex;
            }
        }
        $result = 0;
        $testDataElements = explode("\n", trim($testData));
        foreach ($indexes as $index){
            $arr = explode(',',trim($testDataElements[$index]));
            $mid = (int)$arr[(int) floor(count($arr) / 2)];
            $result += $mid;
        }
        return $result;
    }

    public function p2(string $input): mixed
    {
        [$pageOrderingData, $testData] = explode("\n\n", trim($input));

        $pageOrderAsc = [];
        $pageOrderDesc = [];
        foreach (explode("\n", trim($pageOrderingData)) as $pageOrdering) {
            [$p1, $p2] = explode('|', trim($pageOrdering));
            $pageOrderAsc[$p1][] = $p2;
            $pageOrderDesc[$p2][] = $p1;
        }

        $fixedSets = [];
        foreach (explode("\n", trim($testData)) as $testIndex => $test){
            $numbers = explode(",", trim($test));
            $isValid = true;
            for ($i = 0; $i < count($numbers); $i++) {
                $currNumber = $numbers[$i];
                for ($j = $i + 1; $j < count($numbers); $j++) {
                    if (
                        (isset($pageOrderAsc[$currNumber]) && !in_array($numbers[$j], $pageOrderAsc[$currNumber]))
                        || (isset($pageOrderDesc[$currNumber]) && in_array($numbers[$j], $pageOrderDesc[$currNumber]))
                    ) {
                        $isValid = false;
                        break;
                    }
                }
            }

            $numbersCopy = $numbers;
            if(!$isValid) {
                for ($z = 0; $z < 10; $z++) {
                for ($j = 0; $j < count($numbersCopy) - 1; $j++) {
                    for ($k = $j; $k < count($numbersCopy) - 1; $k++) {
                        $n1 = $numbersCopy[$k];
                        $n2 = $numbersCopy[$k + 1];
                        if (
                            (isset($pageOrderAsc[$n1]) && in_array($n2, $pageOrderAsc[$n1]))
                            && (isset($pageOrderDesc[$n2]) && in_array($n1, $pageOrderDesc[$n2]))
                        ) {
                            continue;
                        }
                        $this->array_swap($numbersCopy, $k, $k + 1);
                    }
                }
            }
                $fixedSets[] = $numbersCopy;
            }
        }

        $result = 0;
        foreach ($fixedSets as $fixedSet){
            $mid = (int)$fixedSet[(int) floor(count($fixedSet) / 2)];
            $result += $mid;
        }
        return $result;
    }

    function array_swap(array &$array,int $swap_a,int $swap_b){
        list($array[$swap_a],$array[$swap_b]) = array($array[$swap_b],$array[$swap_a]);
    }
}
