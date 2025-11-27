<?php

declare(strict_types=1);

namespace Algoritmes\Datasets;

use Algoritmes\Datasets\Interfaces\DatasetInterface;
use ArrayIterator;
use InvalidArgumentException;
use RuntimeException;
use Traversable;

/**
 * Abstract base class for datasets
 * 
 * Provides common functionality for loading and accessing CSV-based datasets
 */
abstract class AbstractDataset implements DatasetInterface
{
    /**
     * The loaded data rows
     */
    protected array $data = [];

    /**
     * Column names from the header
     */
    protected array $columns = [];

    /**
     * Whether the dataset has been loaded
     */
    protected bool $loaded = false;

    /**
     * Path to the dataset file
     */
    protected string $filePath;

    /**
     * CSV delimiter character
     */
    protected string $delimiter = ',';

    /**
     * Whether the CSV has a header row
     */
    protected bool $hasHeader = true;

    public function __construct(?string $filePath = null)
    {
        $this->filePath = $filePath ?? $this->getDefaultFilePath();
    }

    /**
     * Get the default file path for this dataset
     * Override in child classes to provide specific paths
     */
    abstract protected function getDefaultFilePath(): string;

    /**
     * {@inheritdoc}
     */
    public function load(): void
    {
        if ($this->loaded) {
            return;
        }

        if (!file_exists($this->filePath)) {
            throw new RuntimeException("Dataset file not found: {$this->filePath}");
        }

        $handle = fopen($this->filePath, 'r');
        if ($handle === false) {
            throw new RuntimeException("Could not open dataset file: {$this->filePath}");
        }

        try {
            // Read header if present
            if ($this->hasHeader) {
                $header = fgetcsv($handle, 0, $this->delimiter);
                if ($header !== false) {
                    $this->columns = array_map('trim', $header);
                }
            }

            // Read data rows
            while (($row = fgetcsv($handle, 0, $this->delimiter)) !== false) {
                if ($this->hasHeader && !empty($this->columns)) {
                    // Create associative array with column names
                    $this->data[] = $this->parseRow(array_combine($this->columns, $row));
                } else {
                    $this->data[] = $this->parseRow($row);
                }
            }

            $this->loaded = true;
        } finally {
            fclose($handle);
        }
    }

    /**
     * Parse a row of data
     * Override in child classes to convert data types
     * 
     * @param array $row The raw row data
     * @return array The parsed row data
     */
    protected function parseRow(array $row): array
    {
        return $row;
    }

    /**
     * {@inheritdoc}
     */
    public function toArray(): array
    {
        $this->ensureLoaded();
        return $this->data;
    }

    /**
     * {@inheritdoc}
     */
    public function getColumn(string $column): array
    {
        $this->ensureLoaded();

        if (!in_array($column, $this->columns)) {
            throw new InvalidArgumentException("Column '$column' does not exist. Available: " . implode(', ', $this->columns));
        }

        return array_column($this->data, $column);
    }

    /**
     * {@inheritdoc}
     */
    public function getColumns(): array
    {
        $this->ensureLoaded();
        return $this->columns;
    }

    /**
     * {@inheritdoc}
     */
    public function getRow(int $index): ?array
    {
        $this->ensureLoaded();
        return $this->data[$index] ?? null;
    }

    /**
     * {@inheritdoc}
     */
    public function count(): int
    {
        $this->ensureLoaded();
        return count($this->data);
    }

    /**
     * {@inheritdoc}
     */
    public function isLoaded(): bool
    {
        return $this->loaded;
    }

    /**
     * {@inheritdoc}
     */
    public function slice(int $offset, ?int $length = null): array
    {
        $this->ensureLoaded();
        return array_slice($this->data, $offset, $length);
    }

    /**
     * {@inheritdoc}
     */
    public function getIterator(): Traversable
    {
        $this->ensureLoaded();
        return new ArrayIterator($this->data);
    }

    /**
     * Ensure the dataset is loaded before accessing data
     */
    protected function ensureLoaded(): void
    {
        if (!$this->loaded) {
            $this->load();
        }
    }

    /**
     * Get path to storage directory
     */
    protected function getStoragePath(): string
    {
        return dirname(__DIR__, 2) . DIRECTORY_SEPARATOR . 'storage';
    }
}
