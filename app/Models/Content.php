<?php

namespace App\Models;

use App\Models\Album;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;
use Spatie\Image\Enums\Fit;
use Spatie\Image\Exceptions\InvalidManipulation;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Collections\MediaCollection;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

/**
 * @property int $id
 * @property string $name
 * @property int $parent_id
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read MediaCollection<int, \App\Models\Media> $media
 * @property-read int|null $media_count
 * @property-read Album $parent
 * @method static Builder<static>|Content newModelQuery()
 * @method static Builder<static>|Content newQuery()
 * @method static Builder<static>|Content query()
 * @method static Builder<static>|Content whereCreatedAt($value)
 * @method static Builder<static>|Content whereId($value)
 * @method static Builder<static>|Content whereName($value)
 * @method static Builder<static>|Content whereParentId($value)
 * @method static Builder<static>|Content whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Content extends Model implements HasMedia
{
    use InteractsWithMedia;

    protected $fillable = [
        'name',
        'parent_id',
    ];

    /** @var array<string, int> */
    private array $conversions = [
        'thumb' => 300,
        'tiny'  => 20,
    ];

    /**
     * @return BelongsTo<Album, $this>
     */
    public function parent(): BelongsTo
    {
        return $this->belongsTo(Album::class, 'parent_id');
    }

    /**
     * @param Media|null $media
     *
     * @return void
     * @throws InvalidManipulation
     *
     * @SuppressWarnings("PHPMD.UnusedFormalParameter")
     */
    public function registerMediaConversions(Media $media = null): void
    {
        foreach ($this->conversions as $conversionName => $conversionSize) {
            $this->addMediaConversion($conversionName)
                ->fit(Fit::Contain, $conversionSize, $conversionSize);
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
                return $this->parent->getPath();
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

    public function deleteCache(): void
    {
        Cache::forget('mediaUrl' . $this->id);
        foreach (array_keys($this->conversions) as $conversion) {
            Cache::forget('mediaUrl' . $conversion . $this->id);
        }
        Cache::forget('getAlbumPath' . $this->id);
        if (!empty($this->parent)) {
            if (!empty($this->parent->getFeaturedContent())) {
                if ($this->parent->getFeaturedContent()->id === $this->id) {
                    $this->parent->deleteFeaturedThumbCache();
                }
            }
        }
    }

    public function shouldDeletePreservingMedia(): bool
    {
        return true;
    }


    public function updateInternal(Content $model): void
    {
        //TODO: test if this refactor works
        $mediaItem = $model->media()->first();
        if ($mediaItem === null) {
            return;
        }
        $media = $mediaItem->file_name;

        $originalParentId   = $model->getOriginal()['parent_id'] ?? null;
        $originalParent     = is_int($originalParentId) ? Album::find($originalParentId) : null;
        $originalParentPath = '';
        if ($originalParent instanceof Album) {
            $originalParentPath = $originalParent->getPath() . DIRECTORY_SEPARATOR;
        }
        $dirtyParentId   = $model->getAttributes()['parent_id'] ?? null;
        $dirtyParent     = is_int($dirtyParentId) ? Album::find($dirtyParentId) : null;
        $dirtyParentPath = '';
        if ($dirtyParent instanceof Album) {
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
        if ($originalParent instanceof Album) {
            $originalParent->deleteCache();
        }
        if ($dirtyParent instanceof Album) {
            $dirtyParent->deleteCache();
        }
    }

    public function deleteInternal(Content $model): void
    {
        //TODO: test if this refactor works
        $mediaItem = $model->media()->first();
        if ($mediaItem === null) {
            return;
        }
        $media      = $mediaItem->file_name;
        $parent     = $model->parent;
        $parentPath = $parent->getPath() . DIRECTORY_SEPARATOR;
        Storage::disk('media')->delete($parentPath . $media);

        $mediaName      = explode('.', $media)[0];
        $mediaExtention = explode('.', $media)[1];
        foreach (array_keys($model->conversions) as $conversion) {
            Storage::disk('media')->delete(
                'conversions' . DIRECTORY_SEPARATOR . $parentPath . $mediaName . '-' . $conversion . '.' . $mediaExtention
            );
        }
        $parent->deleteCache();
        $model->deleteCache();
    }

    public static function boot()
    {
        parent::boot();

        static::creating(
            function (Content $model) {
                $model->deleteCache();
            }
        );

        static::updating(
            function (Content $model) {
                (new Content())->updateInternal($model);
            }
        );

        static::deleting(
            function (Content $model) {
                (new Content())->deleteInternal($model);
            }
        );
    }
}
