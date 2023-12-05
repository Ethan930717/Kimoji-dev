<?php

namespace App\Http\Controllers;

use App\Models\FriendsiteImage;

class ImageGalleryController extends Controller
{
    public function showGallery() {
        $images = FriendsiteImage::all();
        return view('page.friendsiteimage', compact('images'));
    }
}
