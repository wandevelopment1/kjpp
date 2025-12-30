<?php

namespace App\Services\Upload;

use Illuminate\Http\UploadedFile;

class BasePathUploadService
{
    protected string $baseFolder;

    public function __construct(string $baseFolder = 'uploads')
    {
        $this->baseFolder = trim($baseFolder, '/');
    }

    /**
     * Upload file ke public_html/storage
     */
    public function upload(UploadedFile $file): string
    {
        $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
        $storagePath = base_path("../public_html/storage/{$this->baseFolder}");

        if (!file_exists($storagePath)) {
            mkdir($storagePath, 0775, true);
        }

        $file->move($storagePath, $filename);

        return "{$this->baseFolder}/{$filename}";
    }

    /**
     * Hapus file
     */
    public function delete(?string $filePath): bool
{
    if (!$filePath) {
        return false; // path kosong
    }

    $fullPath = base_path("../public_html/storage/{$filePath}");
    if (file_exists($fullPath)) {
        return unlink($fullPath);
    }

    // file tidak ada di path fisik
    return false;
}

}
