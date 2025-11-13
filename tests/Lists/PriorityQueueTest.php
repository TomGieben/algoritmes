<?php

declare(strict_types=1);

namespace Algoritmes\Tests\Lists;

use Algoritmes\Lists\PriorityQueue;
use PHPUnit\Framework\TestCase;

class PriorityQueueTest extends TestCase
{
    private PriorityQueue $queue;

    protected function setUp(): void
    {
        $this->queue = new PriorityQueue();
    }

    public function testEnqueueAndDequeue(): void
    {
        $this->queue->enqueue('task1', 2);
        $this->queue->enqueue('task2', 1);
        $this->queue->enqueue('task3', 3);

        // Should dequeue in priority order (1, 2, 3)
        $this->assertEquals('task2', $this->queue->dequeue());
        $this->assertEquals('task1', $this->queue->dequeue());
        $this->assertEquals('task3', $this->queue->dequeue());
    }

    public function testPeek(): void
    {
        $this->queue->enqueue('high', 1);
        $this->queue->enqueue('low', 5);

        // Peek should not remove the element
        $this->assertEquals('high', $this->queue->peek());
        $this->assertEquals('high', $this->queue->peek());
    }

    public function testIsEmpty(): void
    {
        $this->assertTrue($this->queue->isEmpty());

        $this->queue->enqueue('item', 1);
        $this->assertFalse($this->queue->isEmpty());

        $this->queue->dequeue();
        $this->assertTrue($this->queue->isEmpty());
    }

    public function testSize(): void
    {
        $this->assertEquals(0, $this->queue->size());

        $this->queue->enqueue('item1', 1);
        $this->queue->enqueue('item2', 2);
        $this->queue->enqueue('item3', 1);

        $this->assertEquals(3, $this->queue->size());

        $this->queue->dequeue();
        $this->assertEquals(2, $this->queue->size());
    }

    public function testFIFOWithinSamePriority(): void
    {
        $this->queue->enqueue('first', 1);
        $this->queue->enqueue('second', 1);
        $this->queue->enqueue('third', 1);

        // Same priority should be FIFO
        $this->assertEquals('first', $this->queue->dequeue());
        $this->assertEquals('second', $this->queue->dequeue());
        $this->assertEquals('third', $this->queue->dequeue());
    }

    public function testDequeueFromEmptyQueue(): void
    {
        $this->expectException(\UnderflowException::class);
        $this->queue->dequeue();
    }

    public function testPeekEmptyQueue(): void
    {
        $this->expectException(\UnderflowException::class);
        $this->queue->peek();
    }

    public function testComplexScenario(): void
    {
        $this->queue->enqueue('email1', 3);
        $this->queue->enqueue('email2', 1);
        $this->queue->enqueue('email3', 2);
        $this->queue->enqueue('email4', 1);
        $this->queue->enqueue('email5', 0);

        // Should dequeue in order: email5(0), email2(1), email4(1), email3(2), email1(3)
        $this->assertEquals('email5', $this->queue->dequeue());
        $this->assertEquals('email2', $this->queue->dequeue());
        $this->assertEquals('email4', $this->queue->dequeue());
        $this->assertEquals('email3', $this->queue->dequeue());
        $this->assertEquals('email1', $this->queue->dequeue());
    }
}
