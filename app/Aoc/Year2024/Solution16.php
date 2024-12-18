<?php

declare(strict_types=1);

namespace App\Aoc\Year2024;

use App\Services\Aoc\SolutionInterface;
use SplPriorityQueue;
use function array_keys;
use function sprintf;
use const PHP_INT_MAX;

class Solution16 implements SolutionInterface
{
    private array $adj = [
        '>' => [1,0],
        '^' => [0,-1],
        '<' => [-1,0],
        'v' => [0,1],
    ];

    private array $possible = [
        '>' => ['^', 'v'],
        '^' => ['<','>'],
        '<' => ['v', '^'],
        'v' => ['>','<'],
    ];
    public function p1(string $input): mixed
    {
        $maze = new MazeSolver($input);
        return $maze->findLowestScore();

    }

    public function p2(string $input): mixed
    {
        $maze = new MazeSolver($input);
        return $maze->getBestTiles();
    }
}

class MazeSolver {
    private array $maze;
    private array $start;
    private array $end;
    private array $directions = [
        'N' => [-1, 0],
        'E' => [0, 1],
        'S' => [1, 0],
        'W' => [0, -1]
    ];
    private int $turnCost = 1000;

    private array $bestPathCoordinates = [];

    public function __construct($input) {
        $this->maze = array_map('str_split', explode("\n", trim($input)));
        $this->findStartAndEnd();
    }

    private function findStartAndEnd(): void
    {
        foreach ($this->maze as $row => $line) {
            foreach ($line as $col => $cell) {
                if ($cell === 'S') {
                    $this->start = [$row, $col, 'E'];
                } elseif ($cell === 'E') {
                    $this->end = [$row, $col];
                }
            }
        }
    }

    public function findLowestScore() {
        $priorityQueue = new SplPriorityQueue();
        $startState = [$this->start[0], $this->start[1], $this->start[2], 0];
        $priorityQueue->insert($startState, 0);
        $visited = [];

        while (!$priorityQueue->isEmpty()) {
            [$x, $y, $direction, $score] = $priorityQueue->extract();

            if (isset($visited[sprintf('%s,%s,%s',$x,$y,$direction)])) {
                continue;
            }
            $visited[sprintf('%s,%s,%s',$x,$y,$direction)] = true;

            if ($x === $this->end[0] && $y === $this->end[1]) {
                return $score;
            }

            [$dx, $dy] = $this->directions[$direction];
            $nx = $x + $dx;
            $ny = $y + $dy;
            if ($this->isWalkable($nx, $ny)) {
                $priorityQueue->insert([$nx, $ny, $direction, $score + 1], -($score + 1));
            }

            foreach ($this->getTurns($direction) as $newDirection => $turnCost) {
                $priorityQueue->insert([$x, $y, $newDirection, $score + $turnCost], -($score + $turnCost));
            }
        }

        return -1;
    }

    public function getBestTiles(): int
    {
        $priorityQueue = new SplPriorityQueue();
        $priorityQueue->setExtractFlags(SplPriorityQueue::EXTR_BOTH);
        $startState = [$this->start[0], $this->start[1], $this->start[2], []];
        $priorityQueue->insert($startState, 0);
        $visited = [];
        $tmpMaze = $this->maze;
        $globalScore = PHP_INT_MAX;
        while (!$priorityQueue->isEmpty()) {

            $item = $priorityQueue->extract();
            [$x, $y, $direction, $path] = $item['data'];
            $score = -$item['priority'];

            $path[] = [$x, $y];

            $visited[sprintf('%s,%s,%s', $x, $y, $direction)] = true;

            if ($x === $this->end[0] && $y === $this->end[1]) {
                if ($score > $globalScore) {
                    break;
                }

                $globalScore = $score;

                foreach ($path as $coordinate) {
                    $tmpMaze[$coordinate[0]][$coordinate[1]] = 'O';
                }
                continue;
            }

            foreach ($this->getTurns($direction) as $newDirectionName => $scoreForMovement) {
                [$dx, $dy] = $this->directions[$newDirectionName];
                $nx = $x + $dx;
                $ny = $y + $dy;

                if (!$this->isWalkable($nx, $ny)) {
                    continue;
                }

                if (isset($visited[sprintf('%s,%s,%s', $nx, $ny, $newDirectionName)])){
                    continue;
                }

                $priorityQueue->insert([$nx, $ny, $newDirectionName, $path], -($score + $scoreForMovement));
            }
        }

        $tiles = 0;
        foreach ($tmpMaze as $x => $row) {
            foreach ($row as $y => $cell) {
                if ($cell === 'O') {
                    $tiles += 1;
                }
            }
        }
        return $tiles;
    }

    private function isWalkable($x, $y): bool
    {
        return isset($this->maze[$x][$y]) && $this->maze[$x][$y] !== '#';
    }

    private function getTurns($currentDirection): array
    {
        $directions = array_keys($this->directions);
        $index = array_search($currentDirection, $directions);

        return [
            $currentDirection => 1,
            $directions[($index + 1) % 4] => $this->turnCost,
            $directions[($index + 3) % 4] => $this->turnCost
        ];
    }

    public function getSuccessCoordsAmount(): ?int
    {
        return count($this->bestPathCoordinates);
    }
}
