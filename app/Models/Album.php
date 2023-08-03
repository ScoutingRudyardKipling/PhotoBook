<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;

/**
 * App\Models\Album
 *
 * @property      int                                                            $id
 * @property      string                                                         $name
 * @property      int|null                                                       $parent_id
 * @property      \Illuminate\Support\Carbon|null                                $created_at
 * @property      \Illuminate\Support\Carbon|null                                $updated_at
 * @property      int|null                                                       $featured_id
 * @property      string|null                                                    $featured_type
 * @property-read \Illuminate\Database\Eloquent\Model|\Eloquent                  $featured
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Album[]   $childAlbums
 * @property-read int|null                                                       $child_albums_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Content[] $contents
 * @property-read int|null                                                       $contents_count
 * @property-read \App\Models\Album|null                                         $parent
 * @method        static \Illuminate\Database\Eloquent\Builder|\App\Models\Album whereFeaturedId($value)
 * @method        static \Illuminate\Database\Eloquent\Builder|\App\Models\Album whereFeaturedType($value)
 * @method        static \Illuminate\Database\Eloquent\Builder|\App\Models\Album newModelQuery()
 * @method        static \Illuminate\Database\Eloquent\Builder|\App\Models\Album newQuery()
 * @method        static \Illuminate\Database\Eloquent\Builder|\App\Models\Album query()
 * @method        static \Illuminate\Database\Eloquent\Builder|\App\Models\Album whereCreatedAt($value)
 * @method        static \Illuminate\Database\Eloquent\Builder|\App\Models\Album whereId($value)
 * @method        static \Illuminate\Database\Eloquent\Builder|\App\Models\Album whereName($value)
 * @method        static \Illuminate\Database\Eloquent\Builder|\App\Models\Album whereParentId($value)
 * @method        static \Illuminate\Database\Eloquent\Builder|\App\Models\Album whereUpdatedAt($value)
 * @mixin         \Eloquent
 */
class Album extends Model
{
    protected $fillable = [
        'name',
        'parent_id',
        'featured',
        'featured_id',
        'featured_type',
    ];

    public function parent()
    {
        return $this->belongsTo(self::class, 'parent_id');
    }

    public function contents()
    {
        return $this->hasMany(Content::class, 'parent_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\MorphTo
     */
    public function featured()
    {
        return $this->morphTo();
    }

    /**
     * @return \Illuminate\Database\Eloquent\Model|\Illuminate\Database\Eloquent\Relations\HasMany|object|null
     */
    public function getFeaturedContent()
    {
        $content = $this->featured;
        if ($content instanceof Album) {
            return $content->getFeaturedContent();
        }

        $content = $this->contents()->first();
        if (empty($content)) {
            $firstChildAlbum = $this->childAlbums()->first();
            if (!empty($firstChildAlbum)) {
                return $firstChildAlbum->getFeaturedContent();
            }
        }

        return $content;
    }

    /**
     * @return string|null
     */
    public function getFeaturedContentThumb()
    {
        return Cache::rememberForever(
            'featuredThumbUrl' . $this->id,
            function () {
                if (empty($this->getFeaturedContent())) {
                    return null;
                }
                return $this->getFeaturedContent()->getFirstMediaUrl('default', 'thumb');
            }
        );
    }

    public function childAlbums()
    {
        return $this->hasMany(self::class, 'parent_id');
    }

    /**
     * @return string
     */
    public function getPath(): string
    {
        return Cache::rememberForever(
            'albumPath' . $this->id,
            function () {
                $path = $this->name;
                if (!is_null($this->parent_id)) {
                    $parentAlbum = Album::find($this->parent_id);
                    $parentPath  = $parentAlbum->getPath();
                    $path        = $parentPath . DIRECTORY_SEPARATOR . $this->name;
                }
                return $path;
            }
        );
    }

    public function deleteCache()
    {
        Cache::forget('albumPath' . $this->id);
        $this->deleteFeaturedThumbCache();
        foreach ($this->childAlbums as $childAlbum) {
            $childAlbum->deleteCache();
        }
        foreach ($this->contents as $content) {
            $content->deleteCache();
        }
    }

    public function deleteFeaturedThumbCache()
    {
        Cache::forget('featuredThumbUrl' . $this->id);
        if (!empty($this->parent)) {
            $this->parent->deleteFeaturedThumbCache();
        }
    }

    public static function boot()
    {
        parent::boot();

        static::creating(
            function ($model) {
                Storage::disk('media')->createDir($model->getPath());
                Storage::disk('media')->createDir('conversions' . DIRECTORY_SEPARATOR . $model->getPath());
                $model->deleteCache();
            }
        );

        static::updating(
            function ($model) {
                $originalParentPath = Album::find(
                    ($model->getOriginal()['parent_id'] ?? null)
                );
                if (!is_null($originalParentPath)) {
                    $originalParentPath = $originalParentPath->getPath() . DIRECTORY_SEPARATOR;
                }
                $dirtyParentPath = Album::find(
                    ($model->getAttributes()['parent_id'] ?? null)
                );
                if (!is_null($dirtyParentPath)) {
                    $dirtyParentPath = $dirtyParentPath->getPath() . DIRECTORY_SEPARATOR;
                }
                $originalPath = $originalParentPath . $model->getOriginal()['name'];
                $dirtyPath    = $dirtyParentPath . $model->getAttributes()['name'];
                if ($originalPath != $dirtyPath) {
                    Storage::disk('media')->move(
                        $originalPath,
                        $dirtyPath
                    );
                    Storage::disk('media')->move(
                        'conversions' . DIRECTORY_SEPARATOR . $originalPath,
                        'conversions' . DIRECTORY_SEPARATOR . $dirtyPath
                    );
                }
                $model->deleteCache();
            }
        );

        static::deleting(
            function ($model) {
                foreach ($model->contents as $content) {
                    $content->delete();
                }
                foreach ($model->childAlbums as $childAlbum) {
                    $childAlbum->delete();
                }
                Storage::disk('media')->deleteDir($model->getPath());
                Storage::disk('media')->deleteDir('conversions' . DIRECTORY_SEPARATOR . $model->getPath());
                $model->deleteCache();
            }
        );
    }
}
