<?php

namespace Algoritmes\Algoritmes;

use Algoritmes\Interfaces\IsList;
use Algoritmes\Interfaces\IsMap;

final class HashTable implements IsMap
{
    private IsList $list;
    private int $size;

    public function __construct(int $size = 101)
    {
        $this->size = $size;
        $this->list = new DoublyLinkedList();
        // Initialiseer met lege buckets
        for ($i = 0; $i < $size; $i++) {
            $this->list->add([]);
        }
    }

    private function hash(mixed $key): int
    {
        $modulo = $this->size;
        // CRC32 hash functie, modulo voor bucket index
        return crc32((string)$key) % $modulo;
    }

    public function set(mixed $key, mixed $value): void
    {
        $index = $this->hash($key);  // Bereken bucket index
        $bucket = $this->list->get($index);

        // Check of sleutel al bestaat (update waarde)
        foreach ($bucket as &$pair) {
            if ($pair[0] === $key) {
                $pair[1] = $value;
                return;
            }
        }

        // Anders voeg nieuw sleutel-waarde paar toe
        $bucket[] = [$key, $value];
        $this->list->set($index, $bucket);
    }

    public function get(mixed $key): mixed
    {
        $index = $this->hash($key);  // Bereken bucket index
        $bucket = $this->list->get($index);

        // Zoek sleutel in bucket (collision handling)
        foreach ($bucket as $pair) {
            if ($pair[0] === $key) {
                return $pair[1];
            }
        }

        return null;  // Niet gevonden
    }

    public function remove(mixed $key): void
    {
        $index = $this->hash($key);
        $bucket = $this->list->get($index);

        foreach ($bucket as $i => $pair) {
            if ($pair[0] === $key) {
                unset($bucket[$i]);
                $this->list->set($index, array_values($bucket));
                return;
            }
        }
    }

    public function has(mixed $key): bool
    {
        $index = $this->hash($key);
        $bucket = $this->list->get($index);

        foreach ($bucket as $pair) {
            if ($pair[0] === $key) {
                return true;
            }
        }

        return false;
    }

    public function size(): int
    {
        return $this->size;
    }

    public function isEmpty(): bool
    {
        for ($i = 0; $i < $this->size; $i++) {
            $bucket = $this->list->get($i);
            if (!empty($bucket)) {
                return false;
            }
        }
        return true;
    }

    public function clear(): void
    {
        for ($i = 0; $i < $this->size; $i++) {
            $this->list->set($i, []);
        }
    }
}
