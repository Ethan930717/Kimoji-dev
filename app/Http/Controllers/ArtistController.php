<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Artist;
use App\Models\Torrent;


class ArtistController extends Controller
{
    public function index()
    {
        $artists = Artist::all();

        return view('artists.index', compact('artists'));
    }

    public function show($id)
    {
        $artist = Artist::findOrFail($id);
        $torrents = Torrent::where('name', 'like', '%'.$artist->name.'%')
            ->where('category_id', 3)
            ->get();

        return view('artists.show', compact('artist', 'torrents'));
    }

    public function edit($id)
    {
        $artist = Artist::findOrFail($id);

        return view('artists.edit', compact('artist'));
    }

    public function update(Request $request, $id)
    {
        $artist = Artist::findOrFail($id);
        $data = $request->validate([
            'birth_date'  => 'nullable|date',
            'zodiac'  => 'nullable|max:255',
            'blood_type'    => 'nullable|max:5',
            'measurements'   => 'nullable|max:255',
            'birthplace'     => 'nullable|max:255',
            'hobbies_skills'     => 'nullable|max:255',
            'description' => 'nullable',
        ]);
        $artist->update($data);

        return redirect()->route('artists.show', $id);
    }
}
