<?php

declare(strict_types=1);

namespace Algoritmes\Lists;

use Algoritmes\Lists\Interfaces\PriorityQueueInterface;

class PriorityQueue implements PriorityQueueInterface
{
    private array $heap = [];
    private int $count = 0;
    private int $sequence = 0;

    public function enqueue(mixed $item, float $priority): void
    {
        $entry = [
            'priority' => $priority,
            'sequence' => $this->sequence++,
            'value' => $item,
        ];

        $this->heap[$this->count] = $entry;
        $this->siftUp($this->count);
        $this->count++;
    }

    public function dequeue(): mixed
    {
        if ($this->isEmpty()) {
            throw new \UnderflowException('Cannot dequeue from an empty priority queue');
        }

        $min = $this->heap[0];
        $lastIndex = $this->count - 1;
        $this->count--;

        if ($lastIndex > 0) {
            $this->heap[0] = $this->heap[$lastIndex];
            unset($this->heap[$lastIndex]);
            $this->siftDown(0);
        } else {
            $this->heap = [];
        }

        return $min['value'];
    }

    public function isEmpty(): bool
    {
        return $this->count === 0;
    }

    public function size(): int
    {
        return $this->count;
    }

    public function peek(): mixed
    {
        if ($this->isEmpty()) {
            throw new \UnderflowException('Cannot peek into an empty priority queue');
        }

        return $this->heap[0]['value'];
    }

    private function siftUp(int $index): void
    {
        while ($index > 0) {
            $parent = (int)(($index - 1) / 2);
            if ($this->compare($this->heap[$index], $this->heap[$parent]) >= 0) {
                break;
            }

            $this->swap($index, $parent);
            $index = $parent;
        }
    }

    private function siftDown(int $index): void
    {
        while (true) {
            $left = (2 * $index) + 1;
            $right = $left + 1;
            $smallest = $index;

            if ($left < $this->count && $this->compare($this->heap[$left], $this->heap[$smallest]) < 0) {
                $smallest = $left;
            }

            if ($right < $this->count && $this->compare($this->heap[$right], $this->heap[$smallest]) < 0) {
                $smallest = $right;
            }

            if ($smallest === $index) {
                break;
            }

            $this->swap($index, $smallest);
            $index = $smallest;
        }
    }

    private function compare(array $a, array $b): int
    {
        $priorityComparison = $a['priority'] <=> $b['priority'];
        if ($priorityComparison !== 0) {
            return $priorityComparison;
        }

        return $a['sequence'] <=> $b['sequence'];
    }

    private function swap(int $a, int $b): void
    {
        [$this->heap[$a], $this->heap[$b]] = [$this->heap[$b], $this->heap[$a]];
    }
}
