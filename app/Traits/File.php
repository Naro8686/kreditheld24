<?php

namespace App\Traits;

use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Storage;
use Log;

trait File
{
    public static $locale = 'public';

    public function fileUpload(UploadedFile $file, $path = 'uploads')
    {
        $fileName = time() . '_' . $file->getClientOriginalName();
        return $file->storeAs($path, $fileName, self::$locale);
    }

    public function deleteFiles($files = [])
    {
        foreach ($files as $file) {
            if (Storage::disk(self::$locale)->exists($file)) {
                Storage::disk(self::$locale)->delete($file);
            }
        }
    }

    public function read($file): ?Collection
    {
        if (Storage::disk(self::$locale)->exists($file)) {
            try {
                $storage = Storage::disk(self::$locale);
                return collect([
                    'mime' => $storage->getMimetype($file),
                    'meta' => $storage->getMetadata($file),
                    'headers' => $storage->response($file)->headers->all(),
                    'binary' => $storage->read($file),
                ]);
            } catch (FileNotFoundException|\League\Flysystem\FileNotFoundException $e) {
                Log::error($e->getMessage());
            }
        }
        return null;
    }
}
