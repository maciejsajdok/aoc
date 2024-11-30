<?php

declare(strict_types=1);

namespace App\Aoc\Year2015;

use App\Services\Aoc\SolutionInterface;
use Illuminate\Support\Str;
use function md5;
use function trim;

class Solution04 implements SolutionInterface
{
    public function p1(string $input): mixed
    {
        $cleanInput = trim($input);
        for ($i = 0; $i < 1000000000; ++$i) {
            $hash = md5($cleanInput.$i);
            if (Str::startsWith($hash,'00000'))
            {
                return $i;
            }
        }
        return null;
    }

    public function p2(string $input): mixed
    {
        $cleanInput = trim($input);
        for ($i = 0; $i < 1000000000; ++$i) {
            $hash = md5($cleanInput.$i);
            if (Str::startsWith($hash,'000000'))
            {
                return $i;
            }
        }
        return null;
    }
}
