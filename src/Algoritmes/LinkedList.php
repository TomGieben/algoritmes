<?php

namespace Algoritmes\Algoritmes;

use Algoritmes\Interfaces\IsList;
use Algoritmes\Objects\Node;

final class LinkedList implements IsList
{
    private ?Node $head = null;
    private ?Node $tail = null;
    private int $size = 0;
    private ?string $elementType = null;

    public function add(mixed $element): void
    {
        $this->insert($this->size, $element);
    }

    public function insert(int $index, mixed $element): void
    {
        $this->assertInsertIndex($index);
        $this->assertElementType($element);

        $newNode = new Node($element);

        if ($index === 0) {  // Voeg toe aan begin
            $newNode->next = $this->head;
            $this->head = $newNode;

            if ($this->tail === null) {
                $this->tail = $newNode;
            }
        } elseif ($index === $this->size) {  // Voeg toe aan einde
            if ($this->tail !== null) {
                $this->tail->next = $newNode;
            }

            $this->tail = $newNode;
            if ($this->head === null) {
                $this->head = $newNode;
            }
        } else {  // Voeg toe in het midden
            $previous = $this->nodeAt($index - 1);
            $newNode->next = $previous->next;
            $previous->next = $newNode;
        }

        $this->size++;
    }

    public function get(int $index): mixed
    {
        $this->assertElementIndex($index);

        return $this->nodeAt($index)->data;
    }

    public function set(int $index, mixed $element): void
    {
        $this->assertElementIndex($index);

        $this->nodeAt($index)->data = $element;
    }

    public function removeAt(int $index): mixed
    {
        $this->assertElementIndex($index);

        if ($index === 0) {  // Verwijder eerste element
            $removedNode = $this->head;
            $this->head = $this->head->next;

            if ($this->head === null) {
                $this->tail = null;
            }
        } else {  // Verwijder element in midden of einde
            $previous = $this->nodeAt($index - 1);
            $removedNode = $previous->next;
            $previous->next = $removedNode->next;

            if ($previous->next === null) {
                $this->tail = $previous;
            }
        }

        $this->size--;

        return $removedNode->data;
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
            $current->next = null;
            $current = $next;
        }

        $this->head = null;
        $this->tail = null;
        $this->size = 0;
    }

    /** Returns the node located at the provided index (expects a valid index). */
    private function nodeAt(int $index): Node
    {
        $current = $this->head;

        // Loop vanaf head naar index
        for ($i = 0; $i < $index; $i++) {
            $current = $current->next;
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

    /**
     * Validates that the element matches the list's type.
     * On first element, locks the list to that type.
     */
    private function assertElementType(mixed $element): void
    {
        $elementType = get_debug_type($element);

        if ($this->elementType === null) {
            $this->elementType = $elementType;
        } elseif ($elementType !== $this->elementType) {
            throw new \TypeError(
                "List expects elements of type {$this->elementType}, got {$elementType}"
            );
        }
    }
}
