<?php

declare(strict_types=1);

namespace Algoritmes\Search;

use Algoritmes\Common\ComparatorResolver;
use Algoritmes\Search\Interfaces\SearcherInterface;

abstract class AbstractSearcher implements SearcherInterface
{
    /**
     * Get the comparator to use for searching
     * 
     * @param callable|null $comparator Custom comparator or null for default
     * @return callable The comparator function
     */
    protected function getComparator(?callable $comparator = null): callable
    {
        return ComparatorResolver::resolve($comparator);
    }
}
