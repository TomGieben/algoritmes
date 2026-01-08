<?php

namespace Algoritmes\Algoritmes;

use Algoritmes\Enums\SortDirection;
use Algoritmes\Interfaces\IsComparator;
use Algoritmes\Interfaces\IsList;
use Algoritmes\Interfaces\IsSorter;

final class MergeSort implements IsSorter
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
        // Base case: lijst van 0 of 1 element is al gesorteerd
        if ($this->list->size() <= 1) {
            return $this->list;
        }

        // Splits lijst in twee helften
        $mid = (int)($this->list->size() / 2);
        $leftList = new LinkedList();
        $rightList = new LinkedList();

        for ($i = 0; $i < $mid; $i++) {
            $leftList->add($this->list->get($i));
        }

        for ($i = $mid; $i < $this->list->size(); $i++) {
            $rightList->add($this->list->get($i));
        }

        // Recursief sorteer beide helften
        $leftSorter = new MergeSort($leftList, $this->comparator);
        $rightSorter = new MergeSort($rightList, $this->comparator);

        // Voeg gesorteerde helften samen
        return $this->merge(
            $leftSorter->sort($direction),
            $rightSorter->sort($direction),
            $direction
        );
    }

    private function merge(IsList $left, IsList $right, SortDirection $direction): IsList
    {
        $mergedList = new LinkedList();
        $i = 0;
        $j = 0;

        // Vergelijk elementen van beide lijsten en voeg kleinste toe
        while ($i < $left->size() && $j < $right->size()) {
            $leftItem = $left->get($i);
            $rightItem = $right->get($j);

            $compare = $this->comparator->compare($leftItem, $rightItem);

            if (($direction === SortDirection::ASCENDING && $compare <= 0) ||
                ($direction === SortDirection::DESCENDING && $compare >= 0)
            ) {
                $mergedList->add($leftItem);
                $i++;
            } else {
                $mergedList->add($rightItem);
                $j++;
            }
        }

        // Voeg resterende elementen toe
        while ($i < $left->size()) {
            $mergedList->add($left->get($i));
            $i++;
        }

        while ($j < $right->size()) {
            $mergedList->add($right->get($j));
            $j++;
        }

        return $mergedList;
    }
}
