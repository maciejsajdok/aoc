<?php

declare(strict_types=1);

namespace App\Aoc\Year2016;

use App\Services\Aoc\SolutionInterface;
use function array_keys;
use function array_last;
use function array_map;
use function array_pop;
use function array_slice;
use function chr;
use function explode;
use function implode;
use function ksort;
use function str_contains;
use function str_replace;
use function str_split;
use function strlen;
use function strtolower;
use function substr;
use function trim;

class Solution04 implements SolutionInterface
{
    public function p1(string $input): mixed
    {
        $data = $input
            |> trim(...)
            |> (fn (string $s) => explode("\n", $s))
            |> (fn (array $lines) => array_map(function (string $line): array {
                [$numbers, $checksum] = explode('[', $line);
                $checksum = substr($checksum, 0, -1);
                $numbersSeg = explode('-', $numbers);
                $id = array_pop($numbersSeg);
                $values = array_count_values(str_split(implode('', $numbersSeg)));

                uksort($values, function ($k1, $k2) use (&$values) {
                    $v1 = $values[$k1];
                    $v2 = $values[$k2];
                    if ($v1 !== $v2) {
                        return $v2 <=> $v1;
                    }
                    return $k1 <=> $k2;
                });

                return [
                    $values,
                    $id,
                    $checksum
                ];
                }, $lines));

        $result = 0;
        foreach ($data as $room){
            $values = $room[0];
            $id = (int)$room[1];
            $checksum = $room[2];
            $firstFive = implode('', array_slice(array_keys($values), 0, strlen($checksum)));
            if($firstFive === $checksum){
                $result += $id;
            }
        }

        return $result;
    }

    public function p2(string $input): mixed
    {
        $data = $input
                |> trim(...)
                |> (fn (string $s) => explode("\n", $s))
                |> (fn (array $lines) => array_map(function (string $line): array {
                    [$numbers] = explode('[', $line);

                    $seg = explode('-', $numbers);
                    $id = array_pop($seg);
                    $name = str_replace('-', ' ', implode('',$seg));

                    return [
                        $name,
                        (int) $id
                    ];
                }, $lines));

        foreach ($data as $room){
            [$name, $sector] = $room;
            $newString = '';
            for($i = 0; $i < strlen($name); $i++){
                if($name[$i] === ' '){
                    $newString .= $name[$i];
                }
                $newString .= $this->cesar($name[$i], $sector);
            }
            if(str_contains(strtolower($newString), 'north')){
                return $sector;
            }
        }

    }

    private function cesar(string $char, int $steps): string
    {
        $ascii = ord($char);
        for($j=0;$j<$steps;$j++){
            if($ascii == 90) {
                $ascii = 65;
            }
            else if($ascii == 122) {
                $ascii = 97;
            }
            else {
                $ascii++;
            }
        }
        return chr($ascii);
    }
}
