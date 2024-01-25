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

use App\Helpers\Bencode;
use App\Helpers\MediaInfo;
use App\Helpers\BDInfo;
use App\Helpers\TorrentHelper;
use App\Helpers\TorrentTools;
use App\Http\Requests\StoreTorrentRequest;
use App\Http\Requests\UpdateTorrentRequest;
use App\Models\Audit;
use App\Models\BonTransactions;
use App\Models\Category;
use App\Models\Distributor;
use App\Models\FeaturedTorrent;
use App\Models\History;
use App\Models\Keyword;
use App\Models\Movie;
use App\Models\PrivateMessage;
use App\Models\Region;
use App\Models\Resolution;
use App\Models\Scopes\ApprovedScope;
use App\Models\Torrent;
use App\Models\TorrentFile;
use App\Models\Tv;
use App\Models\Type;
use App\Models\User;
use App\Repositories\ChatRepository;
use App\Services\Tmdb\TMDBScraper;
use App\Services\Unit3dAnnounce;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Intervention\Image\Facades\Image;
use MarcReichel\IGDBLaravel\Models\Game;
use MarcReichel\IGDBLaravel\Models\PlatformLogo;
use Exception;
use ReflectionException;
use JsonException;

/**
 * @see \Tests\Todo\Feature\Http\Controllers\TorrentControllerTest
 */
class TorrentController extends Controller
{
    /**
     * TorrentController Constructor.
     */
    public function __construct(private readonly ChatRepository $chatRepository)
    {
    }

    /**
     * Display a listing of the Torrent resource.
     */
    public function index(): \Illuminate\Contracts\View\Factory|\Illuminate\View\View
    {
        return view('torrent.index');
    }

    /**
     * Display The Torrent reasource.
     *
     * @throws JsonException
     * @throws \MarcReichel\IGDBLaravel\Exceptions\MissingEndpointException
     * @throws ReflectionException
     * @throws \MarcReichel\IGDBLaravel\Exceptions\InvalidParamsException
     */
    public function show(Request $request, int|string $id): \Illuminate\Contracts\View\Factory|\Illuminate\View\View
    {
        $user = $request->user();

        $torrent = Torrent::withoutGlobalScope(ApprovedScope::class)
            ->with(['user', 'comments', 'category', 'type', 'resolution', 'subtitles', 'playlists'])
            ->withExists(['bookmarks' => fn ($query) => $query->where('user_id', '=', $user->id)])
            ->withExists(['freeleechTokens' => fn ($query) => $query->where('user_id', '=', $user->id)])
            ->findOrFail($id);

        $meta = null;
        $platforms = null;
        $bdInfo = $torrent->bdinfo !== null ? (new BDInfo())->parse($torrent->bdinfo) : null;


        if ($torrent->category->tv_meta && $torrent->tmdb) {
            $meta = Tv::with([
                'genres',
                'credits' => ['person', 'occupation'],
                'companies',
                'networks',
                'recommendedTv:id,name,poster,first_air_date'
            ])->find($torrent->tmdb);
        }

        if ($torrent->category->movie_meta && $torrent->tmdb) {
            $meta = Movie::with([
                'genres',
                'credits' => ['person', 'occupation'],
                'companies',
                'collection',
                'recommendedMovies:id,title,poster,release_date'
            ])
                ->find($torrent->tmdb);
        }

        if ($torrent->category->game_meta && $torrent->igdb) {
            $meta = Game::with([
                'cover'    => ['url', 'image_id'],
                'artworks' => ['url', 'image_id'],
                'genres'   => ['name'],
                'videos'   => ['video_id', 'name'],
                'involved_companies.company',
                'involved_companies.company.logo',
                'platforms', ])
                ->find($torrent->igdb);
            $link = collect($meta->videos)->take(1)->pluck('video_id');
            $platforms = PlatformLogo::whereIn('id', collect($meta->platforms)->pluck('platform_logo')->toArray())->get();
        }

        return view('torrent.show', [
            'torrent'            => $torrent,
            'user'               => $user,
            'personal_freeleech' => cache()->get('personal_freeleech:'.$user->id),
            'meta'               => $meta,
            'platforms'          => $platforms,
            'total_tips'         => BonTransactions::where('torrent_id', '=', $id)->sum('cost'),
            'user_tips'          => BonTransactions::where('torrent_id', '=', $id)->where('sender_id', '=', $user->id)->sum('cost'),
            'featured'           => $torrent->featured == 1 ? FeaturedTorrent::where('torrent_id', '=', $id)->first() : null,
            'mediaInfo'          => $torrent->mediainfo !== null ? (new MediaInfo())->parse($torrent->mediainfo) : null,
            'bdInfo'             => $bdInfo,
            'last_seed_activity' => History::where('torrent_id', '=', $torrent->id)->where('seeder', '=', 1)->latest('updated_at')->first(),
            'playlists'          => $user->playlists,
            'audits'             => Audit::with('user')->where('model_entry_id', '=', $torrent->id)->where('model_name', '=', 'Torrent')->latest()->get(),
        ]);
    }

