<?php

namespace Algoritmes\Helpers;

use Algoritmes\Interfaces\IsComparator;

/**
 * Standard comparator for float values.
 */
final class FloatComparator implements IsComparator
{
    private float $epsilon;

    public function __construct(float $epsilon = 0.0000001)
    {
        $this->epsilon = $epsilon;
    }

    public function compare(mixed $a, mixed $b): int
    {
        if (!is_float($a) && !is_int($a)) {
            throw new \TypeError('FloatComparator expects numeric values, got ' . get_debug_type($a));
        }
        if (!is_float($b) && !is_int($b)) {
            throw new \TypeError('FloatComparator expects numeric values, got ' . get_debug_type($b));
        }

        $diff = (float)$a - (float)$b;

        if (abs($diff) < $this->epsilon) {
            return 0;
        }

        return $diff <=> 0;
    }
}
