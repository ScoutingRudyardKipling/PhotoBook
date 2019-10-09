<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;
use Spatie\MediaLibrary\HasMedia\HasMedia;
use Spatie\MediaLibrary\HasMedia\HasMediaTrait;
use Spatie\MediaLibrary\Models\Media;

/**
 * App\Models\Content
 *
 * @property      int                                                                          $id
 * @property      string                                                                       $name
 * @property      int                                                                          $parent_id
 * @property      \Illuminate\Support\Carbon|null                                              $created_at
 * @property      \Illuminate\Support\Carbon|null                                              $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\Spatie\MediaLibrary\Models\Media[] $media
 * @property-read int|null                                                                     $media_count
 * @property-read \App\Models\Album                                                            $parent
 * @method        static \Illuminate\Database\Eloquent\Builder|\App\Models\Content newModelQuery()
 * @method        static \Illuminate\Database\Eloquent\Builder|\App\Models\Content newQuery()
 * @method        static \Illuminate\Database\Eloquent\Builder|\App\Models\Content query()
 * @method        static \Illuminate\Database\Eloquent\Builder|\App\Models\Content whereCreatedAt($value)
 * @method        static \Illuminate\Database\Eloquent\Builder|\App\Models\Content whereId($value)
 * @method        static \Illuminate\Database\Eloquent\Builder|\App\Models\Content whereName($value)
 * @method        static \Illuminate\Database\Eloquent\Builder|\App\Models\Content whereParentId($value)
 * @method        static \Illuminate\Database\Eloquent\Builder|\App\Models\Content whereUpdatedAt($value)
 * @mixin         \Eloquent
 */
class Content extends Model implements HasMedia
{
    use HasMediaTrait;

    protected $fillable = [
        'name',
        'parent_id',
    ];

    private $conversions = [
        'thumb' => '300',
        'tiny'  => '20',
    ];

    public function parent()
    {
        return $this->belongsTo(Album::class, 'parent_id');
    }

    /**
     * @param \Spatie\MediaLibrary\Models\Media|null $media
     *
     * @return void
     * @throws \Spatie\Image\Exceptions\InvalidManipulation
     *
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function registerMediaConversions(Media $media = null)
    {
        foreach ($this->conversions as $conversionName => $conversionSize) {
            $this->addMediaConversion($conversionName)
                ->width($conversionSize);
        }
    }

    /**
     * @return string
     */
    public function getAlbumPath()
    {
        $cache = Cache::rememberForever(
            'getAlbumPath' . $this->id,
            function () {
                $path = "";
                if (!is_null($this->parent_id)) {
                    $path = $this->parent->getPath();
                }
                return $path;
            }
        );
        return $cache;
    }

    /**
     * @return string
     */
    public function getPath()
    {
        return $this->getAlbumPath() . DIRECTORY_SEPARATOR . $this->name;
    }

    public function getUrl(string $mediaType = ''): string
    {
        $cache = Cache::rememberForever(
            'mediaUrl' . $mediaType . $this->id,
            function () use ($mediaType) {
                return $this->getFirstMediaUrl('default', $mediaType);
            }
        );
        return $cache;
    }

    public function deleteCache()
    {
        Cache::delete('mediaUrl' . $this->id);
        foreach (array_keys($this->conversions) as $conversion) {
            Cache::delete('mediaUrl' . $conversion . $this->id);
        }
        Cache::delete('getAlbumPath' . $this->id);
        if (!empty($this->parent)) {
            if (!empty($this->parent->getFeaturedContent())) {
                if ($this->parent->getFeaturedContent()->id === $this->id) {
                    $this->parent->deleteFeaturedThumbCache();
                }
            }
        }
    }

    public static function boot()
    {
        parent::boot();

        static::creating(
            function ($model) {
                $model->deleteCache();
            }
        );

        static::updating(
            function ($model) {
                $media = $model->media()->first()->file_name;

                $originalParent = Album::find(
                    ($model->getOriginal()['parent_id'] ?? null)
                );
                $originalParentPath = '';
                if (!is_null($originalParent)) {
                    $originalParentPath = $originalParent->getPath() . DIRECTORY_SEPARATOR;
                }
                $dirtyParent = Album::find(
                    ($model->getAttributes()['parent_id'] ?? null)
                );
                $dirtyParentPath = '';
                if (!is_null($dirtyParent)) {
                    $dirtyParentPath = $dirtyParent->getPath() . DIRECTORY_SEPARATOR;
                }
                Storage::disk('media')->move(
                    $originalParentPath . $media,
                    $dirtyParentPath . $media
                );
                $mediaName      = explode('.', $media)[0];
                $mediaExtention = explode('.', $media)[1];
                foreach (array_keys($model->conversions) as $conversion) {
                    Storage::disk('media')->move(
                        'conversions' . DIRECTORY_SEPARATOR . $originalParentPath
                        . $mediaName . '-' . $conversion . '.' . $mediaExtention,
                        'conversions' . DIRECTORY_SEPARATOR . $dirtyParentPath
                        . $mediaName . '-' . $conversion . '.' . $mediaExtention
                    );
                }
                $model->deleteCache();
                if (!is_null($originalParent)) {
                    $originalParent->deleteCache();
                }
                if (!is_null($dirtyParent)) {
                    $originalParent->deleteCache();
                }
            }
        );

        static::deleting(
            function ($model) {
                // TODO: implement with the deletion of the file and the cooperation of the libary
                $model->deleteCache();
            }
        );
    }
}
