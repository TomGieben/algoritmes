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
        $array = [];
        for ($i = 0; $i < $this->list->size(); $i++) {
            $array[] = $this->list->get($i);
        }

        $this->quickSort($array, 0, count($array) - 1, $direction);

        $sortedList = clone $this->list;
        for ($i = 0; $i < count($array); $i++) {
            $sortedList->set($i, $array[$i]);
        }

        return $sortedList;
    }

    private function quickSort(array &$array, int $low, int $high, SortDirection $direction): void
    {
        if ($low < $high) {
            $pi = $this->partition($array, $low, $high, $direction);

            $this->quickSort($array, $low, $pi - 1, $direction);
            $this->quickSort($array, $pi + 1, $high, $direction);
        }
    }

    private function partition(array &$array, int $low, int $high, SortDirection $direction): int
    {
        $pivot = $array[$high];
        $i = $low - 1;

        for ($j = $low; $j < $high; $j++) {
            $comparison = $this->comparator->compare($array[$j], $pivot);
            $compare = ($direction === SortDirection::ASCENDING) ? ($comparison < 0) : ($comparison > 0);
            if ($compare) {
                $i++;
                [$array[$i], $array[$j]] = [$array[$j], $array[$i]];
            }
        }

        [$array[$i + 1], $array[$high]] = [$array[$high], $array[$i + 1]];
        return $i + 1;
    }
}
