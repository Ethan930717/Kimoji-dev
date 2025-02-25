<?php
/**
 * NOTICE OF LICENSE.
 *
 * UNIT3D Community Edition is open-sourced software licensed under the GNU Affero General Public License v3.0
 * The details is bundled with this project in the file LICENSE.txt.
 *
 * @project    UNIT3D Community Edition
 *
 * @author     HDVinnie <hdinnovations@protonmail.com>
 * @license    https://www.gnu.org/licenses/agpl-3.0.en.html/ GNU Affero General Public License v3.0
 */

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Genre;
use App\Models\Group;
use App\Models\Resolution;
use App\Models\Rss;
use App\Models\Torrent;
use App\Models\Type;
use App\Models\User;
use Illuminate\Http\Request;
use Exception;

/**
 * @see \Tests\Todo\Feature\Http\Controllers\RssControllerTest
 */
class RssController extends Controller
{
    /**
     * Display a listing of the RSS resource.
     */
    public function index(Request $request, $hash = null): \Illuminate\Contracts\View\Factory|\Illuminate\View\View
    {
        return view('rss.index', [
            'hash'        => $hash,
            'public_rss'  => Rss::where('is_private', '=', 0)->oldest('position')->get(),
            'private_rss' => Rss::where('is_private', '=', 1)->where('user_id', '=', $request->user()->id)->latest()->get(),
            'user'        => $request->user(),
        ]);
    }

    /**
     * Show the form for creating a new RSS resource.
     */
    public function create(Request $request): \Illuminate\Contracts\View\Factory|\Illuminate\View\View
    {
        return view('rss.create', [
            'categories'  => Category::select(['id', 'name', 'position'])->orderBy('position')->get(),
            'types'       => Type::select(['id', 'name', 'position'])->orderBy('position')->get(),
            'resolutions' => Resolution::select(['id', 'name', 'position'])->orderBy('position')->get(),
            'genres'      => Genre::orderBy('name')->get(),
            'user'        => $request->user(),
        ]);
    }

    /**
     * Store a newly created RSS resource in storage.
     */
    public function store(Request $request): \Illuminate\Http\RedirectResponse|\Illuminate\Http\Response
    {
        $v = validator($request->all(), [
            'name'          => 'required|min:3|max:255',
            'search'        => 'max:255',
            'description'   => 'max:255',
            'uploader'      => 'max:255',
            'categories'    => 'sometimes|array|max:999',
            'categories.*'  => 'sometimes|exists:categories,id',
            'types'         => 'sometimes|array|max:999',
            'types.*'       => 'sometimes|exists:types,id',
            'resolutions'   => 'sometimes|array|max:999',
            'resolutions.*' => 'sometimes|exists:resolutions,id',
            'genres'        => 'sometimes|array|max:999',
            'genres.*'      => 'sometimes|exists:genres,id',
            'position'      => 'sometimes|integer|max:9999',
        ]);

        $params = $request->only([
            'name',
            'search',
            'description',
            'uploader',
            'imdb',
            'tvdb',
            'tmdb',
            'mal',
            'categories',
            'types',
            'resolutions',
            'genres',
            'freeleech',
            'doubleupload',
            'featured',
            'stream',
            'highspeed',
            'sd',
            'internal',
            'personalrelease',
            'bookmark',
            'alive',
            'dying',
            'dead',
        ]);

        if ($v->passes()) {
            $rss = new Rss();
            $rss->name = $request->input('name');
            $rss->user_id = $request->user()->id;
            $expected = $rss->expected_fields;
            $rss->json_torrent = array_merge($expected, $params);
            $rss->is_private = true;
            $rss->save();

            return to_route('rss.index', ['hash' => 'private'])
                ->withSuccess(trans('rss.created'));
        }

        return to_route('rss.create')
            ->withErrors($v->errors());
    }

