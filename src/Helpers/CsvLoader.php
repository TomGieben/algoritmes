<?php

namespace Algoritmes\Helpers;

final class CsvLoader
{
    public static function loadToArray(string $filePath, bool $hasHeader = true): array
    {
        if (!file_exists($filePath)) {
            throw new \InvalidArgumentException("File not found: $filePath");
        }

        $data = [];
        $handle = fopen($filePath, 'r');

        if ($handle === false) {
            throw new \RuntimeException("Unable to open file: $filePath");
        }

        if ($hasHeader) {
            fgetcsv($handle); // Skip header
        }

        while (($row = fgetcsv($handle)) !== false) {
            $data[] = $row;
        }

        fclose($handle);
        return $data;
    }

    public static function loadColumnToArray(string $filePath, int $columnIndex = 1, bool $hasHeader = true): array
    {
        $rows = self::loadToArray($filePath, $hasHeader);
        $column = [];

        foreach ($rows as $row) {
            if (isset($row[$columnIndex])) {
                $column[] = $row[$columnIndex];
            }
        }

        return $column;
    }
}
