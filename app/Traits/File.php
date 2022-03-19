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

//    public function zip($fileName = 'files.zip', $files = [])
//    {
//        $zip = new ZipArchive;
//
//        $zip_file = $fileName;
//
//        if ($zip->open(public_path($zip_file), ZipArchive::CREATE | ZipArchive::OVERWRITE) === TRUE) {
//            $files = \File::files(storage_path('myFiles'));
//
//            foreach ($files as $key => $value) {
//                $relativeNameInZipFile = basename($value);
//                $zip->addFile($value, $relativeNameInZipFile);
//            }
//
//            $zip->close();
//        }
//
//        return public_path($zip_file);
//    }

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

        return rename($tempFileUri, $zipPathAndName);
    }
}
