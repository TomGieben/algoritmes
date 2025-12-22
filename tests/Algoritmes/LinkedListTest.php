<?php

namespace Tests\Algoritmes;

use PHPUnit\Framework\TestCase;
use Algoritmes\Algoritmes\LinkedList;
use Algoritmes\Helpers\CsvLoader;

class LinkedListTest extends TestCase
{
    private LinkedList $list;
    private array $primes;

    protected function setUp(): void
    {
        $this->list = new LinkedList();
        $this->primes = CsvLoader::loadColumnToArray('storage/1m.csv', 1, true);
        // Take first 10000
        $this->primes = array_slice($this->primes, 0, 10000);
        foreach ($this->primes as $prime) {
            $this->list->add((int)$prime);
        }
    }

    public function testBestCase(): void
    {
        // Best case: operations at the beginning
        $this->list->insert(0, 0);
        $this->assertEquals(0, $this->list->get(0));
        $removed = $this->list->removeAt(0);
        $this->assertEquals(0, $removed);
    }

    public function testWorstCase(): void
    {
        // Worst case: operations at the end
        $size = $this->list->size();
        $this->list->add(999999); // Large number
        $this->assertEquals(999999, $this->list->get($size));
        $removed = $this->list->removeAt($size);
        $this->assertEquals(999999, $removed);
    }
}
