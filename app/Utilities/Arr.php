<?php

declare(strict_types=1);

namespace App\Utilities;

use InvalidArgumentException;

class Arr extends \Illuminate\Support\Arr
{
    public static function arraySwap(array &$array, int $swap_a, int $swap_b): void
    {
        list($array[$swap_a], $array[$swap_b]) = array($array[$swap_b], $array[$swap_a]);
    }
    public static function arraySwapAssociative(array &$array, string $swap_a, string $swap_b): void
    {
        list($array[$swap_a], $array[$swap_b]) = array($array[$swap_b], $array[$swap_a]);
    }

    /**
     * @param int   $mode  Rotation mode: 1 (CW) or 2 (CCW).
     */
    public static function rotate2DArray(array &$array, int $mode, int $steps = 1): void
    {
        foreach ($array as $row) {
            if (!is_array($row)) {
                throw new InvalidArgumentException('rotate2DArray expects a 2D array (array of arrays).');
            }
        }

        if ($mode !== 1 && $mode !== 2) {
            throw new InvalidArgumentException('Invalid mode. Use 1 (CW) or 2 (CCW).');
        }

        $steps = $steps % 4;
        if ($steps < 0) {
            $steps += 4;
            $mode = ($mode === 1) ? 2 : 1;
        }

        if ($steps === 0 || $array === []) {
            return;
        }

        for ($i = 0; $i < $steps; $i++) {
            if ($mode === 1) {
                $array = array_map(null, ...array_reverse($array));
            } else {
                $array = array_reverse(array_map(null, ...$array));
            }
        }
    }
}
