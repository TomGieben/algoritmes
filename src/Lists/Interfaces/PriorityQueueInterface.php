<?php

declare(strict_types=1);

namespace Algoritmes\Lists\Interfaces;

interface PriorityQueueInterface
{
    public function enqueue(mixed $item, int $priority): void;
    public function dequeue(): mixed;
    public function isEmpty(): bool;
    public function size(): int;
    public function peek(): mixed;
}
