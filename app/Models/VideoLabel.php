<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VideoLabel extends Model
{
    use HasFactory;

    protected $table = 'video_label';

    protected $fillable = ['name', 'poster'];

    public function videos()
    {
        return $this->belongsToMany(Video::class, 'video_label_video', 'label_id', 'video_id');
    }
}
