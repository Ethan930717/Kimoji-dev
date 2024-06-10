<?php


namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VideoGenre extends Model
{
    use HasFactory;

    protected $table = 'video_genres';

    protected $fillable = ['name', 'poster'];

    public function videos()
    {
        return $this->belongsToMany(Video::class, 'video_genre_video', 'genre_id', 'video_id');
    }
}

