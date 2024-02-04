<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Artist extends Model
{
    use HasFactory;

    // 设置模型的 fillable 或 guarded 属性，以便在使用模型的 create 或 update 方法时允许批量赋值
    protected $fillable = ['name', 'birthday', 'deathday', 'member', 'country', 'label', 'genre', 'biography', 'image_url'];

    // 如果您有日期字段，您可以添加它们到 $dates 属性中，以便它们将自动被转换为 Carbon 实例
    protected $dates = ['birthday', 'deathday'];

    public function musics()
    {
        return $this->hasMany(Music::class);
    }

    public function torrents()
    {
        return $this->hasMany(Torrent::class);
    }
}
