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

use App\Bots\IRCAnnounceBot;
use App\Models\FeaturedTorrent;
use App\Models\FreeleechToken;
use App\Models\Scopes\ApprovedScope;
use App\Models\Torrent;
use App\Repositories\ChatRepository;
use App\Services\Unit3dAnnounce;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

/**
 * @see \Tests\Todo\Feature\Http\Controllers\TorrentControllerTest
 */
class TorrentBuffController extends Controller
{
    /**
     * TorrentController Constructor.
     */
    public function __construct(private readonly ChatRepository $chatRepository)
    {
    }

    /**
     * Bump A Torrent.
     */
    public function bumpTorrent(Request $request, int $id): \Illuminate\Http\RedirectResponse
    {
        $user = $request->user();

        abort_unless($user->group->is_modo || $user->group->is_internal, 403);
        $torrent = Torrent::withoutGlobalScope(ApprovedScope::class)->findOrFail($id);
        $torrent->bumped_at = Carbon::now();
        $torrent->save();

        // Announce To Chat
        $torrentUrl = href_torrent($torrent);
        $profileUrl = href_profile($user);

        $this->chatRepository->systemMessage(
            sprintf('Attention, [url=%s]%s[/url] has been bumped to the top by [url=%s]%s[/url]! It could use more seeds!', $torrentUrl, $torrent->name, $profileUrl, $user->username)
        );

        // Announce To IRC
        if (config('irc-bot.enabled')) {
            $appname = config('app.name');
            $ircAnnounceBot = new IRCAnnounceBot();
            $ircAnnounceBot->message(config('irc-bot.channel'), '['.$appname.'] User '.$user->username.' has bumped '.$torrent->name.' , it could use more seeds!');
            $ircAnnounceBot->message(config('irc-bot.channel'), '[Category: '.$torrent->category->name.'] [Type: '.$torrent->type->name.'] [Size:'.$torrent->getSize().']');
            $ircAnnounceBot->message(config('irc-bot.channel'), sprintf('[Link: %s]', $torrentUrl));
        }

        return to_route('torrents.show', ['id' => $torrent->id])
            ->withSuccess('资源置顶成功');
    }

    /**
     * Sticky A Torrent.
     */
    public function sticky(Request $request, int $id): \Illuminate\Http\RedirectResponse
    {
        $user = $request->user();

        abort_unless($user->group->is_modo || $user->group->is_internal, 403);
        $torrent = Torrent::withoutGlobalScope(ApprovedScope::class)->findOrFail($id);
        $torrent->sticky = !$torrent->sticky;
        $torrent->save();

        return to_route('torrents.show', ['id' => $torrent->id])
            ->withSuccess('种子置顶状态已成功调整！');
    }

    /**
     * Freeleech A Torrent (1% to 100% Free).
     */
    public function grantFL(Request $request, int $id): \Illuminate\Http\RedirectResponse
    {
        $user = $request->user();

        abort_unless($user->group->is_modo || $user->group->is_internal, 403);
        $torrent = Torrent::withoutGlobalScope(ApprovedScope::class)->findOrFail($id);
        $torrentUrl = href_torrent($torrent);

        $request->validate([
            'freeleech' => 'numeric|min:0|max:100',
            'fl_until'  => 'nullable|numeric'
        ]);

        if ($request->freeleech != 0) {
            if ($request->fl_until !== null) {
                $torrent->fl_until = Carbon::now()->addDays($request->fl_until);
            }
        }


        $torrent->free = $request->freeleech;
        $torrent->save();

        cache()->forget('announce-torrents:by-infohash:'.$torrent->info_hash);

        Unit3dAnnounce::addTorrent($torrent);

        return to_route('torrents.show', ['id' => $torrent->id])
            ->withSuccess('已将资源设为免费');
    }

