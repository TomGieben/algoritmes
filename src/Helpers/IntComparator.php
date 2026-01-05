<?php

namespace Algoritmes\Helpers;

use Algoritmes\Interfaces\IsComparator;

/**
 * Standard comparator for integer values.
 */
final class IntComparator implements IsComparator
{
    public function compare(mixed $a, mixed $b): int
    {
        if (!is_int($a) || !is_int($b)) {
            throw new \TypeError('IntComparator expects integer values');
        }

        return $a <=> $b;
    }
}
