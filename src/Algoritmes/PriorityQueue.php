<?php

namespace Algoritmes\Algoritmes;

use Algoritmes\Interfaces\IsList;
use Algoritmes\Objects\PrioritizedObject;

final class PriorityQueue
{
    private ?IsList $list = null;

    public function __construct(IsList $list)
    {
        $this->list = $list;
    }

    public function enqueue(mixed $value, float $priority): void
    {
        $prioritizedObject = new PrioritizedObject($value, $priority);

        if ($this->list->isEmpty()) {
            $this->list->add($prioritizedObject);
            return;
        }

        $index = 0;
        while ($index < $this->list->size()) {
            $current = $this->list->get($index);
            if ($priority < $current->priority) {
                break;
            }
            $index++;
        }

        $this->list->insert($index, $prioritizedObject);
    }

    public function dequeue(): mixed
    {
        if ($this->list->isEmpty()) {
            throw new \UnderflowException("Priority queue is empty");
        }

        $prioritizedObject = $this->list->removeAt(0);
        return $prioritizedObject->value;
    }

    public function peek(): mixed
    {
        if ($this->list->isEmpty()) {
            throw new \UnderflowException("Priority queue is empty");
        }

        $prioritizedObject = $this->list->get(0);
        return $prioritizedObject->value;
    }

    public function isEmpty(): bool
    {
        return $this->list->isEmpty();
    }

    public function size(): int
    {
        return $this->list->size();
    }
}
