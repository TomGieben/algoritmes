<?php

declare(strict_types=1);

namespace Algoritmes\Datasets\Interfaces;

use Countable;
use IteratorAggregate;

interface DatasetInterface extends Countable, IteratorAggregate
{
    /**
     * Load the dataset from file
     * 
     * @return void
     */
    public function load(): void;

    /**
     * Get all data as array
     * 
     * @return array
     */
    public function toArray(): array;

    /**
     * Get a specific column from the dataset
     * 
     * @param string $column The column name
     * @return array Array of values from that column
     */
    public function getColumn(string $column): array;

    /**
     * Get available column names
     * 
     * @return array
     */
    public function getColumns(): array;

    /**
     * Get a row by index
     * 
     * @param int $index The row index (0-based)
     * @return array|null The row data or null if not found
     */
    public function getRow(int $index): ?array;

    /**
     * Get number of rows
     * 
     * @return int
     */
    public function count(): int;

    /**
     * Check if dataset is loaded
     * 
     * @return bool
     */
    public function isLoaded(): bool;

    /**
     * Get a slice of the dataset
     * 
     * @param int $offset Starting index
     * @param int|null $length Number of rows to return (null for all remaining)
     * @return array
     */
    public function slice(int $offset, ?int $length = null): array;
}
