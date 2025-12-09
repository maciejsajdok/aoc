<?php

declare(strict_types=1);

namespace App\Aoc\Year2025;

use App\Services\Aoc\SolutionInterface;
use function array_keys;
use function array_slice;
use function array_unique;
use function explode;
use function in_array;
use function sqrt;

class Solution08 implements SolutionInterface
{
    public function p1(string $input): mixed
    {
        $data = explode("\n", trim($input));
        $pairs = [];
        $circuits = [];
        foreach ($data as $line) {
            $circuits[] = [$line];
        }
        foreach ($data as $index => $coordinates1) {
            foreach (array_slice($data, $index, count($data)) as $coordinates2) {
                if ($coordinates1 === $coordinates2) {
                    continue;
                }
                $a = explode(',', $coordinates1);
                $b = explode(',', $coordinates2);
                $length = $this->euclideanDistance3D($a, $b);
                $pairs[$coordinates1 . ';' . $coordinates2] = $length;
            }
        }

        uasort($pairs, function ($a, $b) {
            return $a <=> $b;
        });

        $pairsToHandle = [];
        $arrayKeys = array_keys($pairs);
        for($i = 0; $i < 1000; ++$i) {
            $pairsToHandle[] = $arrayKeys[$i];
        }
        foreach ($pairsToHandle as $pair) {
            [$left, $right] = explode(';',$pair);
            $new = true;
            foreach ($circuits as $key => $circuit) {
                if(in_array($left, $circuit)) {
                    $new = false;
                    $circuits[$key][] = $right;
                } elseif (in_array($right, $circuit)) {
                    $new = false;
                    $circuits[$key][] = $left;
                }
            }
            if($new) {
                $circuits[] = [$left, $right];
            }
        }

        $mergedCircuits = $this->mergeOverlappingCircuits($circuits);

        usort($mergedCircuits, function ($a, $b) {
            return count($b) <=> count($a);
        });

        return count($mergedCircuits[0]) * count($mergedCircuits[1]) * count($mergedCircuits[2]);
    }

    public function p2(string $input): mixed
    {
        $data = explode("\n", trim($input));
        $pairs = [];
        foreach ($data as $index => $coordinates1) {
            foreach (array_slice($data, $index, count($data)) as $coordinates2) {
                if ($coordinates1 === $coordinates2) {
                    continue;
                }
                $a = explode(',', $coordinates1);
                $b = explode(',', $coordinates2);
                $length = $this->euclideanDistance3D($a, $b);
                $pairs[$coordinates1 . ';' . $coordinates2] = $length;
            }
        }

        uasort($pairs, function ($a, $b) {
            return $a <=> $b;
        });

        $parent = [];
        foreach ($data as $coord) {
            $parent[$coord] = $coord;
        }
        $components = count($data);

        $find = function (string $x) use (&$parent, &$find): string {
            if ($parent[$x] !== $x) {
                $parent[$x] = $find($parent[$x]);
            }
            return $parent[$x];
        };

        $union = function (string $a, string $b) use (&$parent, &$components, $find): bool {
            $ra = $find($a);
            $rb = $find($b);

            if ($ra === $rb) {
                return false;
            }

            $parent[$rb] = $ra;
            $components--;
            return true;
        };

        foreach (array_keys($pairs) as $pairKey) {
            [$left, $right] = explode(';', $pairKey);

            $merged = $union($left, $right);
            if (!$merged) {
                continue;
            }

            if ($components === 1) {
                [$x1] = explode(',', $left);
                [$x2] = explode(',', $right);

                return (int)$x1 * (int)$x2;
            }
        }

        return null;
    }

    private function euclideanDistance3D(array $p1, array $p2): float
    {
        $x1 = (int)$p1[0];
        $y1 = (int)$p1[1];
        $z1 = (int)$p1[2];
        $x2 = (int)$p2[0];
        $y2 = (int)$p2[1];
        $z2 = (int)$p2[2];

        $d = sqrt(pow($x2 - $x1, 2) +
            pow($y2 - $y1, 2) +
            pow($z2 - $z1, 2) * 1.0);

        return $d;
    }

    private function mergeOverlappingCircuits(array $circuits): array
    {
        $changed = true;

        while ($changed) {
            $changed = false;
            $merged  = [];

            foreach ($circuits as $circuit) {
                $circuit = array_values(array_unique($circuit));
                $mergedInto = null;

                foreach ($merged as $idx => $existing) {
                    if (count(array_intersect($existing, $circuit)) > 0) {
                        $merged[$idx] = array_values(array_unique(array_merge($existing, $circuit)));
                        $mergedInto = $idx;
                        $changed = true;
                        break;
                    }
                }

                if ($mergedInto === null) {
                    $merged[] = $circuit;
                }
            }

            $circuits = $merged;
        }

        return $circuits;
    }
}
