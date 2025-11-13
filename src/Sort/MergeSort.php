<?php

declare(strict_types=1);

namespace Algoritmes\Sort;

/**
 * Merge Sort implementation
 * 
 * Time Complexity:
 * - Best case: O(n log n)
 * - Average case: O(n log n)
 * - Worst case: O(n log n)
 * 
 * Space Complexity: O(n) - requires additional space for merging
 * 
 * Stable: Yes
 */
class MergeSort extends AbstractSorter
{
    /**
     * {@inheritdoc}
     */
    public function sort(array $array, ?callable $comparator = null): array
    {
        $comparator = $this->getComparator($comparator);

        if (count($array) <= 1) {
            return $array;
        }

        return $this->mergeSort($array, $comparator);
    }

    /**
     * Recursively divide and merge the array
     * 
     * @param array $array The array to sort
     * @param callable $comparator The comparator function
     * @return array The sorted array
     */
    private function mergeSort(array $array, callable $comparator): array
    {
        $length = count($array);

        // Base case: arrays with 0 or 1 element are already sorted
        if ($length <= 1) {
            return $array;
        }

        // Divide the array in half
        $middle = (int)($length / 2);
        $left = array_slice($array, 0, $middle);
        $right = array_slice($array, $middle);

        // Recursively sort both halves
        $left = $this->mergeSort($left, $comparator);
        $right = $this->mergeSort($right, $comparator);

        // Merge the sorted halves
        return $this->merge($left, $right, $comparator);
    }

    /**
     * Merge two sorted arrays
     * 
     * @param array $left The left sorted array
     * @param array $right The right sorted array
     * @param callable $comparator The comparator function
     * @return array The merged sorted array
     */
    private function merge(array $left, array $right, callable $comparator): array
    {
        $result = [];
        $leftIndex = 0;
        $rightIndex = 0;
        $leftLength = count($left);
        $rightLength = count($right);

        // Compare elements from left and right arrays and add the smaller one
        while ($leftIndex < $leftLength && $rightIndex < $rightLength) {
            if ($comparator($left[$leftIndex], $right[$rightIndex]) <= 0) {
                $result[] = $left[$leftIndex];
                $leftIndex++;
            } else {
                $result[] = $right[$rightIndex];
                $rightIndex++;
            }
        }

        // Add remaining elements from left array
        while ($leftIndex < $leftLength) {
            $result[] = $left[$leftIndex];
            $leftIndex++;
        }

        // Add remaining elements from right array
        while ($rightIndex < $rightLength) {
            $result[] = $right[$rightIndex];
            $rightIndex++;
        }

        return $result;
    }
}
