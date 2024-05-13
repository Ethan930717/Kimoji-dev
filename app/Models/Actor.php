<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Actor extends Model
{
    use HasFactory;
    protected $table = 'actors'; // 指定模型对应的表名

    public $timestamps = false; // 如果您的表没有这些字段，请添加这行

    // 设置模型的 fillable 或 guarded 属性，以便在使用模型的 create 或 update 方法时允许批量赋值
    protected $fillable = ['name', 'english_name', 'birth_date', 'zodiac', 'blood_type', 'measurements', 'birthplace', 'hobbies_skills', 'description', 'gender', 'nationality', 'image_url'];
    // 如果您有日期字段，您可以添加它们到 $dates 属性中，以便它们将自动被转换为 Carbon 实例
    protected $dates = ['birth_date'];

    public function torrents()
    {
        return $this->hasMany(Torrent::class);
    }
}
