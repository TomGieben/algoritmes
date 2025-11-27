<?php

declare(strict_types=1);

namespace Algoritmes\Datasets;

/**
 * Generic list dataset that can load any CSV file
 * Also provides static helper methods for generating test data
 */
class ListDataset extends AbstractDataset
{
    /**
     * Column to use as primary data column
     */
    private ?string $primaryColumn = null;

    /**
     * Custom file path (empty string means no file)
     */
    private string $customFilePath = '';

    public function __construct(?string $filePath = null)
    {
        $this->customFilePath = $filePath ?? '';
        parent::__construct($filePath);
    }

    /**
     * {@inheritdoc}
     */
    protected function getDefaultFilePath(): string
    {
        return $this->customFilePath;
    }

    /**
     * Set the primary column to use for getData()
     */
    public function setPrimaryColumn(string $column): self
    {
        $this->primaryColumn = $column;
        return $this;
    }

    /**
     * Get data from the primary column, or all data if not set
     */
    public function getData(): array
    {
        if ($this->primaryColumn !== null) {
            return $this->getColumn($this->primaryColumn);
        }
        return $this->toArray();
    }

    /**
     * Create a ListDataset from an array of data
     */
    public static function fromArray(array $data, array $columns = []): self
    {
        $dataset = new self();
        $dataset->data = $data;
        $dataset->columns = $columns;
        $dataset->loaded = true;
        return $dataset;
    }

    // ========================================
    // Static helper methods for test data
    // ========================================

    /**
     * Get a dataset with integer values for testing
     */
    public static function getIntegerDataset(): array
    {
        return [
            10,
            25,
            7,
            42,
            15,
            33,
            8,
            99,
            2,
            55,
        ];
    }

    /**
     * Get a dataset with string values for testing
     */
    public static function getStringDataset(): array
    {
        return [
            'apple',
            'banana',
            'cherry',
            'date',
            'elderberry',
            'fig',
            'grape',
            'honeydew',
            'kiwi',
            'lemon',
        ];
    }

    /**
     * Get a small dataset for basic operations testing
     */
    public static function getSmallIntegerDataset(): array
    {
        return [1, 2, 3];
    }

    /**
     * Get a small dataset with strings
     */
    public static function getSmallStringDataset(): array
    {
        return ['a', 'b', 'c'];
    }

    /**
     * Get a dataset with duplicate values
     */
    public static function getDuplicateDataset(): array
    {
        return [5, 5, 10, 10, 15, 15, 20, 20];
    }

    /**
     * Get a large dataset for performance testing
     */
    public static function getLargeIntegerDataset(int $size = 1000): array
    {
        $dataset = [];
        for ($i = 0; $i < $size; $i++) {
            $dataset[] = mt_rand(1, 10000);
        }
        return $dataset;
    }

    /**
     * Get a sorted dataset for search testing
     */
    public static function getSortedIntegerDataset(): array
    {
        return [1, 5, 10, 15, 20, 25, 30, 35, 40, 45, 50];
    }

    /**
     * Get a sorted string dataset for search testing
     */
    public static function getSortedStringDataset(): array
    {
        return ['apple', 'banana', 'cherry', 'date', 'fig', 'grape', 'kiwi', 'lemon'];
    }
}
