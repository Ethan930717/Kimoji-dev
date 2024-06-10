<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VideoTag extends Model
{
    use HasFactory;

    protected $table = 'video_tags';

    protected $fillable = ['name', 'poster'];

    public function videos()
    {
        return $this->belongsToMany(Video::class, 'video_tag_video', 'tag_id', 'video_id');
    }
}

