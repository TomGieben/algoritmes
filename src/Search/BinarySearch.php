<?php

declare(strict_types=1);

namespace Algoritmes\Search;

use Algoritmes\Lists\Interfaces\ListInterface;

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
 * Note: The list MUST be sorted before using binary search!
 */
class BinarySearch extends AbstractSearcher
{
    /**
     * {@inheritdoc}
     */
    public function search(ListInterface $list, mixed $target, ?callable $comparator = null): int
    {
        $comparator = $this->getComparator($comparator);

        if ($list->count() === 0) {
            return -1;
        }

        return $this->binarySearch($list, $target, $comparator);
    }

    /**
     * Perform iterative binary search on sorted list
     * 
     * @param ListInterface $list The sorted list to search in
     * @param mixed $target The element to search for
     * @param callable $comparator The comparator function
     * @return int The index if found, -1 otherwise
     */
    private function binarySearch(ListInterface $list, mixed $target, callable $comparator): int
    {
        $left = 0;
        $right = $list->count() - 1;

        while ($left <= $right) {
            $mid = (int)(($left + $right) / 2);
            $midValue = $list->get($mid);

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
     * Find the leftmost (first) occurrence of target in sorted list
     * 
     * @param ListInterface $list The sorted list to search in
     * @param mixed $target The element to search for
     * @param callable|null $comparator Optional custom comparator function
     * @return int The index of the first occurrence if found, -1 otherwise
     */
    public function searchLeftmost(ListInterface $list, mixed $target, ?callable $comparator = null): int
    {
        $comparator = $this->getComparator($comparator);

        if ($list->count() === 0) {
            return -1;
        }

        $left = 0;
        $right = $list->count() - 1;
        $result = -1;

        while ($left <= $right) {
            $mid = (int)(($left + $right) / 2);
            $comparison = $comparator($list->get($mid), $target);

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
     * Find the rightmost (last) occurrence of target in sorted list
     * 
     * @param ListInterface $list The sorted list to search in
     * @param mixed $target The element to search for
     * @param callable|null $comparator Optional custom comparator function
     * @return int The index of the last occurrence if found, -1 otherwise
     */
    public function searchRightmost(ListInterface $list, mixed $target, ?callable $comparator = null): int
    {
        $comparator = $this->getComparator($comparator);

        if ($list->count() === 0) {
            return -1;
        }

        $left = 0;
        $right = $list->count() - 1;
        $result = -1;

        while ($left <= $right) {
            $mid = (int)(($left + $right) / 2);
            $comparison = $comparator($list->get($mid), $target);

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
     * Find the insertion position for target in sorted list
     * This is useful for maintaining sorted order when inserting new elements
     * 
     * @param ListInterface $list The sorted list
     * @param mixed $target The element to find insertion position for
     * @param callable|null $comparator Optional custom comparator function
     * @return int The index where target should be inserted
     */
    public function findInsertionPosition(ListInterface $list, mixed $target, ?callable $comparator = null): int
    {
        $comparator = $this->getComparator($comparator);

        if ($list->count() === 0) {
            return 0;
        }

        $left = 0;
        $right = $list->count();

        while ($left < $right) {
            $mid = (int)(($left + $right) / 2);
            $comparison = $comparator($list->get($mid), $target);

            if ($comparison < 0) {
                $left = $mid + 1;
            } else {
                $right = $mid;
            }
        }

        return $left;
    }
}
