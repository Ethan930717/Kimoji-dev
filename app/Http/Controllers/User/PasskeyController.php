<?php
/**
 * NOTICE OF LICENSE.
 *
 * UNIT3D Community Edition is open-sourced software licensed under the GNU Affero General Public License v3.0
 * The details is bundled with this project in the file LICENSE.txt.
 *
 * @project    UNIT3D Community Edition
 *
 * @author     Roardom <roardom@protonmail.com>
 * @license    https://www.gnu.org/licenses/agpl-3.0.en.html/ GNU Affero General Public License v3.0
 */

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\PrivateMessage;
use App\Models\User;
use App\Services\Unit3dAnnounce;
use Illuminate\Http\Request;

class PasskeyController extends Controller
{
    /**
     * Update user passkey.
     */
    protected function update(Request $request, User $user): \Illuminate\Http\RedirectResponse
    {
        abort_unless($request->user()->is($user) || $request->user()->group->is_modo, 403);

        $changedByStaff = $request->user()->isNot($user);

        abort_if($changedByStaff && ! $request->user()->group->is_owner && $request->user()->group->level <= $user->group->level, 403);

        $user->update([
            'passkey' => md5(random_bytes(60).$user->password)
        ]);

        if ($changedByStaff) {
            PrivateMessage::create([
                'sender_id'   => 1,
                'receiver_id' => $user->id,
                'subject'     => '请注意，PASSKEY已重置',
                'message'     => "您的PASSKEY已被工作人员重置。您需要在所有的客户端中更新您的PASSKEY，以继续做种。\n\n如需更多信息，请提交工单求助。\n\n[color=red][b]这是一条系统消息，请勿回复！[/b][/color]",
            ]);
        }

        cache()->forget('user:'.$user->passkey);

        Unit3dAnnounce::removeUser($user);
        Unit3dAnnounce::addUser($user);

        return to_route('users.passkey.edit', ['user' => $user])
            ->withSuccess('Passkey更新成功');
    }

    /**
     * Edit user passkey.
     */
    public function edit(Request $request, User $user): \Illuminate\Contracts\View\Factory|\Illuminate\View\View
    {
        abort_unless($request->user()->is($user) || $request->user()->group->is_modo, 403);

        return view('user.passkey.edit', ['user' => $user]);
    }
}
