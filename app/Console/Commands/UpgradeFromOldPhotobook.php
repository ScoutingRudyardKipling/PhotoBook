<?php

namespace App\Console\Commands;

use App\Models\Album;
use App\Models\Content;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use Spatie\MediaLibrary\Models\Media;

class UpgradeFromOldPhotobook extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'upgrade:from-old-photobook';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Upgrading from the old photobook. Reading file structure and add files to the application.';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $parentDirectories = Storage::disk('old')->directories();
        foreach ($parentDirectories as $parentDirectory) {
            $directory = Album::create(
                [
                    'name'      => $parentDirectory,
                    'parent_id' => null,
                ]
            );
            $this->readDirectory($directory);
        }
    }

    private function readDirectory(Album $parentAlbum)
    {
        $directories = Storage::disk('old')->directories($parentAlbum->getPath());
        $files       = Storage::disk('old')->files($parentAlbum->getPath());
        // move all images to the new driver (and create thumbnails)
        foreach ($files as $file) {
            preg_match('/.*\/(.*?$)/', $file, $matches);
            $content = Content::create(
                [
                    'name'      => $matches[1],
                    'parent_id' => $parentAlbum->id,
                ]
            );
            $content->addMedia(
                Storage::disk('old')->path($content->getCompletePath())
            )->toMediaCollection();
        }
        // call the readDirectory method to all sub directories
        foreach ($directories as $directory) {
            preg_match('/.*\/(.*?$)/', $directory, $matches);
            $newDirectoryAlbum = Album::create(
                [
                    'name'      => $matches[1],
                    'parent_id' => $parentAlbum->id,
                ]
            );
            $this->readDirectory($newDirectoryAlbum);
        }
    }
}
