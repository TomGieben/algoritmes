<?php

namespace Tests\Algoritmes;

use PHPUnit\Framework\TestCase;
use Algoritmes\Algoritmes\BinarySearch;
use Algoritmes\Algoritmes\LinkedList;
use Algoritmes\Helpers\CsvLoader;

use Algoritmes\Interfaces\IsComparator;

class IntComparator implements IsComparator
{
    public function compare(mixed $a, mixed $b): int
    {
        return $a <=> $b;
    }
}

class BinarySearchTest extends TestCase
{
    private BinarySearch $binarySearch;
    private LinkedList $sortedList;
    private array $primes;

    protected function setUp(): void
    {
        $this->binarySearch = new BinarySearch(new IntComparator());
        $this->primes = CsvLoader::loadColumnToArray('storage/1m.csv', 1, true);
        $this->sortedList = new LinkedList();
        foreach ($this->primes as $prime) {
            $this->sortedList->add((int)$prime);
        }
    }

    public function testBestCase(): void
    {
        // Best case: target is in the middle
        $middleIndex = (int)($this->sortedList->size() / 2);
        $target = $this->sortedList->get($middleIndex);
        $result = $this->binarySearch->search($this->sortedList, $target);
        $this->assertEquals($middleIndex, $result);
    }

    public function testWorstCase(): void
    {
        // Worst case: target not in list, requires full traversal
        $target = 1; // 1 is not prime
        $result = $this->binarySearch->search($this->sortedList, $target);
        $this->assertEquals(-1, $result);
    }
}
