<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Video extends Model
{
    use HasFactory;

    protected $table = 'videos'; // 指定模型对应的表名

    public $timestamps = false; // 设置为 false 因为表中没有时间戳字段

    // 设置模型的 fillable 属性，允许批量赋值
    protected $fillable = [
        'actor_id', 'actor_name', 'item_code', 'title', 'video_rank',
        'item_number', 'duration', 'release_date', 'director', 'series',
        'maker', 'label', 'genres', 'tags', 'description', 'poster_url', 'video_images'
    ];

    // 指定日期字段
    protected $dates = ['release_date'];

    // 定义与 Actor 模型的关联
    public function actor()
    {
        return $this->belongsTo(Actor::class, 'actor_id');
    }
}
