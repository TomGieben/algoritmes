<?php

declare(strict_types=1);

namespace Algoritmes\Lists;

use Algoritmes\Lists\Interfaces\ListInterface;

class ArrayList implements ListInterface
{
    private array $items = [];
    private string $type;

    public function __construct(string $type)
    {
        $this->type = $type;
    }

    public function add(mixed $item): void
    {
        $this->validateType($item);
        $this->items[] = $item;
    }

    public function insert(int $index, mixed $item): void
    {
        $this->validateType($item);
        array_splice($this->items, $index, 0, [$item]);
    }

    public function get(int $index): mixed
    {
        if ($index < 0 || $index >= count($this->items)) {
            throw new \OutOfBoundsException("Index out of bounds");
        }
        return $this->items[$index];
    }

    public function set(int $index, mixed $item): void
    {
        if ($index < 0 || $index >= count($this->items)) {
            throw new \OutOfBoundsException("Index out of bounds");
        }
        $this->validateType($item);
        $this->items[$index] = $item;
    }

    public function removeAt(int $index): mixed
    {
        if ($index < 0 || $index >= count($this->items)) {
            throw new \OutOfBoundsException("Index out of bounds");
        }
        $item = $this->items[$index];
        array_splice($this->items, $index, 1);
        return $item;
    }

    public function clear(): void
    {
        $this->items = [];
    }

    public function isEmpty(): bool
    {
        return empty($this->items);
    }

    public function count(): int
    {
        return count($this->items);
    }

    public function getIterator(): \Traversable
    {
        foreach ($this->items as $item) {
            yield $item;
        }
    }

    private function validateType(mixed $item): void
    {
        $type = gettype($item);
        if ($type !== $this->type) {
            throw new \InvalidArgumentException("Item must be of type {$this->type}, {$type} given.");
        }
    }
}