    /**
     * Show the form for editing the specified Torrent resource.
     */
    public function edit(Request $request, int $id): \Illuminate\Contracts\View\Factory|\Illuminate\View\View
    {
        $user = $request->user();
        $torrent = Torrent::withoutGlobalScope(ApprovedScope::class)->findOrFail($id);

        abort_unless($user->group->is_modo || $user->id === $torrent->user_id, 403);

        return view('torrent.edit', [
            'categories' => Category::query()
                ->orderBy('position')
                ->get()
                ->mapWithKeys(fn ($cat) => [
                    $cat['id'] => [
                        'name' => $cat['name'],
                        'type' => match (true) {
                            $cat->movie_meta => 'movie',
                            $cat->tv_meta    => 'tv',
                            $cat->game_meta  => 'game',
                            $cat->music_meta => 'music',
                            $cat->no_meta    => 'no',
                            default          => 'no',
                        },
                    ]
                ]),
            'types'        => Type::orderBy('position')->get()->mapWithKeys(fn ($type) => [$type['id'] => ['name' => $type['name']]]),
            'resolutions'  => Resolution::orderBy('position')->get(),
            'regions'      => Region::orderBy('position')->get(),
            'distributors' => Distributor::orderBy('name')->get(),
            'keywords'     => Keyword::where('torrent_id', '=', $torrent->id)->pluck('name'),
            'music_url'    => $torrent->music_url,
            'torrent'      => $torrent,
            'user'         => $user,
        ]);
    }

    /**
     * Update the specified Torrent resource in storage.
     */
    public function update(UpdateTorrentRequest $request, int $id): \Illuminate\Http\RedirectResponse
    {
        $user = $request->user();
        $torrent = Torrent::withoutGlobalScope(ApprovedScope::class)->findOrFail($id);

        abort_unless($user->group->is_modo || $user->id === $torrent->user_id, 403);

        $torrent->update($request->validated());

        // Cover Image for No-Meta and Music-Meta Torrents
        if ($request->hasFile('torrent-cover')) {
            $image_cover = $request->file('torrent-cover');
            $filename_cover = 'torrent-cover_'.$torrent->id.'.jpg';
            $path_cover = public_path('/files/img/'.$filename_cover);
            $width = $height = 500;

            if ($torrent->category && $torrent->category->no_meta) {
                $width = 600;
                $height = 400;
            }
            Image::make($image_cover->getRealPath())->fit($width, $height)->encode('jpg', 90)->save($path_cover);
        }

        // Banner Image for No-Meta Torrents
        if ($request->hasFile('torrent-banner')) {
            $image_cover = $request->file('torrent-banner');
            $filename_cover = 'torrent-banner_'.$torrent->id.'.jpg';
            $path_cover = public_path('/files/img/'.$filename_cover);
            Image::make($image_cover->getRealPath())->fit(960, 540)->encode('jpg', 90)->save($path_cover);
        }

        // Torrent Keywords System
        Keyword::where('torrent_id', '=', $torrent->id)->delete();

        $keywords = [];

        foreach (TorrentTools::parseKeywords($request->string('keywords')) as $keyword) {
            $keywords[] = ['torrent_id' => $torrent->id, 'name' => $keyword];
        }

        foreach (collect($keywords)->chunk(65_000 / 2) as $keywords) {
            Keyword::upsert($keywords->toArray(), ['torrent_id', 'name'], []);
        }

        $category = $torrent->category;

        // TMDB Meta
        if ($torrent->tmdb != 0) {
            switch (true) {
                case $category->tv_meta:
                    (new TMDBScraper())->tv($torrent->tmdb);

                    break;
                case $category->movie_meta:
                    (new TMDBScraper())->movie($torrent->tmdb);

                    break;
            }
        }

        return to_route('torrents.show', ['id' => $id])
            ->withSuccess('编辑成功');
    }

