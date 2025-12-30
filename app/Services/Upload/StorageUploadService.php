<?php

namespace App\Services\Upload;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class StorageUploadService
{
    protected string $disk;
    protected string $folder;

    public function __construct(string $folder = 'uploads', string $disk = 'public')
    {
        $this->folder = trim($folder, '/');
        $this->disk = $disk;
    }

    /**
     * Upload file ke storage disk
     */
    public function upload(UploadedFile $file): string
    {
        return $file->store($this->folder, $this->disk);
    }

    /**
     * Hapus file dari storage
     */
    public function delete(?string $filePath): bool
{
    if (!$filePath) {
        return false;
    }

    return Storage::disk($this->disk)->exists($filePath) 
        ? Storage::disk($this->disk)->delete($filePath) 
        : false;
}

}
