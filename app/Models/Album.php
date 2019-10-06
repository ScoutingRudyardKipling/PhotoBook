<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

/**
 * App\Models\Album
 *
 * @property      int                                                            $id
 * @property      string                                                         $name
 * @property      int|null                                                       $parent_id
 * @property      \Illuminate\Support\Carbon|null                                $created_at
 * @property      \Illuminate\Support\Carbon|null                                $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Album[]   $childAlbums
 * @property-read int|null                                                       $child_albums_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Content[] $contents
 * @property-read int|null                                                       $contents_count
 * @property-read \App\Models\Album|null                                         $parent
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
    ];

    public function parent()
    {
        return $this->belongsTo(self::class, 'parent_id');
    }

    public function contents()
    {
        return $this->hasMany(Content::class, 'parent_id');
    }

    public function getFeaturedContent()
    {
        $content = $this->contents()->first();
        if (is_null($content)) {
            $content = $this->childAlbums()->first()->getFeaturedContent();
        }
        return $content;
    }

    public function childAlbums()
    {
        return $this->hasMany(self::class, 'parent_id');
    }

    /**
     * @return string
     */
    public function getPath()
    {
        $cache = Cache::rememberForever(
            'albumPath' . $this->id,
            function () {
                $path = $this->name;
                if (!is_null($this->parent_id)) {
                    $parentAlbum = self::find($this->parent_id);
                    $parentPath  = $parentAlbum->getPath();
                    $path        = $parentPath . DIRECTORY_SEPARATOR . $this->name;
                }
                return $path;
            }
        );
        return $cache;
    }

    public static function boot()
    {
        parent::boot();

        static::creating(
            function ($model) {
                Cache::delete('albumPath' . $model->id);
            }
        );

        static::updating(
            function ($model) {
                Cache::delete('albumPath' . $model->id);
            }
        );

        static::deleting(
            function ($model) {
                Cache::delete('albumPath' . $model->id);
            }
        );
    }
}
