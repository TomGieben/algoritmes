<?php

declare(strict_types=1);

namespace Algoritmes\Sort;

use Algoritmes\Lists\Adapters\RandomAccessAdapter;
use Algoritmes\Lists\ArrayList;
use Algoritmes\Lists\Interfaces\ListInterface;
use Algoritmes\Lists\Interfaces\RandomAccessListInterface;

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
    public function sort(ListInterface $list, ?callable $comparator = null): ListInterface
    {
        if ($list->count() <= 1) {
            return $list;
        }

        $accessList = RandomAccessAdapter::ensure($list);
        $comparator = $this->getComparator($comparator);

        $this->mergeSort($accessList, 0, $accessList->count() - 1, $comparator);
        return $accessList;
    }

    /**
     * Recursively divide and merge the array
     * 
     * @param array $array The array to sort
     * @param callable $comparator The comparator function
     * @return array The sorted array
     */
    private function mergeSort(RandomAccessListInterface $list, int $left, int $right, callable $comparator): void
    {
        if ($left >= $right) {
            return;
        }

        $middle = (int)(($left + $right) / 2);

        $this->mergeSort($list, $left, $middle, $comparator);
        $this->mergeSort($list, $middle + 1, $right, $comparator);

        $this->merge($list, $left, $middle, $right, $comparator);
    }

    /**
     * Merge two sorted arrays
     * 
     * @param array $left The left sorted array
     * @param array $right The right sorted array
     * @param callable $comparator The comparator function
     * @return array The merged sorted array
     */
    private function merge(RandomAccessListInterface $list, int $left, int $middle, int $right, callable $comparator): void
    {
        $leftLength = $middle - $left + 1;
        $rightLength = $right - $middle;

        $leftBuffer = $this->copySegment($list, $left, $leftLength);
        $rightBuffer = $this->copySegment($list, $middle + 1, $rightLength);

        $i = 0;
        $j = 0;
        $k = $left;

        while ($i < $leftLength && $j < $rightLength) {
            if ($comparator($leftBuffer->get($i), $rightBuffer->get($j)) <= 0) {
                $list->set($k, $leftBuffer->get($i));
                $i++;
            } else {
                $list->set($k, $rightBuffer->get($j));
                $j++;
            }
            $k++;
        }

        while ($i < $leftLength) {
            $list->set($k, $leftBuffer->get($i));
            $i++;
            $k++;
        }

        while ($j < $rightLength) {
            $list->set($k, $rightBuffer->get($j));
            $j++;
            $k++;
        }
    }

    private function copySegment(RandomAccessListInterface $list, int $start, int $length): RandomAccessListInterface
    {
        if ($length <= 0) {
            return new ArrayList('mixed');
        }

        $buffer = new ArrayList('mixed');

        for ($offset = 0; $offset < $length; $offset++) {
            $buffer->add($list->get($start + $offset));
        }

        return $buffer;
    }
}
