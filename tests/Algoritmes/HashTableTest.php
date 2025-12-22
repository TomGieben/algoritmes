<?php

namespace Tests\Algoritmes;

use PHPUnit\Framework\TestCase;
use Algoritmes\Algoritmes\HashTable;
use Algoritmes\Helpers\CsvLoader;

class HashTableTest extends TestCase
{
    private HashTable $hashTable;
    private array $primes;

    protected function setUp(): void
    {
        $this->hashTable = new HashTable(10007); // Larger size to reduce collisions
        $this->primes = CsvLoader::loadColumnToArray('storage/1m.csv', 1, true);
        // Take first 10000
        $this->primes = array_slice($this->primes, 0, 10000);
    }

    public function testBestCase(): void
    {
        // Best case: insert and get with low collisions
        foreach ($this->primes as $prime) {
            $this->hashTable->set($prime, $prime * 2);
        }
        $target = $this->primes[5000];
        $this->assertEquals($target * 2, $this->hashTable->get($target));
    }

    public function testWorstCase(): void
    {
        // Worst case: many operations, potential collisions
        foreach ($this->primes as $prime) {
            $this->hashTable->set($prime, $prime * 2);
        }
        // Remove some
        for ($i = 0; $i < 1000; $i++) {
            $this->hashTable->remove($this->primes[$i]);
        }
        $this->assertFalse($this->hashTable->has($this->primes[0]));
        $this->assertTrue($this->hashTable->has($this->primes[9999]));
    }
}
