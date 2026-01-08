<?php

namespace Algoritmes\Algoritmes;

use Algoritmes\Interfaces\IsComparator;
use Algoritmes\Interfaces\IsList;
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
            $mid = intdiv($left + $right, 2);  // Bereken midden index
            $comparison = $this->comparator->compare($list->get($mid), $target);

            if ($comparison === 0) {
                return $mid;  // Gevonden!
            } elseif ($comparison < 0) {
                $left = $mid + 1;  // Zoek in rechter helft
            } else {
                $right = $mid - 1;  // Zoek in linker helft
            }
        }

        return -1;  // Niet gevonden
    }
}
