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

namespace App\Http\Controllers\Staff;

use App\Helpers\TorrentHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\Staff\UpdateModerationRequest;
use App\Models\PrivateMessage;
use App\Models\Scopes\ApprovedScope;
use App\Models\Torrent;
use App\Repositories\ChatRepository;
use App\Services\Unit3dAnnounce;
use App\Http\Controllers\TelegramController;

/**
 * @see \Tests\Todo\Feature\Http\Controllers\Staff\ModerationControllerTest
 */
class ModerationController extends Controller
{
    /**
     * ModerationController Constructor.
     */
    public function __construct(private readonly ChatRepository $chatRepository)
    {
    }

    /**
     * Torrent Moderation Panel.
     */
    public function index(): \Illuminate\Contracts\View\Factory|\Illuminate\View\View
    {
        return view('Staff.moderation.index', [
            'current' => now(),
            'pending' => Torrent::withoutGlobalScope(ApprovedScope::class)
                ->with(['user.group', 'category', 'type', 'resolution', 'category'])
                ->where('status', '=', Torrent::PENDING)
                ->get(),
            'postponed' => Torrent::withoutGlobalScope(ApprovedScope::class)
                ->with(['user.group', 'moderated.group', 'category', 'type', 'resolution', 'category'])
                ->where('status', '=', Torrent::POSTPONED)
                ->get(),
            'rejected' => Torrent::withoutGlobalScope(ApprovedScope::class)
                ->with(['user.group', 'moderated.group', 'category', 'type', 'resolution', 'category'])
                ->where('status', '=', Torrent::REJECTED)
                ->get(),
        ]);
    }

    /**
     * Update a torrent's moderation status.
     */
    public function update(UpdateModerationRequest $request, int $id): \Illuminate\Http\RedirectResponse
    {
        $torrent = Torrent::withoutGlobalScope(ApprovedScope::class)->with('user')->findOrFail($id);


        if ($request->integer('old_status') !== $torrent->status) {
            return to_route('torrents.show', ['id' => $id])
                ->withInput()
                ->withErrors('该种子已经通过审核');
        }

        if ($request->integer('status') === $torrent->status) {
            return to_route('torrents.show', ['id' => $id])
                ->withInput()
                ->withErrors(
                    match ($torrent->status) {
                        Torrent::PENDING   => '种子已处于待审核状态',
                        Torrent::APPROVED  => '种子已被批准',
                        Torrent::REJECTED  => '种子已被拒绝',
                        Torrent::POSTPONED => '种子已被延期',
                        default            => '无效的审核状态'
                    }
                );
        }

        $staff = auth()->user();

        switch ($request->status) {
            case Torrent::APPROVED:
                // Announce To Shoutbox
                if ($torrent->anon === 0) {
                    $this->chatRepository->systemMessage(
                        sprintf(
                            '[url=%s/users/%s]%s[/url] 上传了 %s. [url=%s/torrents/%s]%s[/url], 快看看吧！',
                            config('app.url'),
                            $torrent->user->username,
                            $torrent->user->username,
                            $torrent->category->name,
                            config('app.url'),
                            $torrent->id,
                            $torrent->name
                        )
                    );
                } else {
                    $this->chatRepository->systemMessage(
                        sprintf(
                            '一位匿名用户上传了 %s. [url=%s/torrents/%s]%s[/url], 快看看吧！',
                            $torrent->category->name,
                            config('app.url'),
                            $torrent->id,
                            $torrent->name
                        )
                    );
                }

                TorrentHelper::approveHelper($id);

                return to_route('staff.moderation.index')
                    ->withSuccess('审核通过！');

            case Torrent::REJECTED:
                $torrent->update([
                    'status'       => Torrent::REJECTED,
                    'moderated_at' => now(),
                    'moderated_by' => $staff->id,
                ]);

                PrivateMessage::create([
                    'sender_id'   => $staff->id,
                    'receiver_id' => $torrent->user_id,
                    'subject' => "您上传的 " . $torrent->name . " 已被拒绝" ,
                    'message' => "拒绝原因如下，请尽快更新您的种子信息后回复本邮件。\n\n" . $request->message . "[\n\n点击跳转：url=" . route('torrents.show', ['id' => $torrent->id]) . "]" . $torrent->name . "[/url]",
                ]);

                cache()->forget('announce-torrents:by-infohash:'.$torrent->info_hash);

                Unit3dAnnounce::addTorrent($torrent);

                return to_route('staff.moderation.index')
                    ->withSuccess('种子已拒绝');

            case Torrent::POSTPONED:
                $torrent->update([
                    'status'       => Torrent::POSTPONED,
                    'moderated_at' => now(),
                    'moderated_by' => $staff->id,
                ]);

                PrivateMessage::create([
                    'sender_id'   => $staff->id,
                    'receiver_id' => $torrent->user_id,
                    'subject' => "您上传的 " . $torrent->name . " 已被延期处理" ,
                    'message' => "延期原因如下，请尽快更新您的种子信息后回复本邮件。\n\n" . $request->message . "[\n\n点击跳转：url=" . route('torrents.show', ['id' => $torrent->id]) . "]" . $torrent->name . "[/url]",
                ]);

                cache()->forget('announce-torrents:by-infohash:'.$torrent->info_hash);

                Unit3dAnnounce::addTorrent($torrent);

                return to_route('staff.moderation.index')
                    ->withSuccess('已设置延期处理');


            default: // Undefined status
                return to_route('torrents.show', ['id' => $id])
                    ->withErrors('无效的审核状态');
        }
    }
}
