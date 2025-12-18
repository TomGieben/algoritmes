<?php

declare(strict_types=1);

namespace Algoritmes\Search\Interfaces;

use Algoritmes\Lists\Interfaces\ListInterface;

interface SearcherInterface
{
    /**
     * Search for an element in a list
     * 
     * @param ListInterface $list The list to search in (must be sorted for binary search)
     * @param mixed $target The element to search for
     * @param callable|null $comparator Optional custom comparator function
     * @return int The index of the element if found, -1 otherwise
     */
    public function search(ListInterface $list, mixed $target, ?callable $comparator = null): int;
}
