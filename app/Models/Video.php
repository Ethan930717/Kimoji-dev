<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Video extends Model
{
    use HasFactory;

    protected $table = 'videos';

    public $timestamps = false;

    protected $fillable = [
        'actor_id', 'actor_name', 'actor_code', 'title', 'video_rank',
        'item_number', 'duration', 'release_date', 'director', 'series',
        'maker', 'label', 'genres', 'tags', 'description', 'poster_url', 'video_images'
    ];

    protected $dates = ['release_date'];

    protected $casts = [
        'genres' => 'array',
        'tags' => 'array',
    ];

    public function actor()
    {
        return $this->belongsTo(Actor::class, 'actor_id');
    }

    public function director()
    {
        return $this->belongsTo(Director::class, 'director_id');
    }

    public function videoGenres()
    {
        return $this->belongsToMany(VideoGenre::class, 'video_genre_video', 'video_id', 'genre_id');
    }

    public function tags()
    {
        return $this->belongsToMany(VideoTag::class, 'video_tag_video', 'video_id', 'tag_id');
    }

    public function series()
    {
        return $this->belongsToMany(VideoSeries::class, 'video_series_video', 'video_id', 'series_id');
    }

    public function maker()
    {
        return $this->belongsToMany(VideoMaker::class, 'video_maker_video', 'video_id', 'maker_id');
    }

    public function label()
    {
        return $this->belongsToMany(VideoLabel::class, 'video_label_video', 'video_id', 'label_id');
    }

    public function scopePopular($query)
    {
        return $query->where('video_rank', '>', 4);
    }

    public function scopeReleasedAfter($query, $date)
    {
        return $query->where('release_date', '>', $date);
    }

    public function getFormattedReleaseDateAttribute()
    {
        return Carbon::parse($this->release_date)->format('Y-m-d');
    }

    public function getVideoImagesAttribute($value)
    {
        return is_string($value) ? $value : implode(';', $value);
    }
}

