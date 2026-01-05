<?php

namespace Tests\Algoritmes;

use PHPUnit\Framework\TestCase;
use Algoritmes\Algoritmes\MergeSort;
use Algoritmes\Algoritmes\LinkedList;
use Algoritmes\Enums\SortDirection;
use Algoritmes\Helpers\CsvLoader;
use Algoritmes\Helpers\IntComparator;

class MergeSortTest extends TestCase
{
    private array $primes;

    protected function setUp(): void
    {
        $this->primes = CsvLoader::loadColumnToArray('storage/1m.csv', 1, true);
        // Take first 100 for faster testing
        $this->primes = array_slice($this->primes, 0, 100);
    }

    public function testBestCase(): void
    {
        // Best case: already sorted ascending
        $list = new LinkedList();
        foreach ($this->primes as $prime) {
            $list->add((int)$prime);
        }
        $sorter = new MergeSort($list, new IntComparator());
        $sorted = $sorter->sort(SortDirection::ASCENDING);
        $this->assertSorted($sorted, SortDirection::ASCENDING);
    }

    public function testWorstCase(): void
    {
        // Worst case: reverse sorted
        $list = new LinkedList();
        $reversed = array_reverse($this->primes);
        foreach ($reversed as $prime) {
            $list->add((int)$prime);
        }
        $sorter = new MergeSort($list, new IntComparator());
        $sorted = $sorter->sort(SortDirection::ASCENDING);
        $this->assertSorted($sorted, SortDirection::ASCENDING);
    }

    private function assertSorted(LinkedList $list, SortDirection $direction): void
    {
        for ($i = 0; $i < $list->size() - 1; $i++) {
            $current = $list->get($i);
            $next = $list->get($i + 1);
            $this->assertGreaterThanOrEqual($current, $next);
        }
    }
}
