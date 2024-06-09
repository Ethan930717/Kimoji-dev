<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VideoSeries extends Model
{
    use HasFactory;

    protected $table = 'video_series';

    protected $fillable = ['name', 'poster'];

    public function videos()
    {
        return $this->belongsToMany(Video::class, 'video_series_video', 'series_id', 'video_id');
    }
}
