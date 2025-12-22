<?php

namespace Tests\Algoritmes;

use PHPUnit\Framework\TestCase;
use Algoritmes\Algoritmes\DoublyLinkedList;
use Algoritmes\Helpers\CsvLoader;

class DoublyLinkedListTest extends TestCase
{
    private DoublyLinkedList $list;
    private array $primes;

    protected function setUp(): void
    {
        $this->list = new DoublyLinkedList();
        $this->primes = CsvLoader::loadColumnToArray('storage/1m.csv', 1, true);
        // Take first 10000
        $this->primes = array_slice($this->primes, 0, 10000);
        foreach ($this->primes as $prime) {
            $this->list->add((int)$prime);
        }
    }

    public function testBestCase(): void
    {
        // Best case: operations near the ends (doubly linked allows O(1) at ends, but get is O(n/2))
        $this->list->add(0);
        $this->assertEquals(0, $this->list->get($this->list->size() - 1));
        $removed = $this->list->removeAt($this->list->size() - 1);
        $this->assertEquals(0, $removed);
    }

    public function testWorstCase(): void
    {
        // Worst case: get in the middle
        $middle = (int)($this->list->size() / 2);
        $value = $this->list->get($middle);
        $this->assertEquals($this->primes[$middle], $value);
    }
}
