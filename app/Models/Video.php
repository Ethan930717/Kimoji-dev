<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Video extends Model
{
    use HasFactory;

    protected $table = 'videos'; // 指定模型对应的表名

    public $timestamps = false; // 设置为 false 因为表中没有时间戳字段

    // 设置模型的 fillable 属性，允许批量赋值
    protected $fillable = [
        'actor_id', 'actor_name', 'actor_code', 'title', 'video_rank',
        'item_number', 'duration', 'release_date', 'director', 'series',
        'maker', 'label', 'genres', 'tags', 'description', 'poster_url', 'video_images'
    ];

    // 指定日期字段
    protected $dates = ['release_date'];

    // 属性转换
    protected $casts = [
        'genres' => 'array',
        'tags' => 'array',
    ];

    // 定义与 Actor 模型的关联
    public function actor()
    {
        return $this->belongsTo(Actor::class, 'actor_id');
    }

    // 定义与 Director 模型的关联
    public function director()
    {
        return $this->belongsTo(Director::class, 'director_id');
    }

    // 定义与 Series 模型的关联
    public function series()
    {
        return $this->belongsTo(Series::class, 'series_id');
    }

    // 定义与 Maker 模型的关联
    public function maker()
    {
        return $this->belongsTo(Maker::class, 'maker_id');
    }

    // 定义与 Label 模型的关联
    public function label()
    {
        return $this->belongsTo(Label::class, 'label_id');
    }

    // 定义与 Genre 模型的多对多关联
    public function genres()
    {
        return $this->belongsToMany(Genre::class, 'video_genre', 'video_id', 'genre_id');
    }

    // 定义与 Tag 模型的多对多关联
    public function tags()
    {
        return $this->belongsToMany(Tag::class, 'video_tag', 'video_id', 'tag_id');
    }

    // 查询作用域
    public function scopePopular($query)
    {
        return $query->where('video_rank', '>', 8);
    }

    public function scopeReleasedAfter($query, $date)
    {
        return $query->where('release_date', '>', $date);
    }

    // 格式化日期字段
    public function getFormattedReleaseDateAttribute()
    {
        return Carbon::parse($this->release_date)->format('Y-m-d');
    }

    // 获取视频截图
    public function getVideoImagesAttribute($value)
    {
        return is_string($value) ? $value : implode(';', $value);
    }
}
