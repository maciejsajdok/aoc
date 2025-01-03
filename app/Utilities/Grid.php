<?php

declare(strict_types=1);

namespace App\Utilities;

use ArrayAccess;
use Countable;
use Illuminate\Contracts\Support\Arrayable;
use Iterator;
use function array_merge;
use function explode;
use function in_array;
use function is_array;
use const STR_PAD_LEFT;

class Grid implements ArrayAccess, Arrayable, Countable, Iterator
{
    private array $container = [];
    private int $position = 0;

    public const DIAGONAL = 2;
    public const STRAIGHT = 1;

    public const DIAGONAL_AND_STRAIGHT = 3;

    public static array $straightAdjacencyMatrix = [
        'u' => [0, -1],
        'l' => [-1, 0], 'r' => [1, 0],
        'd' => [0, 1]
    ];

    public static array $diagonalAdjacencyMatrix = [
        [-1, -1], [1, -1],
        [-1, 1], [1, 1],
    ];

    public function __construct(array $data)
    {
        foreach ($data as $key => $value) {
            $this->container[$key] = is_array($value) ? new static($value) : $value;
        }
    }

    public static function fromInput(string $input, ?callable $callback = null): Grid
    {
        $grid = [];

        foreach (explode("\n", $input) as $y => $line) {
            foreach (str_split($line) as $x => $char) {
                $grid[$x][$y] = $callback !== null ? $callback($x, $y, $char) : $char;
            }
        }

        return new Grid($grid);
    }

    public function offsetExists(mixed $offset): bool
    {
        return isset($this->container[$offset]);
    }

    public function offsetGet(mixed $offset): mixed
    {
        return $this->container[$offset];
    }

    public function offsetSet(mixed $offset, mixed $value): void
    {
        if (is_null($offset)) {
            $this->container[] = is_array($value) ? new static($value) : $value;
        } else {
            $this->container[$offset] = is_array($value) ? new static($value) : $value;
        }
    }

    public function offsetUnset(mixed $offset): void
    {
        unset($this->container[$offset]);
    }

    public function toArray(): array
    {
        $result = [];
        foreach ($this->container as $key => $value) {
            $result[$key] = $value instanceof self ? $value->toArray() : $value;
        }

        return $result;
    }

    public function count(): int
    {
        return count($this->container);
    }

    public function current(): mixed
    {
        return $this->container[array_keys($this->container)[$this->position]];
    }

    public function key(): mixed
    {
        return array_keys($this->container)[$this->position];
    }

    public function next(): void
    {
        ++$this->position;
    }

    public function rewind(): void
    {
        $this->position = 0;
    }

    public function valid(): bool
    {
        return $this->position < count($this->container);
    }

    /**
     * @param int $x
     * @param int $y
     * @param null|callable(int $x1, int $y1, int $x2, int $y2, mixed $v1, mixed $v2, Grid $grid):bool $condition
     * @param int $flag
     * @return array
     */
    public function neighbours(int $x, int $y, ?callable $condition = null, int $flag = 1): array
    {
        $neighbours = [];
        if ($flag & self::STRAIGHT) {
            $neighbours = array_merge($neighbours, self::$straightAdjacencyMatrix);
        }
        if ($flag & self::DIAGONAL) {
            $neighbours = array_merge($neighbours, self::$diagonalAdjacencyMatrix);
        }
        $results = [];

        foreach ($neighbours as $neighbour) {
            $nx = $x + $neighbour[0];
            $ny = $y + $neighbour[1];
            if ($nx >= 0 && $ny >= 0 && $nx < count($this->container[0]) && $ny < count($this->container)) {
                $conditionResult = !($condition !== null) || $condition($x, $y, $nx, $ny, $this->container[$x][$y], $this->container[$nx][$ny], $this);
                if ($conditionResult) {
                    $results[] = [$nx, $ny];
                }
            }
        }

        return $results;
    }

    public static function prettyPrintGrid(array $grid, array $fieldsToMark = []): void
    {
        $width = count($grid);
        $height = count($grid[0]);

        echo str_pad(" ", 8);
        for ($y = 0; $y < $height; $y++) {
            echo str_pad((string)$y, 2);
        }
        echo PHP_EOL;
        echo str_repeat("-", ($height + 1) * 6) . PHP_EOL;

        for ($x = 0; $x < $width; $x++) {
            echo str_pad((string)$x, 6) . "|";
            for ($y = 0; $y < $height; $y++) {
                $shouldBeMarked = in_array([$x, $y], $fieldsToMark);
                echo str_pad(($shouldBeMarked ? "\033[31m " : "\033[0m") . (string)$grid[$x][$y], 6, " ", STR_PAD_LEFT);
            }
            echo PHP_EOL;
        }
    }

    public function pretty(array $fieldsToMark = [])
    {
        self::prettyPrintGrid($this->container, $fieldsToMark);
    }

    public static function prettyArray(array $grid, ?array $spot = null)
    {
        $width = count($grid);
        $height = count($grid[0]);
        for ($y = 0; $y < $height; $y++) {
            for ($x = 0; $x < $width; $x++) {
                if ($spot !== null && $x === $spot[0] && $y === $spot[1]) {
                    echo str_pad("X", 2);
                } else {
                    echo str_pad($grid[$x][$y], 2);
                }
            }
            echo "\n";
        }
    }

    public static function prettyPrintIncompleteGrid(int $width, int $height, array $markedPositions): void
    {
        for ($i = 0; $i < $height; $i++) {
            for ($j = 0; $j < $width; $j++) {
                if (in_array([$i, $j], $markedPositions)) {
                    echo "X ";
                } else {
                    echo ". ";
                }
            }
            echo PHP_EOL;
        }
    }
}
