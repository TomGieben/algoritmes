<?php

declare(strict_types=1);

namespace Algoritmes\HashTable;

use Algoritmes\HashTable\Interfaces\HashTableInterface;
use ArrayIterator;
use Traversable;

/**
 * Hash Table implementation using Separate Chaining for collision resolution
 * 
 * Time Complexity:
 * - Average case (put, get, remove): O(1)
 * - Worst case (put, get, remove): O(n) - when all hash to same bucket
 * 
 * Space Complexity: O(n)
 * 
 * This implementation uses:
 * - Separate Chaining: Each bucket contains a linked list of entries
 * - Dynamic resizing: Table grows when load factor exceeds threshold
 * - FNV-1a hash function for better distribution
 */
class HashTable implements HashTableInterface
{
    /**
     * Default initial capacity
     */
    private const DEFAULT_CAPACITY = 16;

    /**
     * Load factor threshold for resizing
     */
    private const LOAD_FACTOR_THRESHOLD = 0.75;

    /**
     * Hash buckets (array of arrays for chaining)
     */
    private array $buckets = [];

    /**
     * Current capacity
     */
    private int $capacity;

    /**
     * Number of entries
     */
    private int $size = 0;

    public function __construct(int $capacity = self::DEFAULT_CAPACITY)
    {
        $this->capacity = max(1, $capacity);
        $this->initializeBuckets();
    }

    /**
     * Initialize empty buckets
     */
    private function initializeBuckets(): void
    {
        $this->buckets = array_fill(0, $this->capacity, []);
    }

    /**
     * {@inheritdoc}
     */
    public function put(string $key, mixed $value): void
    {
        $index = $this->hash($key);

        // Check if key already exists and update value
        foreach ($this->buckets[$index] as &$entry) {
            if ($entry['key'] === $key) {
                $entry['value'] = $value;
                return;
            }
        }

        // Add new entry
        $this->buckets[$index][] = ['key' => $key, 'value' => $value];
        $this->size++;

        // Check if resizing is needed
        if ($this->getLoadFactor() > self::LOAD_FACTOR_THRESHOLD) {
            $this->resize();
        }
    }

    /**
     * {@inheritdoc}
     */
    public function get(string $key, mixed $default = null): mixed
    {
        $index = $this->hash($key);

        foreach ($this->buckets[$index] as $entry) {
            if ($entry['key'] === $key) {
                return $entry['value'];
            }
        }

        return $default;
    }

    /**
     * {@inheritdoc}
     */
    public function containsKey(string $key): bool
    {
        $index = $this->hash($key);

        foreach ($this->buckets[$index] as $entry) {
            if ($entry['key'] === $key) {
                return true;
            }
        }

        return false;
    }

    /**
     * {@inheritdoc}
     */
    public function remove(string $key): mixed
    {
        $index = $this->hash($key);

        foreach ($this->buckets[$index] as $i => $entry) {
            if ($entry['key'] === $key) {
                $value = $entry['value'];
                unset($this->buckets[$index][$i]);
                $this->buckets[$index] = array_values($this->buckets[$index]);
                $this->size--;
                return $value;
            }
        }

        return null;
    }

    /**
     * {@inheritdoc}
     */
    public function clear(): void
    {
        $this->buckets = [];
        $this->size = 0;
        $this->initializeBuckets();
    }

    /**
     * {@inheritdoc}
     */
    public function keys(): array
    {
        $keys = [];
        foreach ($this->buckets as $bucket) {
            foreach ($bucket as $entry) {
                $keys[] = $entry['key'];
            }
        }
        return $keys;
    }

    /**
     * {@inheritdoc}
     */
    public function values(): array
    {
        $values = [];
        foreach ($this->buckets as $bucket) {
            foreach ($bucket as $entry) {
                $values[] = $entry['value'];
            }
        }
        return $values;
    }

    /**
     * {@inheritdoc}
     */
    public function count(): int
    {
        return $this->size;
    }

    /**
     * {@inheritdoc}
     */
    public function isEmpty(): bool
    {
        return $this->size === 0;
    }

    /**
     * Get an iterator for the hash table entries
     */
    public function getIterator(): Traversable
    {
        $entries = [];
        foreach ($this->buckets as $bucket) {
            foreach ($bucket as $entry) {
                $entries[$entry['key']] = $entry['value'];
            }
        }
        return new ArrayIterator($entries);
    }

    /**
     * FNV-1a hash function
     * Better distribution than PHP's default hash for strings
     * 
     * @param string $key The key to hash
     * @return int The bucket index
     */
    private function hash(string $key): int
    {
        // FNV offset basis
        $hash = 2166136261;

        // FNV prime
        $prime = 16777619;

        for ($i = 0; $i < strlen($key); $i++) {
            $hash ^= ord($key[$i]);
            $hash = ($hash * $prime) & 0xffffffff;
        }

        return abs($hash) % $this->capacity;
    }

    /**
     * Get the current load factor (size / capacity)
     */
    private function getLoadFactor(): float
    {
        return $this->size / $this->capacity;
    }

    /**
     * Resize the hash table when load factor is exceeded
     */
    private function resize(): void
    {
        // Save old buckets
        $oldBuckets = $this->buckets;
        $oldCapacity = $this->capacity;

        // Create new larger capacity
        $this->capacity *= 2;
        $this->size = 0;
        $this->initializeBuckets();

        // Re-hash all entries
        foreach ($oldBuckets as $bucket) {
            foreach ($bucket as $entry) {
                $this->put($entry['key'], $entry['value']);
            }
        }
    }

    /**
     * Get collision statistics (for debugging/analysis)
     */
    public function getCollisionStats(): array
    {
        $stats = [
            'capacity' => $this->capacity,
            'size' => $this->size,
            'load_factor' => $this->getLoadFactor(),
            'empty_buckets' => 0,
            'non_empty_buckets' => 0,
            'collision_buckets' => 0,
            'max_chain_length' => 0,
            'total_chain_length' => 0,
        ];

        foreach ($this->buckets as $bucket) {
            if (empty($bucket)) {
                $stats['empty_buckets']++;
            } else {
                $stats['non_empty_buckets']++;
                $chainLength = count($bucket);
                $stats['total_chain_length'] += $chainLength;

                if ($chainLength > 1) {
                    $stats['collision_buckets']++;
                }

                if ($chainLength > $stats['max_chain_length']) {
                    $stats['max_chain_length'] = $chainLength;
                }
            }
        }

        return $stats;
    }
}
