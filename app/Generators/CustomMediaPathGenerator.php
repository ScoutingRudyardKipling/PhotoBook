<?php

namespace App\Generators;

use App\Models\Content;
use Spatie\MediaLibrary\Models\Media;
use Spatie\MediaLibrary\PathGenerator\PathGenerator;

class CustomMediaPathGenerator implements PathGenerator
{
    /**
     * @param \Spatie\MediaLibrary\Models\Media $media
     *
     * @return string
     */
    public function getPath(Media $media): string
    {
        $content = $media->model()->first();
        if ($content instanceof Content) {
            return $content->getAlbumPath() . DIRECTORY_SEPARATOR;
        }
        return "";
    }

    public function getPathForConversions(Media $media): string
    {
        return 'conversions' . DIRECTORY_SEPARATOR . $this->getPath($media);
    }

    public function getPathForResponsiveImages(Media $media): string
    {
        return 'responsive-images' . DIRECTORY_SEPARATOR . $this->getPath($media);
    }
}
