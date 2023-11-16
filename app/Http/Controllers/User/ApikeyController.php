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
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ApikeyController extends Controller
{
    /**
     * Update user apikey.
     */
    protected function update(Request $request, User $user): \Illuminate\Http\RedirectResponse
    {
        abort_unless($request->user()->is($user) || $request->user()->group->is_modo, 403);

        $changedByStaff = $request->user()->isNot($user);

        abort_if($changedByStaff && ! $request->user()->group->is_owner && $request->user()->group->level <= $user->group->level, 403);

        $user->update([
            'api_token' => Str::random(100),
        ]);

        if ($changedByStaff) {
            PrivateMessage::create([
                'sender_id'   => 1,
                'receiver_id' => $user->id,
                'subject'     => '请注意：API key已重置',
                'message'     => "您的 API 密钥已被工作人员重置。您需要在所有脚本中更新您的 API 密钥才能继续使用 API\n\n如需更多信息，请提交工单求助。\n\n[color=red][b]这是一条系统消息，请勿回复![/b][/color]",
            ]);
        }

        return to_route('users.apikey.edit', ['user' => $user])
            ->withSuccess('API key更新成功');
    }

    /**
     * Edit user apikey.
     */
    public function edit(Request $request, User $user): \Illuminate\Contracts\View\Factory|\Illuminate\View\View
    {
        abort_unless($request->user()->is($user) || $request->user()->group->is_modo, 403);

        return view('user.apikey.edit', ['user' => $user]);
    }
}
