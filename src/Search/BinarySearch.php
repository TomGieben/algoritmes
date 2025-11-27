<?php

declare(strict_types=1);

namespace Algoritmes\Search;

/**
 * Binary Search implementation
 * 
 * Time Complexity:
 * - Best case: O(1) - element found at midpoint on first comparison
 * - Average case: O(log n)
 * - Worst case: O(log n)
 * 
 * Space Complexity: O(1) - iterative implementation uses constant space
 * 
 * Note: The array MUST be sorted before using binary search!
 */
class BinarySearch extends AbstractSearcher
{
    /**
     * {@inheritdoc}
     */
    public function search(array $array, mixed $target, ?callable $comparator = null): int
    {
        $comparator = $this->getComparator($comparator);

        if (empty($array)) {
            return -1;
        }

        // Re-index array to ensure proper indexing
        $array = array_values($array);

        return $this->binarySearch($array, $target, $comparator);
    }

    /**
     * Perform iterative binary search on sorted array
     * 
     * @param array $array The sorted array to search in
     * @param mixed $target The element to search for
     * @param callable $comparator The comparator function
     * @return int The index if found, -1 otherwise
     */
    private function binarySearch(array $array, mixed $target, callable $comparator): int
    {
        $left = 0;
        $right = count($array) - 1;

        while ($left <= $right) {
            $mid = (int)(($left + $right) / 2);
            $midValue = $array[$mid];

            $comparison = $comparator($midValue, $target);

            if ($comparison === 0) {
                // Element found
                return $mid;
            } elseif ($comparison < 0) {
                // Search in the right half
                $left = $mid + 1;
            } else {
                // Search in the left half
                $right = $mid - 1;
            }
        }

        // Element not found
        return -1;
    }

    /**
     * Find the leftmost (first) occurrence of target in sorted array
     * 
     * @param array $array The sorted array to search in
     * @param mixed $target The element to search for
     * @param callable|null $comparator Optional custom comparator function
     * @return int The index of the first occurrence if found, -1 otherwise
     */
    public function searchLeftmost(array $array, mixed $target, ?callable $comparator = null): int
    {
        $comparator = $this->getComparator($comparator);

        if (empty($array)) {
            return -1;
        }

        $array = array_values($array);
        $left = 0;
        $right = count($array) - 1;
        $result = -1;

        while ($left <= $right) {
            $mid = (int)(($left + $right) / 2);
            $comparison = $comparator($array[$mid], $target);

            if ($comparison === 0) {
                $result = $mid;
                // Continue searching in the left half to find leftmost
                $right = $mid - 1;
            } elseif ($comparison < 0) {
                $left = $mid + 1;
            } else {
                $right = $mid - 1;
            }
        }

        return $result;
    }

    /**
     * Find the rightmost (last) occurrence of target in sorted array
     * 
     * @param array $array The sorted array to search in
     * @param mixed $target The element to search for
     * @param callable|null $comparator Optional custom comparator function
     * @return int The index of the last occurrence if found, -1 otherwise
     */
    public function searchRightmost(array $array, mixed $target, ?callable $comparator = null): int
    {
        $comparator = $this->getComparator($comparator);

        if (empty($array)) {
            return -1;
        }

        $array = array_values($array);
        $left = 0;
        $right = count($array) - 1;
        $result = -1;

        while ($left <= $right) {
            $mid = (int)(($left + $right) / 2);
            $comparison = $comparator($array[$mid], $target);

            if ($comparison === 0) {
                $result = $mid;
                // Continue searching in the right half to find rightmost
                $left = $mid + 1;
            } elseif ($comparison < 0) {
                $left = $mid + 1;
            } else {
                $right = $mid - 1;
            }
        }

        return $result;
    }

    /**
     * Find the insertion position for target in sorted array
     * This is useful for maintaining sorted order when inserting new elements
     * 
     * @param array $array The sorted array
     * @param mixed $target The element to find insertion position for
     * @param callable|null $comparator Optional custom comparator function
     * @return int The index where target should be inserted
     */
    public function findInsertionPosition(array $array, mixed $target, ?callable $comparator = null): int
    {
        $comparator = $this->getComparator($comparator);

        if (empty($array)) {
            return 0;
        }

        $array = array_values($array);
        $left = 0;
        $right = count($array);

        while ($left < $right) {
            $mid = (int)(($left + $right) / 2);
            $comparison = $comparator($array[$mid], $target);

            if ($comparison < 0) {
                $left = $mid + 1;
            } else {
                $right = $mid;
            }
        }

        return $left;
    }
}
