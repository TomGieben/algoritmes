<?php

declare(strict_types=1);

namespace Algoritmes\Datasets;

use InvalidArgumentException;

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

    /**
     * Get the first N prime numbers from the dataset
     */
    public function getFirstN(int $count): array
    {
        if ($count < 0) {
            throw new InvalidArgumentException('Count must be non-negative');
        }

        $this->ensureLoaded();

        if ($count === 0) {
            return [];
        }

        $slice = array_slice($this->data, 0, $count);
        return array_map(static fn(array $row): int => $row['Num'], $slice);
    }

    /**
     * Get the Nth prime (1-indexed)
     */
    public function getNthPrime(int $n): int
    {
        if ($n < 1) {
            throw new InvalidArgumentException('Index must be at least 1');
        }

        $this->ensureLoaded();

        if (!isset($this->data[$n - 1])) {
            throw new InvalidArgumentException('Index exceeds dataset size');
        }

        return $this->data[$n - 1]['Num'];
    }

    /**
     * Determine if a value exists in the dataset using binary search
     */
    public function isPrime(int $number): bool
    {
        if ($number < 2) {
            return false;
        }

        $this->ensureLoaded();

        $low = 0;
        $high = count($this->data) - 1;

        while ($low <= $high) {
            $mid = intdiv($low + $high, 2);
            $value = $this->data[$mid]['Num'];

            if ($value === $number) {
                return true;
            }

            if ($value < $number) {
                $low = $mid + 1;
            } else {
                $high = $mid - 1;
            }
        }

        return false;
    }

    /**
     * Return all primes in the inclusive range [$start, $end]
     */
    public function getPrimesInRange(int $start, int $end): array
    {
        if ($start > $end) {
            throw new InvalidArgumentException('Start must be less than or equal to end');
        }

        $this->ensureLoaded();

        $result = [];

        foreach ($this->data as $row) {
            $prime = $row['Num'];

            if ($prime < $start) {
                continue;
            }

            if ($prime > $end) {
                break;
            }

            $result[] = $prime;
        }

        return $result;
    }

    /**
     * Summarise dataset statistics used by the tests
     */
    public function getStatistics(): array
    {
        $this->ensureLoaded();

        $count = count($this->data);

        if ($count === 0) {
            return [
                'count' => 0,
                'smallest' => null,
                'largest' => null,
                'avg_interval' => 0,
                'max_interval' => 0,
                'min_interval' => 0,
            ];
        }

        $smallest = $this->data[0]['Num'];
        $largest = $this->data[$count - 1]['Num'];

        $intervalSum = 0;
        $minInterval = PHP_INT_MAX;
        $maxInterval = 0;

        foreach ($this->data as $row) {
            $interval = $row['Interval'];
            $intervalSum += $interval;
            $minInterval = min($minInterval, $interval);
            $maxInterval = max($maxInterval, $interval);
        }

        $avgInterval = $intervalSum / $count;

        return [
            'count' => $count,
            'smallest' => $smallest,
            'largest' => $largest,
            'avg_interval' => $avgInterval,
            'max_interval' => $maxInterval,
            'min_interval' => $minInterval,
        ];
    }
}
