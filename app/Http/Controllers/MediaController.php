<?php

namespace App\Http\Controllers;

use Illuminate\Http\Response;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Storage;

class MediaController extends Controller
{
    private string $disk;

    public function __construct()
    {
        $diskName   = config('media-library.disk_name');
        $this->disk = is_string($diskName) ? $diskName : '';
    }

    public function get(Request $request, string $filePath): Response|StreamedResponse
    {
        if (!Storage::disk($this->disk)->exists($filePath)) {
            return abort(404);
        }

        $lastModified = Storage::disk($this->disk)->lastModified($filePath);
        $etag         = '"' . md5($filePath . $lastModified) . '"';
        $mimeType     = Storage::disk($this->disk)->mimeType($filePath);

        // Conversions (thumbnails) are content-addressed and never change — cache for 1 year.
        // Original files are cached for 1 day.
        $isConversion   = str_starts_with($filePath, 'conversions/');
        $maxAge         = $isConversion ? 31_536_000 : 86_400;
        $cacheControl   = $isConversion ? "public, max-age={$maxAge}, immutable" : "public, max-age={$maxAge}";

        // Return 304 if the browser already has a fresh copy
        if ($request->header('If-None-Match') === $etag) {
            return response('', 304, [
                'ETag'          => $etag,
                'Cache-Control' => $cacheControl,
            ]);
        }

        $stream = Storage::disk($this->disk)->readStream($filePath);
        if ($stream === null) {
            return abort(500);
        }

        return response()->stream(
            static fn () => fpassthru($stream),
            200,
            [
                'Content-Type'  => $mimeType,
                'Cache-Control' => $cacheControl,
                'ETag'          => $etag,
                'Last-Modified' => gmdate('D, d M Y H:i:s', $lastModified) . ' GMT',
            ]
        );
    }
}
