<?php

namespace App\Services;

use App\Models\Media;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use RuntimeException;

class MediaService
{
    /**
     * Store uploaded file and create media record.
     */
    public function store(UploadedFile $file, ?string $title = null, ?string $alt = null, ?int $userId = null): Media
    {
        if (!$file->isValid()) {
            throw new RuntimeException('Upload failed: ' . $file->getErrorMessage());
        }

        $year = date('Y');
        $month = date('m');
        $directory = "uploads/{$year}/{$month}";

        $originalName = $file->getClientOriginalName();
        $extension = strtolower($file->getClientOriginalExtension() ?: $file->guessExtension() ?: 'bin');
        $filename = time() . '_' . uniqid() . '.' . $extension;
        $relativePath = "{$directory}/{$filename}";

        $mime = $file->getClientMimeType() ?: $file->getMimeType() ?: 'application/octet-stream';
        $size = (int) $file->getSize();

        $this->persistUploadedFile($file, $directory, $filename, $relativePath, $size);

        $width = null;
        $height = null;
        if (str_starts_with($mime, 'image/')) {
            $absolutePath = $this->absolutePath($relativePath);
            if ($absolutePath && is_file($absolutePath)) {
                $imageInfo = @getimagesize($absolutePath);
                if ($imageInfo !== false) {
                    $width = $imageInfo[0];
                    $height = $imageInfo[1];
                }
            }
        }

        return Media::create([
            'disk' => 'public',
            'path' => $relativePath,
            'mime' => $mime,
            'size' => $size,
            'width' => $width,
            'height' => $height,
            'title' => $title ?? pathinfo($originalName, PATHINFO_FILENAME),
            'alt' => $alt ?? $title ?? pathinfo($originalName, PATHINFO_FILENAME),
            'created_by' => $userId,
        ])->fresh();
    }

    /**
     * Write the uploaded file to storage/app/public/uploads/YYYY/MM.
     * Never returns unless the file exists on disk with content.
     */
    private function persistUploadedFile(
        UploadedFile $file,
        string $directory,
        string $filename,
        string $relativePath,
        int $expectedSize
    ): void {
        $absolutePath = $this->absolutePath($relativePath);
        if (!$absolutePath) {
            throw new RuntimeException('Invalid upload path.');
        }

        File::ensureDirectoryExists(dirname($absolutePath));

        $tempPath = $file->getRealPath();
        if ($tempPath && is_file($tempPath)) {
            if (@copy($tempPath, $absolutePath)) {
                $this->assertFileOnDisk($absolutePath, $relativePath, $expectedSize);
                return;
            }

            $contents = @file_get_contents($tempPath);
            if ($contents !== false && Storage::disk('public')->put($relativePath, $contents)) {
                $this->assertFileOnDisk($absolutePath, $relativePath, $expectedSize);
                return;
            }
        }

        Log::warning('Media upload: copy failed, using move fallback.', [
            'directory' => $directory,
            'filename' => $filename,
            'absolute_path' => $absolutePath,
        ]);

        if (!$file->isValid()) {
            throw new RuntimeException('Upload temp file is no longer available.');
        }

        $moved = $file->move(dirname($absolutePath), $filename);
        $this->assertFileOnDisk($moved->getPathname(), $relativePath, $expectedSize);
    }

    private function assertFileOnDisk(string $absolutePath, string $relativePath, int $expectedSize): void
    {
        if (!is_file($absolutePath)) {
            throw new RuntimeException("Uploaded file was not saved: {$relativePath}");
        }

        $bytesOnDisk = (int) filesize($absolutePath);
        if ($bytesOnDisk <= 0) {
            @unlink($absolutePath);
            throw new RuntimeException('Uploaded file is empty on disk.');
        }

        if ($expectedSize > 0 && $bytesOnDisk !== $expectedSize) {
            Log::warning('Media upload size mismatch after save.', [
                'path' => $relativePath,
                'expected' => $expectedSize,
                'actual' => $bytesOnDisk,
            ]);
        }

        Log::info('Media file saved.', [
            'path' => $relativePath,
            'absolute_path' => $absolutePath,
            'bytes' => $bytesOnDisk,
        ]);
    }

    private function absolutePath(string $relativePath): ?string
    {
        $normalized = str_replace('\\', '/', ltrim($relativePath, '/'));
        return storage_path('app/public/' . str_replace('/', DIRECTORY_SEPARATOR, $normalized));
    }

    /**
     * Delete media file and record.
     */
    public function delete(Media $media): bool
    {
        try {
            Log::info('MediaService delete called', [
                'media_id' => $media->id,
                'path' => $media->path,
                'disk' => $media->disk,
            ]);

            $disk = Storage::disk($media->disk);

            if ($disk->exists($media->path)) {
                $disk->delete($media->path);
                Log::info('Original file deleted', ['path' => $media->path]);
            } else {
                $absolutePath = $this->absolutePath($media->path);
                if ($absolutePath && is_file($absolutePath)) {
                    @unlink($absolutePath);
                    Log::info('Original file deleted via fallback path', ['path' => $absolutePath]);
                } else {
                    Log::warning('Original file does not exist', ['path' => $media->path]);
                }
            }

            try {
                $pathInfo = pathinfo($media->path);

                if (isset($pathInfo['dirname'], $pathInfo['filename'], $pathInfo['extension'])) {
                    $thumbPath = $pathInfo['dirname'] . '/' . $pathInfo['filename'] . '_thumb.' . $pathInfo['extension'];
                    if ($disk->exists($thumbPath)) {
                        $disk->delete($thumbPath);
                        Log::info('Thumbnail deleted', ['path' => $thumbPath]);
                    }

                    $webpPath = $pathInfo['dirname'] . '/' . $pathInfo['filename'] . '.webp';
                    if ($disk->exists($webpPath)) {
                        $disk->delete($webpPath);
                        Log::info('WebP version deleted', ['path' => $webpPath]);
                    }
                }
            } catch (\Exception $e) {
                Log::warning('Could not delete related files: ' . $e->getMessage());
            }

            $result = $media->delete();

            Log::info('Database delete result', [
                'result' => $result,
                'media_id' => $media->id,
            ]);

            return $result !== false;
        } catch (\Exception $e) {
            Log::error('MediaService delete exception', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            throw $e;
        }
    }
}
