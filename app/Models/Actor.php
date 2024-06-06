<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class Actor extends Model
{
    use HasFactory;

    protected $table = 'actors';

    public $timestamps = false;

    protected $fillable = [
        'name', 'english_name', 'birth_date', 'zodiac', 'blood_type',
        'measurements', 'birthplace', 'hobbies_skills', 'description',
        'gender', 'nationality', 'image_url'
    ];

    protected $dates = ['birth_date'];

    public function videos()
    {
        return $this->hasMany(Video::class, 'actor_id');
    }

    protected static function boot()
    {
        parent::boot();

        static::saved(function ($actor) {
            Cache::forget('actors');
            Cache::forget("actor_{$actor->id}");
        });

        static::deleted(function ($actor) {
            Cache::forget('actors');
            Cache::forget("actor_{$actor->id}");
        });
    }
}

