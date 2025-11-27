<?php

declare(strict_types=1);

require_once __DIR__ . '/../vendor/autoload.php';

use Algoritmes\Datasets\ListDataset;
use Algoritmes\Datasets\PrimeDataset;
use Algoritmes\Sort\QuickSort;
use Algoritmes\Sort\MergeSort;
use Algoritmes\Search\BinarySearch;
use Algoritmes\Lists\ArrayList;
use Algoritmes\Lists\LinkedList;
use Algoritmes\Lists\PriorityQueue;
use Algoritmes\HashTable\HashTable;

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    exit(0);
}

$action = $_GET['action'] ?? '';

try {
    $response = match ($action) {
        'get_dataset' => getDataset(),
        'run_algorithm' => runAlgorithm(),
        default => ['error' => 'Unknown action']
    };
} catch (Exception $e) {
    $response = ['error' => $e->getMessage()];
}

echo json_encode($response, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);

/**
 * Get a dataset based on the type parameter
 */
function getDataset(): array
{
    $type = $_GET['type'] ?? 'integers';
    $size = (int)($_GET['size'] ?? 1000);
    $customData = $_POST['custom_data'] ?? '';

    $data = [];
    $columns = [];

    switch ($type) {
        case 'integers':
            $data = ListDataset::getIntegerDataset();
            $columns = ['value'];
            break;

        case 'strings':
            $data = ListDataset::getStringDataset();
            $columns = ['value'];
            break;

        case 'sorted_integers':
            $data = ListDataset::getSortedIntegerDataset();
            $columns = ['value'];
            break;

        case 'sorted_strings':
            $data = ListDataset::getSortedStringDataset();
            $columns = ['value'];
            break;

        case 'duplicates':
            $data = ListDataset::getDuplicateDataset();
            $columns = ['value'];
            break;

        case 'large_integers':
            $size = min($size, 10000); // Limit to 10k
            $data = ListDataset::getLargeIntegerDataset($size);
            $columns = ['value'];
            break;

        case 'primes_100':
            $primeDataset = new PrimeDataset();
            $primeDataset->load();
            $rawData = $primeDataset->slice(0, 100);
            $data = array_column($rawData, 'Num');
            $columns = ['Rank', 'Num', 'Interval'];
            break;

        case 'primes_1000':
            $primeDataset = new PrimeDataset();
            $primeDataset->load();
            $rawData = $primeDataset->slice(0, 1000);
            $data = array_column($rawData, 'Num');
            $columns = ['Rank', 'Num', 'Interval'];
            break;

        case 'custom':
            $customData = trim($customData);
            if (empty($customData)) {
                return ['error' => 'No custom data provided'];
            }
            $parts = array_map('trim', explode(',', $customData));
            // Try to parse as numbers first
            $allNumeric = true;
            foreach ($parts as $part) {
                if (!is_numeric($part)) {
                    $allNumeric = false;
                    break;
                }
            }
            $data = $allNumeric ? array_map('floatval', $parts) : $parts;
            $columns = ['value'];
            break;

        default:
            return ['error' => 'Unknown dataset type'];
    }

    return [
        'success' => true,
        'type' => $type,
        'data' => $data,
        'columns' => $columns,
        'count' => count($data),
        'dataType' => !empty($data) && is_numeric($data[0]) ? 'numeric' : 'string'
    ];
}

/**
 * Run an algorithm on provided data
 */
