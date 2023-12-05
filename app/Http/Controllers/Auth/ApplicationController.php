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

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Application;
use App\Models\ApplicationImageProof;
use App\Models\ApplicationUrlProof;
use App\Rules\EmailBlacklist;
use Illuminate\Http\Request;
use App\Http\Controllers\TelegramController;
use Illuminate\Support\Facades\Log;

/**
 * @see \Tests\Todo\Feature\Http\Controllers\Staff\ApplicationControllerTest
 */
class ApplicationController extends Controller
{
    /**
     * Application Add Form.
     */
    public function create(): \Illuminate\Contracts\View\Factory|\Illuminate\View\View
    {
        return view('auth.application.create');
    }

    /**
     * Store A New Application.
     */
    public function store(Request $request): \Illuminate\Http\RedirectResponse
    {
        Log::info('开始处理新的入站申请');
        $application = resolve(Application::class);
        $application->type = $request->input('type');
        $application->email = $request->input('email');
        $application->referrer = $request->input('referrer');

        if (config('email-blacklist.enabled')) {
            if (! config('captcha.enabled')) {
                $v = validator($request->all(), [
                    'type'  => 'required',
                    'email' => [
                        'required',
                        'string',
                        'email',
                        'max:70',
                        'unique:invites',
                        'unique:users',
                        'unique:applications',
                        new EmailBlacklist(),
                    ],
                    'referrer' => 'required',
                    'images.*' => 'filled',
                    'images'   => 'min:2',
                    'links.*'  => 'filled',
                    'links'    => 'min:2',
                ]);
            } else {
                $v = validator($request->all(), [
                    'type'  => 'required',
                    'email' => [
                        'required',
                        'string',
                        'email',
                        'max:70',
                        'unique:invites',
                        'unique:users',
                        'unique:applications',
                        new EmailBlacklist(),
                    ],
                    'referrer' => 'required',
                    'images.*' => 'filled',
                    'images'   => 'min:2',
                    'links.*'  => 'filled',
                    'links'    => 'min:2',
                    'captcha'  => 'hiddencaptcha',
                ]);
            }
        } elseif (! config('captcha.enabled')) {
            $v = validator($request->all(), [
                'type'     => 'required',
                'email'    => 'required|string|email|max:70|unique:invites|unique:users|unique:applications',
                'referrer' => 'required',
                'images.*' => 'filled',
                'images'   => 'min:2',
                'links.*'  => 'filled',
                'links'    => 'min:2',
            ]);
        } else {
            $v = validator($request->all(), [
                'type'     => 'required',
                'email'    => 'required|string|email|max:70|unique:invites|unique:users|unique:applications',
                'referrer' => 'required',
                'images.*' => 'filled',
                'images'   => 'min:2',
                'links.*'  => 'filled',
                'links'    => 'min:2',
                'captcha'  => 'hiddencaptcha',
            ]);
        }

        if ($v->fails()) {
            Log::error('申请验证失败', ['errors' => $v->errors()]);
            return to_route('application.create')
                ->withErrors($v->errors());
        }

        $application->save();
        Log::info('新申请已保存', ['application_id' => $application->id]);

        // Map And Save IMG Proofs
        $applicationImageProofs = collect($request->input('images'))->map(fn ($value) => new ApplicationImageProof(['image' => $value]));
        $application->imageProofs()->saveMany($applicationImageProofs);
        // Map And Save URL Proofs
        $applicationUrlProofs = collect($request->input('links'))->map(fn ($value) => new ApplicationUrlProof(['url' => $value]));
        $application->urlProofs()->saveMany($applicationUrlProofs);
        $telegramController = new TelegramController(); // 确保这种方式可以实例化你的 TelegramController
        $telegramController->sendNewApplicationNotification("收到新的入站申请：\n申请类型：{$application->type}\n电子邮件：{$application->email}\n推荐人：{$application->referrer}");
        Log::info('Telegram 通知已发送');
        return to_route('login')
            ->withSuccess(trans('auth.application-submitted'));
    }
}
