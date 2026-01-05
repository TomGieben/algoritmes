<?php

namespace Algoritmes\Interfaces;

/**
 * Generic map interface supporting mixed key types.
 * 
 * @template K
 * @template V
 */
interface IsMap
{
    public function set(mixed $key, mixed $value): void;
    public function get(mixed $key): mixed;
    public function has(mixed $key): bool;
    public function remove(mixed $key): void;
    public function size(): int;
    public function clear(): void;
}
