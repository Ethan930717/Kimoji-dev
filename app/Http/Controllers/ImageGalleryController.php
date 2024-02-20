<?php

namespace App\Http\Controllers;

use App\Models\FriendsiteImage;
use Illuminate\Support\Facades\Cache;


class ImageGalleryController extends Controller
{
    public function showGallery()
    {
        $images = Cache::remember('friendsite_images', 60, function () {
            return FriendsiteImage::all();
        });

        return view('page.friendsiteimage', compact('images'));
    }

}
