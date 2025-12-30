<?php

namespace App\Services\Upload;

use Illuminate\Http\UploadedFile;
use Illuminate\Http\Request;

class UploadManager
{
    public static function basePath(UploadedFile $file, string $folder = 'uploads'): string
    {
        return (new BasePathUploadService($folder))->upload($file);
    }

    public static function storage(UploadedFile $file, string $folder = 'uploads', string $disk = 'public'): string
    {
        return (new StorageUploadService($folder, $disk))->upload($file);
    }

    public static function default(UploadedFile $file, string $folder = 'uploads', string $disk = 'public'): string
    {
        return (new StorageUploadService($folder, $disk))->upload($file);
    }


    public static function basePathDelete(?string $filePath, string $folder = 'uploads'): bool
    {
        return (new BasePathUploadService($folder))->delete($filePath);
    }

    public static function storageDelete(?string $filePath, string $folder = 'uploads', string $disk = 'public'): bool
    {
        return (new StorageUploadService($folder, $disk))->delete($filePath);
    }
    public static function defaultDelete(?string $filePath, string $folder = 'uploads', string $disk = 'public'): bool
    {
        return (new StorageUploadService($folder, $disk))->delete($filePath);
    }

    public static function defaultGet(?string $filePath): ?string
{
    if (!$filePath) {
        return null;
    }

    return asset('storage/' . ltrim($filePath, '/'));
}


}
