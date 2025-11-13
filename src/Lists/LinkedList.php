<?php

declare(strict_types=1);

namespace Algoritmes\Lists;

use Algoritmes\Lists\Interfaces\ListInterface;

class LinkedList implements ListInterface
{
    private ?Node $head = null;
    private ?Node $tail = null;
    private int $size = 0;

    public function add(mixed $item): void
    {
        $newNode = new Node($item);
        if ($this->tail) {
            $this->tail->next = $newNode;
            $newNode->prev = $this->tail;
        } else {
            $this->head = $newNode;
        }
        $this->tail = $newNode;
        $this->size++;
    }

    public function insert(int $index, mixed $item): void
    {
        if ($index < 0 || $index > $this->size) {
            throw new \OutOfBoundsException("Index out of bounds");
        }

        $newNode = new Node($item);

        if ($index === 0) {
            if ($this->head) {
                $newNode->next = $this->head;
                $this->head->prev = $newNode;
            } else {
                $this->tail = $newNode;
            }
            $this->head = $newNode;
        } elseif ($index === $this->size) {
            $this->add($item);
            return;
        } else {
            $current = $this->getNodeAt($index);
            $prevNode = $current->prev;

            if ($prevNode) {
                $prevNode->next = $newNode;
                $newNode->prev = $prevNode;
            }
            $newNode->next = $current;
            $current->prev = $newNode;
        }

        $this->size++;
    }

    public function get(int $index): mixed
    {
        return $this->getNodeAt($index)->data;
    }

    public function set(int $index, mixed $item): void
    {
        $this->getNodeAt($index)->data = $item;
    }

    public function removeAt(int $index): mixed
    {
        $nodeToRemove = $this->getNodeAt($index);
        $data = $nodeToRemove->data;

        if ($nodeToRemove->prev) {
            $nodeToRemove->prev->next = $nodeToRemove->next;
        } else {
            $this->head = $nodeToRemove->next;
        }

        if ($nodeToRemove->next) {
            $nodeToRemove->next->prev = $nodeToRemove->prev;
        } else {
            $this->tail = $nodeToRemove->prev;
        }

        $this->size--;
        return $data;
    }

    public function clear(): void
    {
        $this->head = null;
        $this->tail = null;
        $this->size = 0;
    }

    public function isEmpty(): bool
    {
        return $this->size === 0;
    }

    public function count(): int
    {
        return $this->size;
    }

    public function getIterator(): \Traversable
    {
        $current = $this->head;
        while ($current) {
            yield $current->data;
            $current = $current->next;
        }
    }

    private function getNodeAt(int $index): Node
    {
        if ($index < 0 || $index >= $this->size) {
            throw new \OutOfBoundsException("Index out of bounds");
        }

        $current = $this->head;
        for ($i = 0; $i < $index; $i++) {
            $current = $current->next;
        }

        return $current;
    }
}
