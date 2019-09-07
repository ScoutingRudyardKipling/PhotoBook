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

    public function registerMediaConversions(Media $media = null)
    {
        $this->addMediaConversion('thumb')
            ->height(500);
    }

    /**
     * @return string
     */
    public function getPath()
    {
        $path = "";
        $i    = self::find($this->id);
        while (!empty($i->parent_id)) {
            $i    = Album::find($i->parent_id);
            $path = $i->name . DIRECTORY_SEPARATOR . $path;
        }
        return $path;
    }

    /**
     * @return string
     */
    public function getCompletePath()
    {
        return $this->getPath() . DIRECTORY_SEPARATOR . $this->name;
    }
}
