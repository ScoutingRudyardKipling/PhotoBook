<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
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

    public function parent()
    {
        return $this->belongsTo(Album::class, 'parent_id');
    }

    /**
     * @param \Spatie\MediaLibrary\Models\Media|null $media
     *
     * @return void
     * @throws \Spatie\Image\Exceptions\InvalidManipulation
     */
    public function registerMediaConversions(Media $media = null)
    {
        $this->addMediaConversion('thumb')
            ->width(300);
        $this->addMediaConversion('tiny')
            ->width(20);
    }

    /**
     * @return string
     */
    public function getAlbumPath()
    {
        // TODO:: could be cached...
        $path = "";
        if (!is_null($this->parent_id)) {
            $path = $this->parent->getPath();
        }
        return $path;
    }

    /**
     * @return string
     */
    public function getPath()
    {
        return $this->getAlbumPath() . DIRECTORY_SEPARATOR . $this->name;
    }
}
