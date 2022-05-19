<?php

namespace App\Traits;

use Exception;
use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Storage;
use Log;
use ZipArchive;

trait File
{
    public static $locale = 'public';
    public static $path = 'uploads';

    public function fileUpload(UploadedFile $file, $path = null)
    {
        if (is_null($path)) $path = self::$path;
        $fileName = time() . '_' . $file->getClientOriginalName();
        return $file->storeAs($path, $fileName, self::$locale);
    }

    public function deleteFiles($files = [])
    {
        foreach ($files as $file) {
            $this->deleteFile($file);
        }
    }

    public function deleteFile($file): bool
    {
        if (Storage::disk(self::$locale)->exists($file)) {
            Storage::disk(self::$locale)->delete($file);
        }
        return true;
    }

    public function copyFiles()
    {
        $files = [];
        foreach ($this->files as $file) {
            if (Storage::disk(self::$locale)->exists($file)) {
                $fileName = ltrim($file, self::$path . "/");
                $fileName = time() . "_copy_" . $fileName;
                $fullPath = self::$path . "/" . $fileName;
                if (Storage::disk(self::$locale)->copy($file, $fullPath)) {
                    $files[] = $fullPath;
                }
            }
        }
        $this->files = $files;
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

    /**
     * @param string $zipPathAndName
     * @param array $filesAndPaths
     * @return bool
     * @throws Exception
     */
    public function makeZipWithFiles(string $zipPathAndName, array $filesAndPaths): bool
    {
        $zip = new ZipArchive();
        $tempFile = tmpfile();
        $tempFileUri = stream_get_meta_data($tempFile)['uri'];

        if ($zip->open($tempFileUri, ZipArchive::CREATE | ZipArchive::OVERWRITE) !== TRUE) {
            throw new Exception('Could not open ZIP file.');
        }

        foreach ($filesAndPaths as $file) {
            if (!$zip->addFile($file, basename($file))) {
                throw new Exception('Could not add file to ZIP: ' . $file);
            }
        }
        $zip->close();
        $dirname = dirname($zipPathAndName);
        if (!is_dir($dirname)) mkdir($dirname);
        if (file_exists($zipPathAndName)) unlink($zipPathAndName);
        return rename($tempFileUri, $zipPathAndName);
    }
}
