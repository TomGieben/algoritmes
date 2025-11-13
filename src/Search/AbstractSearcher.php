<?php

declare(strict_types=1);

namespace Algoritmes\Search;

use Algoritmes\Search\Interfaces\SearcherInterface;

abstract class AbstractSearcher implements SearcherInterface
{
    /**
     * Default comparator function for comparison
     * 
     * @param mixed $a First element
     * @param mixed $b Second element
     * @return int Negative if a < b, 0 if equal, positive if a > b
     */
    protected function defaultComparator(mixed $a, mixed $b): int
    {
        if ($a < $b) return -1;
        if ($a > $b) return 1;
        return 0;
    }

    /**
     * Get the comparator to use for searching
     * 
     * @param callable|null $comparator Custom comparator or null for default
     * @return callable The comparator function
     */
    protected function getComparator(?callable $comparator = null): callable
    {
        return $comparator ?? $this->defaultComparator(...);
    }
}
