<?php

namespace App\Support;

use Illuminate\Http\UploadedFile;

class CsvReader
{
    /**
     * @return array<int, array<string, string>>
     */
    public static function read(UploadedFile $file): array
    {
        $rows = [];
        $handle = fopen($file->getRealPath(), 'r');

        if ($handle === false) {
            throw new \RuntimeException('Unable to read CSV file.');
        }

        $headers = null;

        while (($data = fgetcsv($handle)) !== false) {
            if ($headers === null) {
                $headers = array_map(
                    static fn ($header) => strtolower(trim((string) $header)),
                    $data
                );
                continue;
            }

            if (count(array_filter($data, static fn ($value) => trim((string) $value) !== '')) === 0) {
                continue;
            }

            $row = [];
            foreach ($headers as $index => $header) {
                if ($header === '') {
                    continue;
                }
                $row[$header] = isset($data[$index]) ? trim((string) $data[$index]) : '';
            }

            $rows[] = $row;
        }

        fclose($handle);

        return $rows;
    }
}