    /**
     * Feature A Torrent.
     */
    public function grantFeatured(Request $request, int $id): \Illuminate\Http\RedirectResponse
    {
        $user = $request->user();

        abort_unless($user->group->is_modo || $user->group->is_internal, 403);
        $torrent = Torrent::withoutGlobalScope(ApprovedScope::class)->findOrFail($id);

        if ($torrent->featured == 0) {
            $torrent->free = 100;
            $torrent->doubleup = true;
            $torrent->featured = true;
            $torrent->save();

            cache()->forget('announce-torrents:by-infohash:'.$torrent->info_hash);

            Unit3dAnnounce::addTorrent($torrent);

            $featured = new FeaturedTorrent();
            $featured->user_id = $user->id;
            $featured->torrent_id = $torrent->id;
            $featured->save();


            return to_route('torrents.show', ['id' => $torrent->id])
                ->withSuccess('已将资源设为精选');
        }

        return to_route('torrents.show', ['id' => $torrent->id])
            ->withErrors('该种子已经是精选资源');
    }

    /**
     * UnFeature A Torrent.
     */
    public function revokeFeatured(Request $request, int $id): \Illuminate\Http\RedirectResponse
    {
        $user = $request->user();

        abort_unless($user->group->is_modo, 403);

        $featured_torrent = FeaturedTorrent::where('torrent_id', '=', $id)->sole();

        $torrent = Torrent::withoutGlobalScope(ApprovedScope::class)->findOrFail($id);
        $torrent->free = 0;
        $torrent->doubleup = false;
        $torrent->featured = false;
        $torrent->save();

        cache()->forget('announce-torrents:by-infohash:'.$torrent->info_hash);

        Unit3dAnnounce::addTorrent($torrent);

        $featured_torrent->delete();

        return to_route('torrents.show', ['id' => $torrent->id])
            ->withSuccess('已从精选资源中删除');
    }

    /**
     * Double Upload A Torrent.
     */
    public function grantDoubleUp(Request $request, int $id): \Illuminate\Http\RedirectResponse
    {
        $user = $request->user();

        abort_unless($user->group->is_modo || $user->group->is_internal, 403);
        $torrent = Torrent::withoutGlobalScope(ApprovedScope::class)->findOrFail($id);
        $torrentUrl = href_torrent($torrent);

        if (!$torrent->doubleup) {
            $torrent->doubleup = true;
            $du_until = $request->input('du_until');
            if ($du_until !== null) {
                $torrent->du_until = Carbon::now()->addDays($request->input('du_until'));
            }
            }
         else {
            $torrent->doubleup = false;
        }

        $torrent->save();

        cache()->forget('announce-torrents:by-infohash:'.$torrent->info_hash);

        Unit3dAnnounce::addTorrent($torrent);

        return to_route('torrents.show', ['id' => $torrent->id])
            ->withSuccess('双倍上传已开启');
    }

    /**
     * Use Freeleech Token On A Torrent.
     */
    public function freeleechToken(Request $request, int $id): \Illuminate\Http\RedirectResponse
    {
        $user = $request->user();
        $torrent = Torrent::withoutGlobalScope(ApprovedScope::class)->findOrFail($id);

        $activeToken = cache()->get('freeleech_token:'.$user->id.':'.$torrent->id);

        if ($user->fl_tokens >= 1 && !$activeToken) {
            $freeleechToken = new FreeleechToken();
            $freeleechToken->user_id = $user->id;
            $freeleechToken->torrent_id = $torrent->id;
            $freeleechToken->save();

            Unit3dAnnounce::addFreeleechToken($user->id, $torrent->id);

            $user->fl_tokens -= '1';
            $user->save();

            cache()->put('freeleech_token:'.$user->id.':'.$torrent->id, true);

            return to_route('torrents.show', ['id' => $torrent->id])
                ->withSuccess('免费令生效，该种子将在未来24小时内免费');
        }

        return to_route('torrents.show', ['id' => $torrent->id])
            ->withErrors('免费令不足或当前种子已处于免费状态');
    }

    /**
     * Set Torrents Refudable Status.
     */
    public function setRefundable(Request $request, int $id): \Illuminate\Http\RedirectResponse
    {
        $user = $request->user();
        abort_unless($user->group->is_modo || $user->group->is_internal, 403);

        $torrent = Torrent::withoutGlobalScope(ApprovedScope::class)->findOrFail($id);
        $torrent_url = href_torrent($torrent);

        if (!$torrent->refundable) {
            $torrent->refundable = true;

        } else {
            $torrent->refundable = 0;

        }

        $torrent->save();

        return to_route('torrents.show', ['id' => $torrent->id])
            ->withSuccess('退款状态更新成功');
    }
}
