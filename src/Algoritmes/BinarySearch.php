<?php

namespace Algoritmes\Algoritmes;

use Algoritmes\Interfaces\IsComparator;
use Algoritmes\Interfaces\IsSearch;

final class BinarySearch implements IsSearch
{
    private IsComparator $comparator;

    public function __construct(IsComparator $comparator)
    {
        $this->comparator = $comparator;
    }

    public function search(IsList $list, mixed $target): int
    {
        $left = 0;
        $right = $list->size() - 1;

        while ($left <= $right) {
            $mid = intdiv($left + $right, 2);
            $comparison = $this->comparator->compare($list->get($mid), $target);

            if ($comparison === 0) {
                return $mid;
            } elseif ($comparison < 0) {
                $left = $mid + 1;
            } else {
                $right = $mid - 1;
            }
        }

        return -1;
    }
}
