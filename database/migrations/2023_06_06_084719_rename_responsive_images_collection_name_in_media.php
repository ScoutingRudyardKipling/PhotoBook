<?php

use Illuminate\Contracts\Filesystem\Factory;
use Illuminate\Database\Migrations\Migration;
use App\Models\Media;
use Spatie\MediaLibrary\Support\PathGenerator\PathGeneratorFactory;

class RenameResponsiveImagesCollectionNameInMedia extends Migration
{

    const OLD_COLLECTION_NAME = 'medialibrary_original';
    const NEW_COLLECTION_NAME = 'media_library_original';

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $this->change(self::OLD_COLLECTION_NAME, self::NEW_COLLECTION_NAME);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $this->change(self::NEW_COLLECTION_NAME, self::OLD_COLLECTION_NAME);
    }

    public function change(string $from, string $to)
    {
        /** @var Factory $filesystem */
        $filesystem = app(Factory::class);

        $pathGenerator = PathGeneratorFactory::create();

        // Find media with the old collection name is present
        Media::query()
            ->withoutGlobalScopes()
            ->whereNotNull('responsive_images->' . $from)
            ->cursor()
            ->each(function ($media) use ($from, $to, $filesystem, $pathGenerator) {
                // Change the old collection key
                $responsive_images = array_merge(
                    $media->responsive_images,
                    [
                        $to   => $media->responsive_images[$from],
                        $from => null,
                    ]
                );
                // Remove it completely
                unset($responsive_images[$from]);

                // Responsive image path for this media
                $directory = $pathGenerator->getPathForResponsiveImages($media);
                // Media disk
                $disk = $filesystem->disk($media->disk);

                foreach ($responsive_images[$to]['urls'] as &$filename) {
                    // Replace the old collection name with the new one
                    $newFilename = str_replace(
                        $from,
                        $to,
                        $filename
                    );
                    // If the old file exists move it on disk
                    if ($disk->exists($directory . $filename)) {
                        $disk->move($directory . $filename, $directory . $newFilename);
                        // Update the new array by ref
                        $filename = $newFilename;
                    }
                }
                // Save the new array
                $media->responsive_images = $responsive_images;
                $media->save();
            });
    }
}
