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

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\PrivateMessage;
use App\Models\User;
use App\Models\Warning;
use Illuminate\Http\Request;
use Exception;
use Illuminate\Support\Carbon;

/**
 * @see \Tests\Todo\Feature\Http\Controllers\WarningControllerTest
 */
class WarningController extends Controller
{
    /**
     * Manually warn a user.
     */
    protected function store(Request $request, User $user): \Illuminate\Http\RedirectResponse
    {
        abort_unless($request->user()->group->is_modo, 403);

        Warning::create([
            'user_id'    => $user->id,
            'warned_by'  => $request->user()->id,
            'torrent'    => null,
            'reason'     => $request->string('message'),
            'expires_on' => Carbon::now()->addDays(config('hitrun.expire')),
            'active'     => '1',
        ]);

        PrivateMessage::create([
            'sender_id'   => User::SYSTEM_USER_ID,
            'receiver_id' => $user->id,
            'subject'     => '收到警告',
            'message'     => '您收到了一条警告 [b]警告[/b]. 原因： '.$request->string('message'),
        ]);

        return to_route('users.show', ['user' => $user])
            ->withSuccess('警告发布成功');
    }

    /**
     * Delete A Warning.
     *
     *
     * @throws Exception
     */
    public function destroy(Request $request, User $user, Warning $warning): \Illuminate\Http\RedirectResponse
    {
        abort_unless($request->user()->group->is_modo, 403);

        $staff = $request->user();

        PrivateMessage::create([
            'sender_id'   => $staff->id,
            'receiver_id' => $user->id,
            'subject'     => 'H&R记录删除通知',
            'message'     => $staff->username.' 删除了您的H&R记录 '.$warning->torrent.'  [color=red][b]这是一条系统消息，请勿回复![/b][/color]',
        ]);

        $warning->update([
            'deleted_by' => $staff->id,
        ]);

        $warning->delete();

        return to_route('users.show', ['user' => $user])
            ->withSuccess('警告信息已删除');
    }

    /**
     * Delete All Warnings.
     */
    public function massDestroy(Request $request, User $user): \Illuminate\Http\RedirectResponse
    {
        abort_unless($request->user()->group->is_modo, 403);

        $staff = $request->user();

        $user->warnings()->update([
            'deleted_by' => $staff->id,
        ]);

        $user->warnings()->delete();

        PrivateMessage::create([
            'sender_id'   => $staff->id,
            'receiver_id' => $user->id,
            'subject'     => 'H&R记录删除告知',
            'message'     => $staff->username.' 决定删除您所有的警告记录。您真幸运！[color=red][b]这是一条系统消息，请勿回复！[/b][/color]',
        ]);

        return to_route('users.show', ['user' => $user])
            ->withSuccess('已删除所有警告信息');
    }

    /**
     * Restore A Soft Deleted Warning.
     */
    public function update(Request $request, User $user, Warning $warning): \Illuminate\Http\RedirectResponse
    {
        abort_unless($request->user()->group->is_modo, 403);

        $warning->restore();

        return to_route('users.show', ['user' => $user])
            ->withSuccess('警告信息已重置');
    }
}
