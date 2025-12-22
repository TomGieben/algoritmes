<?php

namespace Algoritmes\Objects;

class PrioritizedObject
{
    public mixed $value;
    public float $priority;

    public function __construct(mixed $value, float $priority)
    {
        $this->value = $value;
        $this->priority = $priority;
    }
}
