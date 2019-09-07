<?php

namespace App\Generators;

use Spatie\MediaLibrary\Models\Media;
use Spatie\MediaLibrary\PathGenerator\PathGenerator;

class CustomMediaPathGenerator implements PathGenerator
{
    public function getPath(Media $media): string
    {
        $content = $media->model()->first();
        return $content->getPath();
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
