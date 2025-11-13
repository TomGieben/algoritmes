<?php

declare(strict_types=1);

namespace Algoritmes\Lists;

use Algoritmes\Lists\Interfaces\PriorityQueueInterface;

class PriorityQueue implements PriorityQueueInterface
{
    private array $items = [];

    public function enqueue(mixed $item, int $priority): void
    {
        if (!isset($this->items[$priority])) {
            $this->items[$priority] = [];
        }

        $this->items[$priority][] = $item;

        ksort($this->items);
    }

    public function dequeue(): mixed
    {
        if ($this->isEmpty()) {
            throw new \UnderflowException("Cannot dequeue from an empty priority queue");
        }

        $firstPriority = key($this->items);
        $item = array_shift($this->items[$firstPriority]);

        if (empty($this->items[$firstPriority])) {
            unset($this->items[$firstPriority]);
        }

        return $item;
    }

    public function isEmpty(): bool
    {
        return empty($this->items);
    }

    public function size(): int
    {
        $count = 0;
        foreach ($this->items as $priorityLevel) {
            $count += count($priorityLevel);
        }
        return $count;
    }

    public function peek(): mixed
    {
        if ($this->isEmpty()) {
            throw new \UnderflowException("Cannot peek into an empty priority queue");
        }

        $firstPriority = key($this->items);
        return $this->items[$firstPriority][0];
    }
}
