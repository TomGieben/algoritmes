<?php

namespace Algoritmes\Algoritmes;

use Algoritmes\Objects\PrioritizedObject;

/**
 * Binary Min-Heap based Priority Queue
 * 
 * Time Complexity:
 * - enqueue: O(log n)
 * - dequeue: O(log n)
 * - peek: O(1)
 * 
 * Lower priority values = higher priority (min-heap)
 */
final class PriorityQueue
{
    private array $heap = [];

    public function __construct()
    {
        $this->heap = [];
    }

    public function enqueue(mixed $value, float $priority): void
    {
        $prioritizedObject = new PrioritizedObject($value, $priority);

        // Voeg toe aan einde van heap
        $this->heap[] = $prioritizedObject;

        // Herstel heap property door naar boven te bubblelen
        $this->heapifyUp(count($this->heap) - 1);
    }

    public function dequeue(): mixed
    {
        if (empty($this->heap)) {
            throw new \UnderflowException("Priority queue is empty");
        }

        // Root heeft minimale prioriteit
        $min = $this->heap[0];

        // Verplaats laatste element naar root
        $lastIndex = count($this->heap) - 1;
        $this->heap[0] = $this->heap[$lastIndex];
        array_pop($this->heap);

        // Herstel heap property door naar beneden te bubblelen
        if (!empty($this->heap)) {
            $this->heapifyDown(0);
        }

        return $min->value;
    }

    public function peek(): mixed
    {
        if (empty($this->heap)) {
            throw new \UnderflowException("Priority queue is empty");
        }

        return $this->heap[0]->value;
    }

    public function isEmpty(): bool
    {
        return empty($this->heap);
    }

    public function size(): int
    {
        return count($this->heap);
    }

    /**
     * Bubble element up to restore heap property
     * Time Complexity: O(log n)
     */
    private function heapifyUp(int $index): void
    {
        while ($index > 0) {
            $parentIndex = (int)(($index - 1) / 2);

            // Als huidige prioriteit >= parent, heap property OK
            if ($this->heap[$index]->priority >= $this->heap[$parentIndex]->priority) {
                break;
            }

            // Swap met parent
            [$this->heap[$index], $this->heap[$parentIndex]] =
                [$this->heap[$parentIndex], $this->heap[$index]];

            $index = $parentIndex;
        }
    }

    /**
     * Bubble element down to restore heap property
     * Time Complexity: O(log n)
     */
    private function heapifyDown(int $index): void
    {
        $size = count($this->heap);

        while (true) {
            $leftChild = 2 * $index + 1;
            $rightChild = 2 * $index + 2;
            $smallest = $index;

            // Vind kleinste van node en zijn children
            if (
                $leftChild < $size &&
                $this->heap[$leftChild]->priority < $this->heap[$smallest]->priority
            ) {
                $smallest = $leftChild;
            }

            if (
                $rightChild < $size &&
                $this->heap[$rightChild]->priority < $this->heap[$smallest]->priority
            ) {
                $smallest = $rightChild;
            }

            // Als huidige node kleinste is, heap property OK
            if ($smallest === $index) {
                break;
            }

            // Swap met kleinste child
            [$this->heap[$index], $this->heap[$smallest]] =
                [$this->heap[$smallest], $this->heap[$index]];

            $index = $smallest;
        }
    }
}
