<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Redis;
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

    public function videoGenres()
    {
        return $this->belongsToMany(VideoGenre::class, 'video_genre_video', 'video_id', 'genre_id');
    }

    public function videotags()
    {
        return $this->belongsToMany(VideoTag::class, 'video_tag_video', 'video_id', 'tag_id');
    }

    public function videoseries()
    {
        return $this->belongsToMany(VideoSeries::class, 'video_series_video', 'video_id', 'series_id');
    }

    public function videomaker()
    {
        return $this->belongsToMany(VideoMaker::class, 'video_maker_video', 'video_id', 'maker_id');
    }

    public function videolabel()
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

    // 缓存到 Redis
    public static function cacheToRedis()
    {
        $videos = self::all()->chunk(1000);

        $i = 0;
        foreach ($videos as $chunk) {
            $data = $chunk->toJson();
            Redis::set("videos:all:chunk_{$i}", $data);
            $i++;
        }

        // 设置总块数
        Redis::set('videos:all:chunks', $i);
    }


    // 从 Redis 获取数据
    public static function getFromRedis($page = 1, $perPage = 50, $filters = [], $sortField = 'release_date', $sortDirection = 'desc')
    {
        $chunks = Redis::get('videos:all:chunks');
        $startChunk = intdiv(($page - 1) * $perPage, 1000);
        $endChunk = intdiv($page * $perPage, 1000);
        $videos = collect();

        for ($i = $startChunk; $i <= $endChunk; $i++) {
            $data = Redis::get("videos:all:chunk_{$i}");
            $videos = $videos->merge(json_decode($data, true));
        }

        foreach ($filters as $key => $value) {
            $videos = $videos->filter(function ($video) use ($key, $value) {
                return strpos($video[$key], $value) !== false;
            });
        }

        $videos = $videos->sortBy(function ($video) use ($sortField, $sortDirection) {
            return $sortDirection == 'asc' ? $video[$sortField] : -$video[$sortField];
        })->values();

        $total = Redis::get('videos:all:count');

        $currentPageItems = $videos->slice(($page - 1) * $perPage % 1000, $perPage)->all();

        return new LengthAwarePaginator($currentPageItems, $total, $perPage, $page, [
            'path' => LengthAwarePaginator::resolveCurrentPath(),
        ]);
    }


    // 在模型事件中刷新缓存
    protected static function booted()
    {
        static::created(function ($video) {
            self::cacheToRedis();
        });

        static::updated(function ($video) {
            self::cacheToRedis();
        });

        static::deleted(function ($video) {
            self::cacheToRedis();
        });
    }
}


