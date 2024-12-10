<?php

declare(strict_types=1);

namespace App\Utilities;

use ArrayAccess;
use Countable;
use Illuminate\Contracts\Support\Arrayable;
use Iterator;
use function explode;
use function is_array;

class Grid implements ArrayAccess, Arrayable, Countable, Iterator
{
    private array $container = [];
    private int $position = 0;

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
}
