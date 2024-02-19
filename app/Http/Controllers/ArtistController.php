<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Artist;
use App\Models\Torrent;
use Illuminate\Support\Facades\Log;


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
        $torrents = Torrent::where('name', 'like', '%'.$artist->name.'%')->get();

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
            'birthday'  => 'nullable|date', // 生日可以为 null，如果提供则必须是日期格式
            'deathday'  => 'nullable|date', // 忌日可以为 null，如果提供则必须是日期格式
            'member'    => 'nullable|max:512', // 组成员可以为 null，最大长度为512个字符
            'country'   => 'nullable|max:255', // 国家可以为 null，最大长度为255个字符
            'label'     => 'nullable|max:255', // 唱片公司可以为 null，最大长度为255个字符
            'genre'     => 'nullable|max:255', // 风格可以为 null，最大长度为255个字符
            'biography' => 'nullable', // 传记可以为 null
        ]);
        $artist->update($data);

        return redirect()->route('artists.show', $id);
    }

    public function countryIndex()
    {
        Log::info('Accessing countryIndex method');
        $countries = Artist::select('country')->distinct()->orderBy('country', 'asc')->get();
        Log::info('Countries retrieved', ['countries' => $countries->pluck('country')]);
        return view('artists.country.index', compact('countries'));
    }

    public function countryShow($country_name)
    {
        // 解码 URL 参数以匹配数据库中的国家/地区名称
        $decoded_country_name = urldecode($country_name);

        $artists = Artist::where('country', '=', $decoded_country_name)
            ->orderBy('name', 'asc')
            ->get();

        return view('artists.country.show', compact('artists', 'country_name'));
    }

}
