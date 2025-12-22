<?php

namespace Algoritmes\Algoritmes;

use Algoritmes\Interfaces\IsList;
use Algoritmes\Objects\DoublyNode;

final class DoublyLinkedList implements IsList
{
    private ?DoublyNode $head = null;
    private ?DoublyNode $tail = null;
    private int $size = 0;

    public function add(mixed $element): void
    {
        $this->insert($this->size, $element);
    }

    public function insert(int $index, mixed $element): void
    {
        $this->assertInsertIndex($index);

        $newNode = new DoublyNode($element);

        if ($index === 0) {
            $newNode->next = $this->head;

            if ($this->head !== null) {
                $this->head->prev = $newNode;
            } else {
                $this->tail = $newNode;
            }

            $this->head = $newNode;
        } elseif ($index === $this->size) {
            $newNode->prev = $this->tail;

            if ($this->tail !== null) {
                $this->tail->next = $newNode;
            } else {
                $this->head = $newNode;
            }

            $this->tail = $newNode;
        } else {
            $current = $this->nodeAt($index);
            $previous = $current->prev;

            $newNode->next = $current;
            $newNode->prev = $previous;
            $previous->next = $newNode;
            $current->prev = $newNode;
        }

        $this->size++;
    }

    public function get(int $index): mixed
    {
        $this->assertElementIndex($index);

        return $this->nodeAt($index)->value;
    }

    public function set(int $index, mixed $element): void
    {
        $this->assertElementIndex($index);

        $this->nodeAt($index)->value = $element;
    }

    public function removeAt(int $index): mixed
    {
        $this->assertElementIndex($index);

        if ($index === 0) {
            $removedNode = $this->head;
            $this->head = $this->head->next;

            if ($this->head !== null) {
                $this->head->prev = null;
            } else {
                $this->tail = null;
            }
        } elseif ($index === $this->size - 1) {
            $removedNode = $this->tail;
            $this->tail = $this->tail->prev;

            if ($this->tail !== null) {
                $this->tail->next = null;
            } else {
                $this->head = null;
            }
        } else {
            $removedNode = $this->nodeAt($index);
            $removedNode->prev->next = $removedNode->next;
            $removedNode->next->prev = $removedNode->prev;
        }

        $this->size--;

        return $removedNode->value;
    }

    public function size(): int
    {
        return $this->size;
    }

    public function isEmpty(): bool
    {
        return $this->size === 0;
    }

    public function clear(): void
    {
        $current = $this->head;

        while ($current !== null) {
            $next = $current->next;
            $current->prev = null;
            $current->next = null;
            $current = $next;
        }

        $this->head = null;
        $this->tail = null;
        $this->size = 0;
    }

    /** Returns the node at the requested index by walking from the closest end. */
    private function nodeAt(int $index): DoublyNode
    {
        if ($index <= $this->size / 2) {
            $current = $this->head;

            for ($i = 0; $i < $index; $i++) {
                $current = $current->next;
            }

            return $current;
        }

        $current = $this->tail;

        for ($i = $this->size - 1; $i > $index; $i--) {
            $current = $current->prev;
        }

        return $current;
    }

    private function assertElementIndex(int $index): void
    {
        if ($index < 0 || $index >= $this->size) {
            throw new \OutOfBoundsException("Index $index is out of bounds.");
        }
    }

    private function assertInsertIndex(int $index): void
    {
        if ($index < 0 || $index > $this->size) {
            throw new \OutOfBoundsException("Index $index is out of bounds.");
        }
    }
}
