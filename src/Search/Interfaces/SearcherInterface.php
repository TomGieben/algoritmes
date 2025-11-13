<?php

declare(strict_types=1);

namespace Algoritmes\Search\Interfaces;

interface SearcherInterface
{
    /**
     * Search for an element in an array
     * 
     * @param array $array The array to search in (must be sorted for binary search)
     * @param mixed $target The element to search for
     * @param callable|null $comparator Optional custom comparator function
     * @return int The index of the element if found, -1 otherwise
     */
    public function search(array $array, mixed $target, ?callable $comparator = null): int;
}
