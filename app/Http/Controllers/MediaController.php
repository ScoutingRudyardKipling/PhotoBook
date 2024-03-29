<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Illuminate\Http\Response;
use Storage;

class MediaController extends Controller
{
    private $disk;
    private $temporaryDisk;

    public function __construct()
    {
        $this->disk          = config("media-library.disk_name");
        $this->temporaryDisk = 'temp';
    }

    public function get(string $filePath)
    {
        if (Storage::disk($this->disk)->exists($filePath)) {
            $file = $this->getFile($filePath);
            $type = Storage::disk($this->disk)->mimeType($filePath);

            return new Response($file, 200, ['Content-Type' => $type]);
        }
        return abort(404);
    }

    private function getFile(string $filePath)
    {
        try {
            $file = Storage::disk($this->disk)->get($filePath);
        } catch (FileNotFoundException $e) {
            return abort(404);
        }
        return $file;
    }
}
