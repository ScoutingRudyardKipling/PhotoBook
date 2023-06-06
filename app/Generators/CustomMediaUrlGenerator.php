<?php

namespace App\Generators;

use DateTimeInterface;
use Spatie\MediaLibrary\Support\UrlGenerator\BaseUrlGenerator;
use Spatie\MediaLibrary\Support\UrlGenerator\UrlGenerator;

class CustomMediaUrlGenerator extends BaseUrlGenerator implements UrlGenerator
{
    /**
     * @return string
     */
    public function getUrl(): string
    {
        return url('/media/' . $this->getPath());
    }

    /**
     * Get the temporary url for a media item.
     *
     * @param DateTimeInterface $expiration
     * @param array             $options
     *
     * @return string
     */
    public function getTemporaryUrl(DateTimeInterface $expiration, array $options = []): string
    {
        unset($expiration);
        unset($options);
        // TODO: Implement getTemporaryUrl() method.
        return '';
    }

    /**
     * Get the url to the directory containing responsive images.
     *
     * @return string
     */
    public function getResponsiveImagesDirectoryUrl(): string
    {
        // TODO: Implement getResponsiveImagesDirectoryUrl() method.
        return '';
    }

    /**
     * Get the url for the profile of a media item.
     *
     * @return string
     */
    public function getPath(): string
    {
        return $this->getPathRelativeToRoot();
    }
}
