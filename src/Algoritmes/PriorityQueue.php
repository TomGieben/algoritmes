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

        // Add to end of heap
        $this->heap[] = $prioritizedObject;

        // Restore heap property by bubbling up
        $this->heapifyUp(count($this->heap) - 1);
    }

    public function dequeue(): mixed
    {
        if (empty($this->heap)) {
            throw new \UnderflowException("Priority queue is empty");
        }

        // Root has minimum priority
        $min = $this->heap[0];

        // Move last element to root
        $lastIndex = count($this->heap) - 1;
        $this->heap[0] = $this->heap[$lastIndex];
        array_pop($this->heap);

        // Restore heap property by bubbling down
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

            // If current priority >= parent priority, heap property satisfied
            if ($this->heap[$index]->priority >= $this->heap[$parentIndex]->priority) {
                break;
            }

            // Swap with parent
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

            // Find smallest among node and its children
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

            // If current node is smallest, heap property satisfied
            if ($smallest === $index) {
                break;
            }

            // Swap with smallest child
            [$this->heap[$index], $this->heap[$smallest]] =
                [$this->heap[$smallest], $this->heap[$index]];

            $index = $smallest;
        }
    }
}
