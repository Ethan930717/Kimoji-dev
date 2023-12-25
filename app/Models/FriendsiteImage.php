<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FriendsiteImage extends Model
{
    // 指定模型关联的数据表名称
    protected $table = 'friendsite_images';

    // 指定可以被批量赋值的字段
    protected $fillable = ['name', 'url'];
    // 如果你不想让 Eloquent 自动维护 created_at 和 updated_at 字段，
    // 可以设置 $timestamps 为 false
    // protected $timestamps = false;
}
