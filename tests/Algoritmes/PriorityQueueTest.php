<?php

namespace Tests\Algoritmes;

use PHPUnit\Framework\TestCase;
use Algoritmes\Algoritmes\PriorityQueue;
use Algoritmes\Algoritmes\LinkedList;
use Algoritmes\Helpers\CsvLoader;

class PriorityQueueTest extends TestCase
{
    private PriorityQueue $pq;
    private array $primes;

    protected function setUp(): void
    {
        $this->pq = new PriorityQueue(new LinkedList());
        $this->primes = CsvLoader::loadColumnToArray('storage/1m.csv', 1, true);
        // Take first 1000 for faster
        $this->primes = array_slice($this->primes, 0, 1000);
    }

    public function testBestCase(): void
    {
        // Best case: enqueue in order (lowest priority first)
        foreach ($this->primes as $i => $prime) {
            $this->pq->enqueue($prime, $i);
        }
        $first = $this->pq->dequeue();
        $this->assertEquals($this->primes[0], $first);
    }

    public function testWorstCase(): void
    {
        // Worst case: enqueue in reverse order (highest priority first, but since it's min-heap like, inserting high first requires shifting)
        $reversed = array_reverse($this->primes);
        foreach ($reversed as $i => $prime) {
            $this->pq->enqueue($prime, count($this->primes) - $i - 1);
        }
        $first = $this->pq->dequeue();
        $this->assertEquals($this->primes[0], $first);
    }
}
