<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SecretGardenLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'url',
        'ip_address',
        'created_at',
        'updated_at',
    ];
}
