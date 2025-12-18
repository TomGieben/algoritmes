<?php

declare(strict_types=1);

namespace Algoritmes\Common;

/**
 * Helper responsible for resolving comparator callbacks.
 */
final class ComparatorResolver
{
    /**
     * Resolve a comparator, falling back to the default when null is provided.
     */
    public static function resolve(?callable $comparator = null): callable
    {
        return $comparator ?? self::defaultComparator();
    }

    /**
     * Default comparator using PHP's spaceship operator.
     */
    public static function defaultComparator(): callable
    {
        return static fn(mixed $a, mixed $b): int => $a <=> $b;
    }
}
