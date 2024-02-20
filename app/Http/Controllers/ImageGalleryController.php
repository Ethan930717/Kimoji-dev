<?php

namespace App\Http\Controllers;

use App\Models\FriendsiteImage;

class ImageGalleryController extends Controller
{
    public function showGallery()
    {
        $images = cache()->remember('friendsite_images', 60, function () {
            return FriendsiteImage::all();
        });

        return view('page.friendsiteimage', compact('images'));
    }
}

