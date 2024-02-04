<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Music extends Model
{
    use HasFactory;

    // 如果您的表名不是模型名的复数形式，您需要指定表名
    protected $table = 'music'; // 只有当表名不是 'music' 时才需要添加这行

    // 如果您不希望 Eloquent 维护 created_at 和 updated_at 时间戳，将此属性设置为 false
    public $timestamps = false; // 如果您的表没有这些字段，请添加这行

    // 设置模型的 fillable 或 guarded 属性，以便在使用模型的 create 或 update 方法时允许批量赋值
    protected $fillable = ['song_name', 'torrent_id', 'duration', 'artist_name'];

    // 定义与其他模型的关系，例如如果一个音乐属于一个艺术家和一个种子
    public function artist()
    {
        return $this->belongsTo(Artist::class);
    }

    public function torrent()
    {
        return $this->belongsTo(Torrent::class);
    }
}