    /**
     * Delete A Torrent.
     *
     * @throws Exception
     */
    public function destroy(Request $request, int $id): \Illuminate\Http\RedirectResponse
    {
        $request->validate([
            'message' => [
                'required',
                'min:1',
            ],
        ]);

        $user = $request->user();
        $torrent = Torrent::withoutGlobalScope(ApprovedScope::class)->findOrFail($id);

        abort_unless($user->group->is_modo || ($user->id === $torrent->user_id && Carbon::now()->lt($torrent->created_at->addDay())), 403);

        $pms = [];

        foreach (History::where('torrent_id', '=', $torrent->id)->pluck('user_id') as $user_id) {
            $pms[] = [
                'sender_id'   => User::SYSTEM_USER_ID,
                'receiver_id' => $user_id,
                'subject'     => '种子已删除 - '.$torrent->name,
                'message'     => '[b]请注意:[/b]  '.$torrent->name." 已被删除，我们留意到您正在连接这个种子，请您抽空把这个种子删除吧.\n\n[b]删种原因:[/b] ".$request->message."\n\n[color=red][b]这是一条系统消息，请勿回复！[/b][/color]",
                'created_at'  => now(),
                'updated_at'  => now(),
            ];
        }

        PrivateMessage::insert($pms);

        // Reset Requests
        $torrent->requests()->update([
            'torrent_id' => null,
        ]);

        //Remove Torrent related info
        cache()->forget(sprintf('torrent:%s', $torrent->info_hash));

        $torrent->comments()->delete();
        $torrent->peers()->delete();
        $torrent->history()->delete();
        $torrent->hitrun()->delete();
        $torrent->files()->delete();
        $torrent->playlists()->detach();
        $torrent->subtitles()->delete();
        $torrent->resurrections()->delete();
        $torrent->featured()->delete();

        $freeleechTokens = $torrent->freeleechTokens();

        foreach ($freeleechTokens->get() as $freeleechToken) {
            cache()->forget('freeleech_token:'.$freeleechToken->user_id.':'.$torrent->id);
        }

        $freeleechTokens->delete();

        cache()->forget('announce-torrents:by-infohash:'.$torrent->info_hash);

        Unit3dAnnounce::removeTorrent($torrent);

        $torrent->delete();

        return to_route('torrents.index')
            ->withSuccess('种子删除成功');
    }

    /**
     * Torrent Upload Form.
     */
    public function create(Request $request): \Illuminate\Contracts\View\Factory|\Illuminate\View\View
    {
        return view('torrent.create', [
            'categories' => Category::orderBy('position')
                ->get()
                ->mapWithKeys(fn ($category) => [$category->id => [
                    'name' => $category->name,
                    'type' => match (true) {
                        $category->movie_meta => 'movie',
                        $category->tv_meta    => 'tv',
                        $category->game_meta  => 'game',
                        $category->music_meta => 'music',
                        $category->no_meta    => 'no',
                        default               => 'no',
                    },
                ]])
                ->toArray(),
            'types'        => Type::orderBy('position')->get(),
            'resolutions'  => Resolution::orderBy('position')->get(),
            'regions'      => Region::orderBy('position')->get(),
            'distributors' => Distributor::orderBy('name')->get(),
            'user'         => $request->user(),
            'category_id'  => $request->category_id,
            'title'        => urldecode((string) $request->title),
            'imdb'         => $request->imdb,
            'tmdb'         => $request->tmdb,
            'mal'          => $request->mal,
            'tvdb'         => $request->tvdb,
            'igdb'         => $request->igdb,
        ]);
    }

