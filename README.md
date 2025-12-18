# Algoritmes

This library provides classic data structures and algorithms implemented in PHP 7.4+.

## Recent Architecture Notes
- Lists now expose a `RandomAccessListInterface` marker so algorithms such as merge sort, quick sort, and binary search can enforce fast `get`/`set` semantics while still accepting any `ListInterface` via the `RandomAccessAdapter`.
- Comparator resolution lives in `Algoritmes\Common\ComparatorResolver`, keeping consistent ordering semantics across searchers and sorters.
- The graph module stores `Edge` value objects internally and Dijkstra's implementation now relies on the shared binary heap based `PriorityQueue` for `O((V + E) log V)` performance.

## Running Tests
Execute the PHPUnit suite from the project root:

```powershell
php -d memory_limit=2G vendor/bin/phpunit tests
```

> The dataset specs load the full 1M primes CSV, so allocating at least 2 GB of memory is recommended to avoid exhaustion errors.
