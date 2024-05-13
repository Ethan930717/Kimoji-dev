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
        'artist_id', 'artist_name', 'artist_code', 'title', 'video_rank',
        'item_number', 'duration', 'release_date', 'director', 'series',
        'maker', 'label', 'genres', 'tags', 'description', 'poster_url', 'video_images'
    ];

    // 指定日期字段
    protected $dates = ['release_date'];

    // 如果有相关联的模型，如 Torrents，可以定义关联方法
    // 示例：假设有一个 Torrents 模型与 Video 模型相关联
    public function torrents()
    {
        return $this->hasMany(Torrent::class);
    }

}

