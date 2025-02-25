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

namespace App\Helpers;

use App\Achievements\UserMade100Uploads;
use App\Achievements\UserMade200Uploads;
use App\Achievements\UserMade25Uploads;
use App\Achievements\UserMade300Uploads;
use App\Achievements\UserMade400Uploads;
use App\Achievements\UserMade500Uploads;
use App\Achievements\UserMade50Uploads;
use App\Achievements\UserMade600Uploads;
use App\Achievements\UserMade700Uploads;
use App\Achievements\UserMade800Uploads;
use App\Achievements\UserMade900Uploads;
use App\Achievements\UserMadeUpload;
use App\Bots\IRCAnnounceBot;
use App\Models\PrivateMessage;
use App\Models\Scopes\ApprovedScope;
use App\Models\Torrent;
use App\Models\Wish;
use App\Notifications\NewUpload;
use App\Services\Unit3dAnnounce;
use Illuminate\Support\Carbon;

class TorrentHelper
{
    public static function approveHelper($id): void
    {
        $appurl = config('app.url');
        $appname = config('app.name');

        $torrent = Torrent::with('user')->withoutGlobalScope(ApprovedScope::class)->find($id);
        $torrent->created_at = Carbon::now();
        $torrent->bumped_at = Carbon::now();
        $torrent->status = Torrent::APPROVED;
        $torrent->moderated_at = now();
        $torrent->moderated_by = auth()->id();
        $torrent->save();

        $uploader = $torrent->user;

        $wishes = Wish::where('tmdb', '=', $torrent->tmdb)->whereNull('source')->get();

        foreach ($wishes as $wish) {
            $wish->source = sprintf('%s/torrents/%s', $appurl, $torrent->id);
            $wish->save();

            // Send Private Message
            $pm = new PrivateMessage();
            $pm->sender_id = 1;
            $pm->receiver_id = $wish->user_id;
            $pm->subject = '心愿卡通知';
            $pm->message = sprintf('您心愿卡中的以下内容，%s，已上传到 %s！您可以在此处查看 [url=%s/torrents/', $wish->title, $appname, $appurl).$torrent->id.'] 点击 [/url]
                [color=red][b]这是一条系统消息，请勿回复！[/b][/color]';
            $pm->save();
        }

        if ($torrent->anon == 0) {
            foreach ($uploader->followers()->get() as $follower) {
                if ($follower->acceptsNotification($uploader, $follower, 'following', 'show_following_upload')) {
                    $follower->notify(new NewUpload('follower', $torrent));
                }
            }
        }

        $user = $torrent->user;
        $username = $user->username;
        $anon = $torrent->anon;

        if ($anon == 0) {
            // Achievements
            $user->unlock(new UserMadeUpload());
            $user->addProgress(new UserMade25Uploads(), 1);
            $user->addProgress(new UserMade50Uploads(), 1);
            $user->addProgress(new UserMade100Uploads(), 1);
            $user->addProgress(new UserMade200Uploads(), 1);
            $user->addProgress(new UserMade300Uploads(), 1);
            $user->addProgress(new UserMade400Uploads(), 1);
            $user->addProgress(new UserMade500Uploads(), 1);
            $user->addProgress(new UserMade600Uploads(), 1);
            $user->addProgress(new UserMade700Uploads(), 1);
            $user->addProgress(new UserMade800Uploads(), 1);
            $user->addProgress(new UserMade900Uploads(), 1);
        }

        // Announce To IRC
        if (config('irc-bot.enabled')) {
            $appname = config('app.name');
            $ircAnnounceBot = new IRCAnnounceBot();

            if ($anon == 0) {
                $ircAnnounceBot->message(config('irc-bot.channel'), '['.$appname.'] 用户 '.$username.' 上传了 '.$torrent->name.' 快看看吧！');
                $ircAnnounceBot->message(config('irc-bot.channel'), '[资源: '.$torrent->category->name.'] [类别: '.$torrent->type->name.'] [体积:'.$torrent->getSize().']');
            } else {
                $ircAnnounceBot->message(config('irc-bot.channel'), '['.$appname.'] 匿名用户 '.$torrent->name.' 快看看吧!');
                $ircAnnounceBot->message(config('irc-bot.channel'), '[资源: '.$torrent->category->name.'] [Type: '.$torrent->type->name.'] [体积: '.$torrent->getSize().']');
            }
            $ircAnnounceBot->message(config('irc-bot.channel'), sprintf('[链接: %s/torrents/', $appurl).$id.']');
        }

        cache()->forget('announce-torrents:by-infohash:'.$torrent->info_hash);

        Unit3dAnnounce::addTorrent($torrent);
    }
}
