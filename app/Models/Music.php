<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Music extends Model
{
    use HasFactory;

    protected $table = 'music'; // 只有当表名不是 'music' 时才需要添加这行

    public $timestamps = false; // 如果您的表没有这些字段，请添加这行

    protected $fillable = ['song_name', 'torrent_id', 'duration', 'artist_name'];

    public function artist()
    {
        return $this->belongsTo(Artist::class);
    }

    public function torrent()
    {
        return $this->belongsTo(Torrent::class);
    }
}
