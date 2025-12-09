<?php

declare(strict_types=1);

namespace App\Aoc\Year2025;

use App\Services\Aoc\SolutionInterface;
use Geometry;
use geoPHP;
use function array_slice;
use function explode;
use function usort;

class Solution09 implements SolutionInterface
{
    public function p1(string $input): mixed
    {
        $data = explode("\n", trim($input));
        $rectangles = [];

        foreach ($data as $i => $coordinate1) {
            foreach (array_slice($data, $i, count($data)) as $coordinate2) {
                [$x1, $y1] = explode(',', $coordinate1);
                [$x2, $y2] = explode(',', $coordinate2);
                $width = abs((int)$x1 - (int)$x2) + 1;
                $height = abs((int)$y1 - (int)$y2) + 1;
                $area = $width * $height;
                $rectangles[$coordinate1.';'.$coordinate2] = $area;
            }
        }
        usort($rectangles, function ($a, $b) {
            return $b <=> $a;
        });

        return $rectangles[0];
    }

    public function p2(string $input): mixed
    {
        $data = explode("\n", trim($input));
        $redPoints = [];

        foreach ($data as $i => $coordinate1) {
            [$x, $y] = explode(',', $coordinate1);
            $redPoints[] = ['x' => $x, 'y' => $y];
        }

        $coords = [];
        foreach ($redPoints as $p) {
            $coords[] = "{$p['x']} {$p['y']}";
        }

        $coords[] = "{$redPoints[0]['x']} {$redPoints[0]['y']}";

        $wktPolygon = 'POLYGON((' . implode(',', $coords) . '))';

        /** @var Geometry $poly */
        $poly = geoPHP::load($wktPolygon, 'wkt');

        $maxArea = 0;
        $n = count($redPoints);
        for ($i = 0; $i < $n; $i++) {
            for ($j = $i + 1; $j < $n; $j++) {

                $x1 = $redPoints[$i]['x'];
                $y1 = $redPoints[$i]['y'];
                $x2 = $redPoints[$j]['x'];
                $y2 = $redPoints[$j]['y'];

                if ($x1 == $x2 || $y1 == $y2) {
                    continue;
                }

                $xmin = min($x1, $x2);
                $xmax = max($x1, $x2);
                $ymin = min($y1, $y2);
                $ymax = max($y1, $y2);

                $area = ($xmax - $xmin + 1) * ($ymax - $ymin + 1);
                if ($area <= $maxArea) continue;

                $rectWkt = sprintf(
                    'POLYGON((%f %f,%f %f,%f %f,%f %f,%f %f))',
                    $xmin, $ymin,
                    $xmax, $ymin,
                    $xmax, $ymax,
                    $xmin, $ymax,
                    $xmin, $ymin
                );

                $rect = geoPHP::load($rectWkt, 'wkt');

                $diff = $rect->difference($poly);
                if ($diff->isEmpty()) {
                    $maxArea = $area;
                }
            }
        }

        return $maxArea;
    }
}
