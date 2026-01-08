<?php

namespace Algoritmes\Algoritmes;

use Algoritmes\Enums\SortDirection;
use Algoritmes\Interfaces\IsComparator;
use Algoritmes\Interfaces\IsList;
use Algoritmes\Interfaces\IsSorter;

final class QuickSort implements IsSorter
{
    private IsList $list;
    private IsComparator $comparator;

    public function __construct(IsList $list, IsComparator $comparator)
    {
        $this->list = $list;
        $this->comparator = $comparator;
    }

    public function sort(SortDirection $direction = SortDirection::ASCENDING): IsList
    {
        // Converteer lijst naar array voor snellere bewerkingen
        $array = [];
        for ($i = 0; $i < $this->list->size(); $i++) {
            $array[] = $this->list->get($i);
        }

        // Voer QuickSort uit (in-place sortering)
        $this->quickSort($array, 0, count($array) - 1, $direction);

        // Converteer gesorteerde array terug naar lijst
        $sortedList = clone $this->list;
        for ($i = 0; $i < count($array); $i++) {
            $sortedList->set($i, $array[$i]);
        }

        return $sortedList;
    }

    private function quickSort(array &$array, int $low, int $high, SortDirection $direction): void
    {
        if ($low < $high) {
            // Partition: splits array rond pivot
            $pi = $this->partition($array, $low, $high, $direction);

            // Sorteer recursief linker en rechter deel
            $this->quickSort($array, $low, $pi - 1, $direction);
            $this->quickSort($array, $pi + 1, $high, $direction);
        }
    }

    private function partition(array &$array, int $low, int $high, SortDirection $direction): int
    {
        $pivot = $array[$high];  // Kies laatste element als pivot
        $i = $low - 1;  // Grens tussen kleiner/groter dan pivot

        for ($j = $low; $j < $high; $j++) {
            $comparison = $this->comparator->compare($array[$j], $pivot);
            $compare = ($direction === SortDirection::ASCENDING) ? ($comparison < 0) : ($comparison > 0);
            if ($compare) {
                $i++;
                [$array[$i], $array[$j]] = [$array[$j], $array[$i]];  // Swap naar linker sectie
            }
        }

        // Plaats pivot op finale positie
        [$array[$i + 1], $array[$high]] = [$array[$high], $array[$i + 1]];
        return $i + 1;
    }
}
