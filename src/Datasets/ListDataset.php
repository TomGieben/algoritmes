<?php

declare(strict_types=1);

namespace Algoritmes\Datasets;

class ListDataset
{
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
}
