<?php

namespace Algoritmes\Interfaces;

interface IsList
{
    /** Adds an element to the end of the list */
    public function add(mixed $element): void;

    /** Inserts an element at a specific index */
    public function insert(int $index, mixed $element): void;

    /** Retrieves an element at a specific index */
    public function get(int $index): mixed;

    /** Updates an element at a specific index */
    public function set(int $index, mixed $element): void;

    /** Removes an element at a specific index */
    public function removeAt(int $index): mixed;

    /** Removes the first occurrence of an element */
    public function size(): int;

    /** Returns the number of elements in the list */
    public function isEmpty(): bool;

    /** Checks if the list is empty */
    public function clear(): void;
}
