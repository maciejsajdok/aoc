<?php

declare(strict_types=1);

namespace App\Aoc\Year2025;

use App\Services\Aoc\SolutionInterface;
use App\Utilities\Memoize;
use function array_merge;
use function array_unique;
use function explode;
use function str_split;

class Solution07 implements SolutionInterface
{
    public function p1(string $input): mixed
    {
        $grid = [];
        $result = 0;
        $startRow = $startCol = 0;
        $rows = explode("\n", trim($input));
        foreach ($rows as $row) {
            $grid[] = str_split($row);
        }
        foreach ($grid as $i => $row) {
            foreach ($grid[$i] as $j => $cell) {
                if ($cell === 'S') {
                    [$startRow, $startCol] = [$i, $j];
                }
            }
        }
        $beamsIncomingColumns = [$startCol];
        $currentRow = $startRow;
        while($currentRow < count($rows)) {
            $currentRow += 2;
            if($currentRow >= count($rows)){
                break;
            }
            $oldBeamsIncomingColumns = $beamsIncomingColumns;
            $newBeamsIncomingColumns = [];
            $splitAmount = 0;
            foreach ($grid[$currentRow] as $col => $cell) {
                if($cell === '^') {
                    foreach ($beamsIncomingColumns as $beamIncomingColumn) {
                        if($beamIncomingColumn === $col) {
                            if (
                                ($key = array_search(
                                    $col,
                                    $oldBeamsIncomingColumns
                                )) !== false) {
                                unset($oldBeamsIncomingColumns[$key]);
                            }
                            $splitAmount++;
                            $newBeamsIncomingColumns[] = $col-1;
                            $newBeamsIncomingColumns[] = $col+1;
                        }
                    }
                }
            }
            $newBeamsIncomingColumns = array_unique(
                array_merge(
                    $newBeamsIncomingColumns,
                    $oldBeamsIncomingColumns
                )
            );
            $beamsIncomingColumns = $newBeamsIncomingColumns;
            $result += $splitAmount;
        }

        return $result;
    }

    public function p2(string $input): mixed
    {
        $grid = [];
        $startRow = $startCol = 0;
        $rows = explode("\n", trim($input));
        foreach ($rows as $row) {
            $grid[] = str_split($row);
        }
        foreach ($grid as $i => $row) {
            foreach ($grid[$i] as $j => $cell) {
                if ($cell === 'S') {
                    [$startRow, $startCol] = [$i, $j];
                }
            }
        }

        $solver = Memoize::make(function(int $row, int $col) use (&$solver, $grid){
           $newRow = $row + 1;
           if($newRow >= count($grid) || $col <= 0 || $col >= count($grid[0])-1) {
                return 1;
           }

           $splits = 0;
               if($grid[$newRow][$col] === '^') {
                   if($grid[$newRow][$col-1] ?? '' === '.') {
                       $splits += $solver($newRow, $col - 1);
                   }
                   if($grid[$newRow][$col+1] ?? '' === '.') {
                       $splits += $solver($newRow, $col + 1);
                   }
               } else {
                    $splits += $solver($newRow, $col);
               }

           return $splits;
        });

        return $solver($startRow, $startCol);
    }
}