    /**
     * Upload A Torrent.
     */
    public function store(StoreTorrentRequest $request): \Illuminate\Http\RedirectResponse
    {
        $user = $request->user();

        $decodedTorrent = TorrentTools::normalizeTorrent($request->file('torrent'));

        $meta = Bencode::get_meta($decodedTorrent);

        $fileName = uniqid('', true).'.torrent'; // Generate a unique name
        file_put_contents(getcwd().'/files/torrents/'.$fileName, Bencode::bencode($decodedTorrent));

        $piecesHash = TorrentTools::extractPiecesHash($request->file('torrent')->getRealPath());

        $torrent = Torrent::create([
            'mediainfo'    => TorrentTools::anonymizeMediainfo($request->string('mediainfo')),
            'info_hash'    => Bencode::get_infohash($decodedTorrent),
            'file_name'    => $fileName,
            'num_file'     => $meta['count'],
            'folder'       => Bencode::get_name($decodedTorrent),
            'size'         => $meta['size'],
            'nfo'          => $request->hasFile('nfo') ? TorrentTools::getNfo($request->file('nfo')) : '',
            'user_id'      => $user->id,
            'moderated_at' => now(),
            'moderated_by' => User::SYSTEM_USER_ID,
            'pieces_hash'  => $piecesHash,
            'music_url'    => $request->input('music_url'),
        ] + $request->safe()->except(['torrent']));

        // Count and save the torrent number in this category
        $category = Category::findOrFail($request->integer('category_id'));
        $category->num_torrent = $category->torrents()->count();
        $category->save();

        // Backup the files contained in the torrent
        $files = TorrentTools::getTorrentFiles($decodedTorrent);

        foreach($files as &$file) {
            $file['torrent_id'] = $torrent->id;
        }

        // Can't insert them all at once since some torrents have more files than mysql supports placeholders.
        // Divide by 3 since we're inserting 3 fields: name, size and torrent_id
        foreach (collect($files)->chunk(intdiv(65_000, 3)) as $files) {
            TorrentFile::insert($files->toArray());
        }

        // TMDB Meta
        if ($torrent->tmdb != 0) {
            switch (true) {
                case $category->tv_meta:
                    (new TMDBScraper())->tv($torrent->tmdb);

                    break;
                case $category->movie_meta:
                    (new TMDBScraper())->movie($torrent->tmdb);

                    break;
            }
        }

        // Torrent Keywords System
        $keywords = [];

        foreach (TorrentTools::parseKeywords($request->string('keywords')) as $keyword) {
            $keywords[] = ['torrent_id' => $torrent->id, 'name' => $keyword];
        }

        foreach (collect($keywords)->chunk(intdiv(65_000, 2)) as $keywords) {
            Keyword::upsert($keywords->toArray(), ['torrent_id', 'name'], []);
        }

        // Cover Image for No-Meta Torrents and Music-Meta Torrents
        if ($request->hasFile('torrent-cover')) {
            $image_cover = $request->file('torrent-cover');
            $filename_cover = 'torrent-cover_'.$torrent->id.'.jpg';
            $path_cover = public_path('/files/img/'.$filename_cover);

            $width = $height = 500;

            if ($category->no_meta) {
                $width = 600;
                $height = 400;
            }
            Image::make($image_cover->getRealPath())->fit($width, $height)->encode('jpg', 90)->save($path_cover);
        }

        // Banner Image for No-Meta Torrents and Music-Meta Torrents
        if ($request->hasFile('torrent-banner')) {
            $image_cover = $request->file('torrent-banner');
            $filename_cover = 'torrent-banner_'.$torrent->id.'.jpg';
            $path_cover = public_path('/files/img/'.$filename_cover);
            Image::make($image_cover->getRealPath())->fit(960, 540)->encode('jpg', 90)->save($path_cover);
        }

        // check for trusted user and update torrent
        if ($user->group->is_trusted && !$request->boolean('mod_queue_opt_in')) {
            $appurl = config('app.url');
            $user = $torrent->user;
            $username = $user->username;
            $anon = $torrent->anon;

            // Announce To Shoutbox
            if ($anon == 0) {
                $this->chatRepository->systemMessage(
                    sprintf('帅气的 [url=%s/users/', $appurl).$username.']'.$username.sprintf('[/url] 上传了新 '.$torrent->category->name.'. [url=%s/torrents/', $appurl).$torrent->id.']'.$torrent->name.'[/url], 快来看看吧！ :slight_smile:'
                );
            } else {
                $this->chatRepository->systemMessage(
                    sprintf('匿名用户上传了新'.$torrent->category->name.'. [url=%s/torrents/', $appurl).$torrent->id.']'.$torrent->name.'[/url], 快来看看吧! :slight_smile:'
                );
            }

            if ($torrent->free >= 1) {
                $this->chatRepository->systemMessage(
                    sprintf('乡亲们, [url=%s/torrents/', $appurl).$torrent->id.']'.$torrent->name.'[/url] 现在 '.$torrent->free.'% 免费! 快来看看! :fire:'
                );
            }

            TorrentHelper::approveHelper($torrent->id);
        }

        return to_route('download_check', ['id' => $torrent->id])
            ->withSuccess('您的种子文件已准备好下载和做种');
    }
}
