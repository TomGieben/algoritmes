<?php

declare(strict_types=1);

namespace Algoritmes\Datasets;

/**
 * Dataset containing the first 1 million prime numbers
 * 
 * Source: First 1 Million Prime Numbers (1m.csv)
 * 
 * Columns:
 * - Rank: The ranking/position of the prime (1st, 2nd, 3rd, etc.)
 * - Num: The prime number itself
 * - Interval: The gap between this prime and the previous one
 */
class PrimeDataset extends AbstractDataset
{
    /**
     * {@inheritdoc}
     */
    protected function getDefaultFilePath(): string
    {
        return $this->getStoragePath() . DIRECTORY_SEPARATOR . '1m.csv';
    }

    /**
     * {@inheritdoc}
     */
    protected function parseRow(array $row): array
    {
        return [
            'Rank' => (int)($row['Rank'] ?? 0),
            'Num' => (int)($row['Num'] ?? 0),
            'Interval' => (int)($row['Interval'] ?? 0),
        ];
    }
}
