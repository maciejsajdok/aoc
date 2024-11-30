<?php

namespace App\Services\Aoc;

interface SolutionInterface
{
    public function p1(string $input): mixed;
    public function p2(string $input): mixed;
}
