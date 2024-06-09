<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VideoMaker extends Model
{
    use HasFactory;

    protected $table = 'video_maker';

    protected $fillable = ['name', 'poster'];

    public function videos()
    {
        return $this->belongsToMany(Video::class, 'video_maker_video', 'maker_id', 'video_id');
    }
}
