<?php

declare(strict_types=1);

namespace Algoritmes\Lists\Interfaces;

use Countable;
use IteratorAggregate;
use Traversable;

interface ListInterface extends Countable, IteratorAggregate
{
    public function add(mixed $item): void;
    public function insert(int $index, mixed $item): void;

    public function get(int $index): mixed;
    public function set(int $index, mixed $item): void;

    public function removeAt(int $index): mixed;
    public function clear(): void;

    public function isEmpty(): bool;
    public function count(): int;

    public function getIterator(): Traversable;
}
