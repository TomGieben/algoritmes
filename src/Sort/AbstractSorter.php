<?php

declare(strict_types=1);

namespace Algoritmes\Sort;

use Algoritmes\Common\ComparatorResolver;
use Algoritmes\Sort\Interfaces\SorterInterface;

abstract class AbstractSorter implements SorterInterface
{
    /**
     * Get the comparator to use for sorting
     * 
     * @param callable|null $comparator Custom comparator or null for default
     * @return callable The comparator function
     */
    protected function getComparator(?callable $comparator = null): callable
    {
        return ComparatorResolver::resolve($comparator);
    }
}
