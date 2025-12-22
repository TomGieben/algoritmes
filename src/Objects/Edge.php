<?php

namespace Algoritmes\Objects;

class Edge
{
    private mixed $from;
    private mixed $to;
    private float $weight;

    public function __construct(mixed $from, mixed $to, float $weight = 1.0)
    {
        $this->from = $from;
        $this->to = $to;
        $this->weight = $weight;
    }

    public function getFrom(): mixed
    {
        return $this->from;
    }

    public function getTo(): mixed
    {
        return $this->to;
    }

    public function getWeight(): float
    {
        return $this->weight;
    }
}
