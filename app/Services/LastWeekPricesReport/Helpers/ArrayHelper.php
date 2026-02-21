<?php
declare(strict_types=1);

namespace App\Services\LastWeekPricesReport\Helpers;

class ArrayHelper
{
    /**
     * @param array[] $array
     * @param string $delimiter
     * @param string $enclosure
     * @return string
     */
    public static function arrayToCsv(array $array, string $delimiter = ',', string $enclosure = '"'): string
    {
        $handle = fopen('php://memory', 'rb+');

        foreach ($array as $row) {
            fputcsv($handle, $row, $delimiter, $enclosure);
        }

        rewind($handle);
        $csvString = stream_get_contents($handle);
        fclose($handle);

        return $csvString;
    }
}