    /**
     * Display the specified RSS resource.
     *
     * @throws Exception
     */
    public function show(int $id, string $rsskey): array|\Illuminate\Http\Response
    {
        $user = User::where('rsskey', '=', $rsskey)->sole();

        $bannedGroup = cache()->rememberForever('banned_group', fn () => Group::where('slug', '=', 'banned')->pluck('id'));
        $disabledGroup = cache()->rememberForever('disabled_group', fn () => Group::where('slug', '=', 'disabled')->pluck('id'));

        abort_if($user->group_id == $bannedGroup[0] || $user->group_id == $disabledGroup[0] || !$user->active, 404);

        $rss = Rss::query()
            ->where(
                fn ($query) => $query
                    ->where('user_id', '=', $user->id)
                    ->orWhere('is_private', '=', 0)
            )
            ->findOrFail($id);

        $search = $rss->object_torrent;

        $cacheKey = 'rss:'.$rss->id;

        $torrents = cache()->remember($cacheKey, 300, fn () => Torrent::query()
            ->select([
                'name',
                'id',
                'category_id',
                'type_id',
                'resolution_id',
                'size',
                'created_at',
                'seeders',
                'leechers',
                'times_completed',
                'user_id',
                'anon',
                'imdb',
                'tmdb',
                'tvdb',
                'mal',
                'internal',
            ])
            ->with([
                'user:id,username,rsskey',
                'category:id,name,movie_meta,tv_meta',
                'type:id,name',
                'resolution:id,name'
            ])
            ->when($search->search !== null, fn ($query) => $query->ofName($search->search))
            ->when($search->description !== null, fn ($query) => $query->ofDescription($search->description)->orWhere->ofMediainfo($search->description))
            ->when($search->uploader !== null, fn ($query) => $query->ofUploader($search->uploader, $user))
            ->when($search->categories !== null, fn ($query) => $query->ofCategory($search->categories))
            ->when($search->types !== null, fn ($query) => $query->ofType($search->types))
            ->when($search->resolutions !== null, fn ($query) => $query->ofResolution($search->resolutions))
            ->when($search->genres !== null, fn ($query) => $query->ofGenre($search->genres))
            ->when($search->tmdb !== null, fn ($query) => $query->ofTmdb((int) $search->tmdb))
            ->when($search->imdb !== null, fn ($query) => $query->ofImdb((int) (preg_match('/tt0*(?=(\d{7,}))/', (string) $search->imdb, $matches) ? $matches[1] : $search->imdb)))
            ->when($search->tvdb !== null, fn ($query) => $query->ofTvdb((int) $search->tvdb))
            ->when($search->mal !== null, fn ($query) => $query->ofMal((int) $search->mal))
            ->when($search->freeleech !== null, fn ($query) => $query->ofFreeleech([25, 50, 75, 100]))
            ->when($search->doubleupload !== null, fn ($query) => $query->doubleup())
            ->when($search->featured !== null, fn ($query) => $query->featured())
            ->when($search->stream !== null, fn ($query) => $query->streamOptimized())
            ->when($search->sd !== null, fn ($query) => $query->sd())
            ->when($search->highspeed !== null, fn ($query) => $query->highspeed())
            ->when($search->bookmark !== null, fn ($query) => $query->bookmarkedBy($user))
            ->when($search->internal !== null, fn ($query) => $query->internal())
            ->when($search->personalrelease !== null, fn ($query) => $query->personalRelease())
            ->when($search->alive !== null, fn ($query) => $query->alive())
            ->when($search->dying !== null, fn ($query) => $query->dying())
            ->when($search->dead !== null, fn ($query) => $query->dead())
            ->orderByDesc('bumped_at')
            ->take(50)
            ->get());

        return response()->view('rss.show', [
            'torrents' => $torrents,
            'user'     => $user,
            'rss'      => $rss,
        ])
            ->header('Content-Type', 'text/xml');
    }

    /**
     * Show the form for editing the specified RSS resource.
     */
    public function edit(Request $request, int $id): \Illuminate\Contracts\View\Factory|\Illuminate\View\View
    {
        $user = $request->user();
        $rss = Rss::where('is_private', '=', 1)->findOrFail($id);

        abort_unless($user->group->is_modo || $user->id === $rss->user_id, 403);

        return view('rss.edit', [
            'categories'  => Category::select(['id', 'name', 'position'])->orderBy('position')->get(),
            'types'       => Type::select(['id', 'name', 'position'])->orderBy('position')->get(),
            'resolutions' => Resolution::select(['id', 'name', 'position'])->orderBy('position')->get(),
            'genres'      => Genre::orderBy('name')->get(),
            'user'        => $user,
            'rss'         => $rss,
        ]);
    }

    /**
     * Update the specified RSS resource in storage.
     */
    public function update(Request $request, int $id): \Illuminate\Http\RedirectResponse|\Illuminate\Http\Response
    {
        $user = $request->user();
        $rss = Rss::where('is_private', '=', 1)->findOrFail($id);

        abort_unless($user->group->is_modo || $user->id === $rss->user_id, 403);

        $v = validator($request->all(), [
            'search'        => 'max:255',
            'description'   => 'max:255',
            'uploader'      => 'max:255',
            'categories'    => 'sometimes|array|max:999',
            'categories.*'  => 'sometimes|exists:categories,id',
            'types'         => 'sometimes|array|max:999',
            'types.*'       => 'sometimes|exists:types,id',
            'resolutions'   => 'sometimes|array|max:999',
            'resolutions.*' => 'sometimes|exists:resolutions,id',
            'genres'        => 'sometimes|array|max:999',
            'genres.*'      => 'sometimes|exists:genres,id',
            'position'      => 'sometimes|integer|max:9999',
        ]);

        $params = $request->only([
            'search',
            'description',
            'uploader',
            'imdb',
            'tvdb',
            'tmdb',
            'mal',
            'categories',
            'types',
            'resolutions',
            'genres',
            'freeleech',
            'doubleupload',
            'featured',
            'stream',
            'highspeed',
            'sd',
            'internal',
            'personalrelease',
            'bookmark',
            'alive',
            'dying',
            'dead',
        ]);

        if ($v->passes()) {
            $expected = $rss->expected_fields;
            $push = array_merge($expected, $params);
            $rss->json_torrent = array_merge($rss->json_torrent, $push);
            $rss->is_private = true;
            $rss->save();

            return to_route('rss.index', ['hash' => 'private'])
                ->withSuccess(trans('rss.created'));
        }

        return to_route('rss.create', ['id' => $id])
            ->withErrors($v->errors());
    }

    /**
     * Remove the specified RSS resource from storage.
     *
     * @throws Exception
     */
    public function destroy(Request $request, int $id): \Illuminate\Http\RedirectResponse|\Illuminate\Http\Response
    {
        $user = $request->user();
        $rss = Rss::where('is_private', '=', 1)->findOrFail($id);

        abort_unless($user->group->is_modo || $user->id === $rss->user_id, 403);

        $rss->delete();

        return to_route('rss.index', ['hash' => 'private'])
            ->withSuccess(trans('rss.deleted'));
    }
}
