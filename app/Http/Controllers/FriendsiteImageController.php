<?php

namespace App\Http\Controllers;

use App\Models\FriendsiteImage;

class FriendsiteImageController extends Controller
{
    public function friendsiteimage()
    {
        // 使用查询作用域获取按 name 升序排列的图片
        $images = FriendsiteImage::orderedByName()->get();

        // 将图片数据传递给视图
        return view('page.friendsiteimage', compact('images'));
    }
}
