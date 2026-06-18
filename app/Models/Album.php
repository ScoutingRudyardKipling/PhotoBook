<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;

/**
 * @property int $id
 * @property string $name
 * @property int|null $parent_id
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property int|null $featured_id
 * @property string|null $featured_type
 * @property-read Collection<int, Album> $childAlbums
 * @property-read int|null $child_albums_count
 * @property-read Collection<int, \App\Models\Content> $contents
 * @property-read int|null $contents_count
 * @property-read Model|\Eloquent|null $featured
 * @property-read Album|null $parent
 * @method static Builder<static>|Album newModelQuery()
 * @method static Builder<static>|Album newQuery()
 * @method static Builder<static>|Album query()
 * @method static Builder<static>|Album whereCreatedAt($value)
 * @method static Builder<static>|Album whereFeaturedId($value)
 * @method static Builder<static>|Album whereFeaturedType($value)
 * @method static Builder<static>|Album whereId($value)
 * @method static Builder<static>|Album whereName($value)
 * @method static Builder<static>|Album whereParentId($value)
 * @method static Builder<static>|Album whereUpdatedAt($value)
 * @mixin \Eloquent
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

    /**
     * @return BelongsTo<Album, $this>
     */
    public function parent(): BelongsTo
    {
        return $this->belongsTo(self::class, 'parent_id');
    }

    /**
     * @return HasMany<Content, $this>
     */
    public function contents(): HasMany
    {
        return $this->hasMany(Content::class, 'parent_id');
    }

    /**
     * @return MorphTo<Model, $this>
     */
    public function featured(): MorphTo
    {
        return $this->morphTo();
    }

    public function getFeaturedContent(): ?Content
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
    public function getFeaturedContentThumb(): ?string
    {
        return Cache::rememberForever(
            'featuredThumbUrl' . $this->id,
            function (): ?string {
                if (empty($this->getFeaturedContent())) {
                    return null;
                }
                return $this->getFeaturedContent()->getFirstMediaUrl('default', 'thumb');
            }
        );
    }

    /**
     * @return HasMany<Album, $this>
     */
    public function childAlbums(): HasMany
    {
        return $this->hasMany(self::class, 'parent_id');
    }

    /**
     * Get all contents from this album and all its descendants.
     *
     * @return Collection<int, Content>
     */
    public function getAllContents()
    {
        return Content::whereIn('parent_id', $this->getDescendantAlbumIds())->get();
    }

    /**
     * Check if this album or any of its descendants have contents.
     *
     * @return boolean
     */
    public function hasContentRecursive()
    {
        return Content::whereIn('parent_id', $this->getDescendantAlbumIds())->exists();
    }

    /**
     * Get IDs of this album and all its descendants.
     *
     * @return array<int, int>
     */
    public function getDescendantAlbumIds(): array
    {
        $ids = [$this->id];
        foreach ($this->childAlbums as $child) {
            $ids = array_merge($ids, $child->getDescendantAlbumIds());
        }
        return $ids;
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
                    if ($parentAlbum instanceof Album) {
                        $path = $parentAlbum->getPath() . DIRECTORY_SEPARATOR . $this->name;
                    }
                }
                return $path;
            }
        );
    }

    public function deleteCache(): void
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

    public function deleteFeaturedThumbCache(): void
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
                Storage::disk('media')->makeDirectory($model->getPath());
                Storage::disk('media')->makeDirectory('conversions' . DIRECTORY_SEPARATOR . $model->getPath());
                $model->deleteCache();
            }
        );

        static::updating(
            function ($model) {
                $originalParentId    = $model->getOriginal()['parent_id'] ?? null;
                $originalParentAlbum = is_int($originalParentId) ? Album::find($originalParentId) : null;
                $originalParentPath  = $originalParentAlbum instanceof Album
                    ? $originalParentAlbum->getPath() . DIRECTORY_SEPARATOR
                    : null;

                $dirtyParentId    = $model->getAttributes()['parent_id'] ?? null;
                $dirtyParentAlbum = is_int($dirtyParentId) ? Album::find($dirtyParentId) : null;
                $dirtyParentPath  = $dirtyParentAlbum instanceof Album
                    ? $dirtyParentAlbum->getPath() . DIRECTORY_SEPARATOR
                    : null;

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
                Storage::disk('media')->deleteDirectory($model->getPath());
                Storage::disk('media')->deleteDirectory('conversions' . DIRECTORY_SEPARATOR . $model->getPath());
                $model->deleteCache();
            }
        );
    }
}
