<?php

namespace Algoritmes\Helpers;

use Algoritmes\Interfaces\IsComparator;

/**
 * Standard comparator for string values.
 */
final class StringComparator implements IsComparator
{
    public function compare(mixed $a, mixed $b): int
    {
        if (!is_string($a) || !is_string($b)) {
            throw new \TypeError('StringComparator expects string values');
        }

        return strcmp($a, $b);
    }
}