function runAlgorithm(): array
{
    $input = json_decode(file_get_contents('php://input'), true);

    if (!$input) {
        return ['error' => 'Invalid JSON input'];
    }

    $algorithm = $input['algorithm'] ?? '';
    $data = $input['data'] ?? [];
    $target = $input['target'] ?? null;

    if (empty($data)) {
        return ['error' => 'No data provided'];
    }

    $startTime = microtime(true);
    $result = [];
    $details = [];

    switch ($algorithm) {
        case 'quicksort':
            $sorter = new QuickSort();
            $result = $sorter->sort($data);
            $details = [
                'algorithm' => 'QuickSort',
                'complexity' => 'O(n log n) gemiddeld, O(n²) slechtste geval',
                'stable' => false
            ];
            break;

        case 'mergesort':
            $sorter = new MergeSort();
            $result = $sorter->sort($data);
            $details = [
                'algorithm' => 'MergeSort',
                'complexity' => 'O(n log n) alle gevallen',
                'stable' => true
            ];
            break;

        case 'binarysearch':
            $searcher = new BinarySearch();
            $index = $searcher->search($data, $target);
            $result = [
                'found' => $index >= 0,
                'index' => $index,
                'value' => $index >= 0 ? $data[$index] : null
            ];
            $details = [
                'algorithm' => 'Binary Search',
                'complexity' => 'O(log n)',
                'requirement' => 'Data moet gesorteerd zijn'
            ];
            break;

        case 'binarysearch_left':
            $searcher = new BinarySearch();
            $index = $searcher->searchLeftmost($data, $target);
            $result = [
                'found' => $index >= 0,
                'index' => $index,
                'value' => $index >= 0 ? $data[$index] : null
            ];
            $details = [
                'algorithm' => 'Binary Search (Leftmost)',
                'complexity' => 'O(log n)',
                'description' => 'Vindt de eerste voorkomst'
            ];
            break;

        case 'binarysearch_right':
            $searcher = new BinarySearch();
            $index = $searcher->searchRightmost($data, $target);
            $result = [
                'found' => $index >= 0,
                'index' => $index,
                'value' => $index >= 0 ? $data[$index] : null
            ];
            $details = [
                'algorithm' => 'Binary Search (Rightmost)',
                'complexity' => 'O(log n)',
                'description' => 'Vindt de laatste voorkomst'
            ];
            break;

        case 'arraylist':
            $type = !empty($data) && is_numeric($data[0]) ? 'integer' : 'string';
            $list = new ArrayList($type);
            foreach ($data as $item) {
                $list->add($type === 'integer' ? (int)$item : (string)$item);
            }
            $result = [
                'items' => iterator_to_array($list),
                'count' => $list->count(),
                'type' => $type
            ];
            $details = [
                'algorithm' => 'ArrayList',
                'complexity' => 'O(1) access, O(n) insert/remove',
                'description' => 'Type-safe dynamische array'
            ];
            break;

        case 'linkedlist':
            $list = new LinkedList();
            foreach ($data as $item) {
                $list->add($item);
            }
            $result = [
                'items' => iterator_to_array($list),
                'count' => $list->count(),
                'first' => $list->count() > 0 ? $list->get(0) : null,
                'last' => $list->count() > 0 ? $list->get($list->count() - 1) : null
            ];
            $details = [
                'algorithm' => 'LinkedList',
                'complexity' => 'O(n) access, O(1) insert/remove aan begin/eind',
                'description' => 'Dubbel gelinkte lijst'
            ];
            break;

        case 'priorityqueue':
            $queue = new PriorityQueue();
            foreach ($data as $index => $item) {
                // Use value as priority for numbers, or index for strings
                $priority = is_numeric($item) ? (int)$item : $index;
                $queue->enqueue($item, $priority);
            }
            $dequeued = [];
            while (!$queue->isEmpty()) {
                $dequeued[] = $queue->dequeue();
            }
            $result = [
                'ordered' => $dequeued,
                'description' => 'Items gedequeued op prioriteit (laagste eerst)'
            ];
            $details = [
                'algorithm' => 'PriorityQueue',
                'complexity' => 'O(log n) enqueue/dequeue',
                'description' => 'Priority queue (lagere waarde = hogere prioriteit)'
            ];
            break;

        case 'hashtable':
            $table = new HashTable();
            foreach ($data as $index => $item) {
                $key = is_numeric($item) ? "key_$item" : (string)$item;
                $table->put($key, $item);
            }
            $result = [
                'keys' => $table->keys(),
                'values' => $table->values(),
                'count' => $table->count(),
                'collisionStats' => $table->getCollisionStats()
            ];
            $details = [
                'algorithm' => 'HashTable',
                'complexity' => 'O(1) gemiddeld voor get/put',
                'description' => 'Hash table met separate chaining'
            ];
            break;

        default:
            return ['error' => 'Unknown algorithm: ' . $algorithm];
    }

    $endTime = microtime(true);
    $executionTime = ($endTime - $startTime) * 1000; // Convert to milliseconds

    return [
        'success' => true,
        'algorithm' => $algorithm,
        'result' => $result,
        'details' => $details,
        'executionTime' => round($executionTime, 4),
        'executionTimeFormatted' => formatTime($executionTime),
        'inputCount' => count($data)
    ];
}

/**
 * Format execution time nicely
 */
function formatTime(float $ms): string
{
    if ($ms < 1) {
        return round($ms * 1000, 2) . ' μs';
    } elseif ($ms < 1000) {
        return round($ms, 2) . ' ms';
    } else {
        return round($ms / 1000, 2) . ' s';
    }
}
