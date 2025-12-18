<?php

declare(strict_types=1);

namespace Algoritmes\Sort\Interfaces;

use Algoritmes\Lists\Interfaces\ListInterface;

interface SorterInterface
{
    public function sort(ListInterface $list, ?callable $comparator = null): ListInterface;
}
