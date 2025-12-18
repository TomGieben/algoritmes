<?php

declare(strict_types=1);

namespace Algoritmes\Sort;

use Algoritmes\Lists\Interfaces\ListInterface;

/**
 * Quick Sort implementation
 * 
 * Time Complexity:
 * - Best case: O(n log n)
 * - Average case: O(n log n)
 * - Worst case: O(n²) - when pivot is always smallest or largest element
 * 
 * Space Complexity: O(log n) - due to recursion stack
 * 
 * Stable: No (in this implementation)
 */
class QuickSort extends AbstractSorter
{
    /**
     * {@inheritdoc}
     */
    public function sort(ListInterface $list, ?callable $comparator = null): ListInterface
    {
        $comparator = $this->getComparator($comparator);

        if ($list->count() <= 1) {
            return $list;
        }

        $this->quickSort($list, 0, $list->count() - 1, $comparator);
        return $list;
    }

    /**
     * Recursively partition and sort the array using the Lomuto partition scheme
     * 
     * @param array &$array The array to sort (passed by reference)
     * @param int $low The starting index of the partition
     * @param int $high The ending index of the partition
     * @param callable $comparator The comparator function
     * @return void
     */
    private function quickSort(ListInterface $list, int $low, int $high, callable $comparator): void
    {
        if ($low < $high) {
            // Partition the array and get the pivot index
            $partitionIndex = $this->partition($list, $low, $high, $comparator);

            // Recursively sort elements before and after partition
            $this->quickSort($list, $low, $partitionIndex - 1, $comparator);
            $this->quickSort($list, $partitionIndex + 1, $high, $comparator);
        }
    }

    /**
     * Partition the array using Lomuto partition scheme
     * Elements smaller than pivot are on the left, larger on the right
     * 
     * @param array &$array The array to partition (passed by reference)
     * @param int $low The starting index
     * @param int $high The ending index (pivot position)
     * @param callable $comparator The comparator function
     * @return int The final pivot index
     */
    private function partition(ListInterface $list, int $low, int $high, callable $comparator): int
    {
        $pivot = $list->get($high);
        $i = $low - 1;

        // Compare each element with the pivot
        for ($j = $low; $j < $high; $j++) {
            if ($comparator($list->get($j), $pivot) < 0) {
                $i++;
                // Swap elements at positions i and j
                $this->swap($list, $i, $j);
            }
        }

        // Place pivot at its final position
        $this->swap($list, $i + 1, $high);
        return $i + 1;
    }

    /**
     * Swap two elements in the array
     * 
     * @param array &$array The array (passed by reference)
     * @param int $index1 The first index
     * @param int $index2 The second index
     * @return void
     */
    private function swap(ListInterface $list, int $index1, int $index2): void
    {
        if ($index1 === $index2) {
            return;
        }

        $temp = $list->get($index1);
        $list->set($index1, $list->get($index2));
        $list->set($index2, $temp);
    }
}
