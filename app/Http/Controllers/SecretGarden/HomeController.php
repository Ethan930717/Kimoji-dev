<?php

namespace App\Http\Controllers\SecretGarden;

use App\Http\Controllers\Controller;
use App\Models\Actor;
use App\Models\Video;
use App\Models\VideoGenre;
use App\Models\VideoMaker;
use App\Models\VideoLabel;
use App\Models\VideoSeries;
use App\Models\VideoTag;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    /**
     * Display Media Hubs.
     */
    public function index(): \Illuminate\Contracts\View\Factory|\Illuminate\View\View
    {

        $latestUpdate = Video::max('release_date');

        return view('secretgarden.home', [
            'videosCount'      => Video::count(),
            'actorsCount'      => Actor::count(),
            'genresCount'      => VideoGenre::count(),
            'makersCount'      => VideoMaker::count(),
            'labelsCount'      => VideoLabel::count(),
            'seriesCount'      => VideoSeries::count(),
            'tagsCount'        => VideoTag::count(),
            'latestUpdate'     => $latestUpdate,
        ]);
    }
}
