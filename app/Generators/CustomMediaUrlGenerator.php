<?php

namespace App\Generators;

use DateTimeInterface;
use Spatie\MediaLibrary\UrlGenerator\BaseUrlGenerator;
use Spatie\MediaLibrary\UrlGenerator\UrlGenerator;

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
        if ($this->media->disk === 'public') {
            return $this->getStoragePath() . '/' . $this->getPathRelativeToRoot();
        }
        return $this->getPathRelativeToRoot();
    }

    protected function getStoragePath(): string
    {
        $diskRootPath = $this->config->get("filesystems.disks.{$this->media->disk}.root");
        return realpath($diskRootPath);
    }
}
