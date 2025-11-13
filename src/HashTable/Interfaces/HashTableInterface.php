<?php

declare(strict_types=1);

namespace Algoritmes\HashTable\Interfaces;

use Countable;
use IteratorAggregate;

interface HashTableInterface extends Countable, IteratorAggregate
{
    /**
     * Put a key-value pair into the hash table
     * 
     * @param string $key The key
     * @param mixed $value The value
     * @return void
     */
    public function put(string $key, mixed $value): void;

    /**
     * Get a value by key
     * 
     * @param string $key The key
     * @param mixed $default The default value if key not found
     * @return mixed The value or default
     */
    public function get(string $key, mixed $default = null): mixed;

    /**
     * Check if key exists
     * 
     * @param string $key The key
     * @return bool True if key exists, false otherwise
     */
    public function containsKey(string $key): bool;

    /**
     * Remove a key-value pair
     * 
     * @param string $key The key
     * @return mixed The removed value or null
     */
    public function remove(string $key): mixed;

    /**
     * Clear all entries
     * 
     * @return void
     */
    public function clear(): void;

    /**
     * Get all keys
     * 
     * @return array Array of all keys
     */
    public function keys(): array;

    /**
     * Get all values
     * 
     * @return array Array of all values
     */
    public function values(): array;

    /**
     * Get the size of the hash table
     * 
     * @return int Number of entries
     */
    public function count(): int;

    /**
     * Check if hash table is empty
     * 
     * @return bool
     */
    public function isEmpty(): bool;
}
