<?php

declare(strict_types=1);

namespace Algoritmes\Sort\Interfaces;

interface SorterInterface
{
    public function sort(array $array, ?callable $comparator = null): array;
}
