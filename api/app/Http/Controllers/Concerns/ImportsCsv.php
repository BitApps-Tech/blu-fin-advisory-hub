<?php

namespace App\Http\Controllers\Concerns;

use App\Support\CsvReader;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

trait ImportsCsv
{
    /**
     * @return array<int, array<string, string>>
     */
    protected function parseCsvUpload(Request $request): array
    {
        $request->validate([
            'file' => 'required|file|mimes:csv,txt|max:5120',
        ]);

        return CsvReader::read($request->file('file'));
    }

    protected function toBool(?string $value, bool $default = true): bool
    {
        if ($value === null || $value === '') {
            return $default;
        }

        return in_array(strtolower($value), ['1', 'true', 'yes', 'on'], true);
    }

    protected function nullableInt(?string $value): ?int
    {
        if ($value === null || $value === '') {
            return null;
        }

        return (int) $value;
    }

    protected function slugOrGenerate(?string $slug, string $source): string
    {
        $slug = trim((string) $slug);

        if ($slug !== '') {
            return $slug;
        }

        return Str::slug($source);
    }

  /**
   * @param array<int, array{row: int, message: string}> $errors
   */
    protected function importResult(int $imported, array $errors): JsonResponse
    {
        $failed = count($errors);

        return response()->json([
            'success' => $failed === 0,
            'message' => $failed > 0
                ? "Imported {$imported} record(s). {$failed} row(s) failed."
                : "Successfully imported {$imported} record(s).",
            'data' => [
                'imported' => $imported,
                'failed' => $failed,
                'errors' => $errors,
            ],
        ], $imported > 0 ? 200 : 422);
    }
}
