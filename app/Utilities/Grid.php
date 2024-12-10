<?php

declare(strict_types=1);

namespace App\Utilities;

use ArrayAccess;
use Countable;
use Illuminate\Contracts\Support\Arrayable;
use Iterator;
use function array_merge;
use function explode;
use function is_array;

class Grid implements ArrayAccess, Arrayable, Countable, Iterator
{
    private array $container = [];
    private int $position = 0;

    public const DIAGONAL = 2;
    public const STRAIGHT = 1;

    public static array $straightAdjacencyMatrix = [
        [0, -1],
        [-1, 0], [1, 0],
        [0, 1]
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
    public function neigbours(int $x, int $y, ?callable $condition = null, int $flag = 1): array
    {
        $neighbours = [];
        if ($flag & self::STRAIGHT){
            $neighbours = array_merge($neighbours, self::$straightAdjacencyMatrix);
        }
        if ($flag & self::DIAGONAL){
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
}
