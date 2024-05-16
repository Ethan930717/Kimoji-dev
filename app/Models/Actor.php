<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Actor extends Model
{
    use HasFactory;
    protected $table = 'actors';

    public $timestamps = false;

    protected $fillable = ['name', 'english_name', 'birth_date', 'zodiac', 'blood_type', 'measurements', 'birthplace', 'hobbies_skills', 'description', 'gender', 'nationality', 'image_url'];
    protected $dates = ['birth_date'];

    public function torrents()
    {
        return $this->hasMany(Torrent::class);
    }
}
