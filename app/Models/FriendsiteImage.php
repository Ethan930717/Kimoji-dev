<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FriendsiteImage extends Model
{
    protected $table = 'friendsite_images';

    protected $fillable = ['name', 'url'];

    /**
     * 查询作用域 - 按 name 字段升序排列图片
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeOrderedByName($query)
    {
        return $query->orderBy('name', 'asc');
    }

}
