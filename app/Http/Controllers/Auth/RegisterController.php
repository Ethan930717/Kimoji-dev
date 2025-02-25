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
use App\Jobs\SendActivationMail;
use App\Models\Group;
use App\Models\Invite;
use App\Models\PrivateMessage;
use App\Models\User;
use App\Models\UserActivation;
use App\Models\UserNotification;
use App\Models\UserPrivacy;
use App\Repositories\ChatRepository;
use App\Rules\EmailBlacklist;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules\Password;

class RegisterController extends Controller
{
    /**
     * RegisterController Constructor.
     */
    public function __construct(private readonly ChatRepository $chatRepository)
    {
    }

    /**
     * Registration Form.
     */
    public function registrationForm($code = null): \Illuminate\Contracts\View\Factory|\Illuminate\View\View|\Illuminate\Http\RedirectResponse
    {
        // Make sure open reg is off, invite code is not present and application signups enabled
        if ($code === 'null' && config('other.invite-only') == 1 && config('other.application_signups')) {
            return to_route('application.create')
                ->withInfo(trans('auth.allow-invite-appl'));
        }

        // Make sure open reg is off and invite code is not present
        if ($code === 'null' && config('other.invite-only') == 1) {
            return to_route('login')
                ->withWarning(trans('auth.allow-invite'));
        }

        return view('auth.register', ['code' => $code]);
    }

    public function register(Request $request, $code = null): \Illuminate\Http\RedirectResponse
    {
        // Make sure open reg is off and invite code exist and has not been used already
        $key = Invite::where('code', '=', $code)->first();

        if (config('other.invite-only') == 1 && (!$key || $key->accepted_by !== null)) {
            return to_route('registrationForm', ['code' => $code])
                ->withErrors(trans('auth.invalid-key'));
        }

        $validatingGroup = cache()->rememberForever('validating_group', fn () => Group::where('slug', '=', 'validating')->pluck('id'));

        $user = new User();
        $user->username = $request->input('username');
        $user->email = $request->input('email');
        $user->password = Hash::make($request->input('password'));
        $user->passkey = md5(random_bytes(60).$user->password);
        $user->rsskey = md5(random_bytes(60).$user->password);
        $user->uploaded = config('other.default_upload');
        $user->downloaded = config('other.default_download');
        $user->style = config('other.default_style', 0);
        $user->locale = 'zh-CN';
        $user->group_id = $validatingGroup[0];

        if (config('email-blacklist.enabled')) {
            if (!config('captcha.enabled')) {
                $v = validator($request->all(), [
                    'username' => ['required', 'string', 'between:3,25', 'unique:users', 'regex:/^[\p{L}\p{N}_-]+$/u'],
                    'password' => [
                        'required',
                        Password::min(8)->mixedCase()->letters()->numbers()->uncompromised(),
                    ],
                    'email' => [
                        'required',
                        'string',
                        'email',
                        'max:70',
                        'unique:users',
                        new EmailBlacklist(),
                    ],
                ]);
            } else {
                $v = validator($request->all(), [
                    'username' => ['required', 'string', 'between:3,25', 'unique:users', 'regex:/^[\p{L}\p{N}_-]+$/u'],
                    'password' => [
                        'required',
                        Password::min(8)->mixedCase()->letters()->numbers()->uncompromised(),
                    ],
                    'email' => [
                        'required',
                        'string',
                        'email',
                        'max:70',
                        'unique:users',
                        new EmailBlacklist(),
                    ],
                    'captcha' => 'hiddencaptcha',
                ]);
            }
        } elseif (!config('captcha.enabled')) {
            $v = validator($request->all(), [
                'username' => ['required', 'string', 'between:3,25', 'unique:users', 'regex:/^[\p{L}\p{N}_-]+$/u'],
                'password' => [
                    'required',
                    Password::min(8)->mixedCase()->letters()->numbers()->uncompromised(),
                ],
                'email' => 'required|string|email|max:70|unique:users',
            ]);
        } else {
            $v = validator($request->all(), [
                'username' => ['required', 'string', 'between:3,25', 'unique:users', 'regex:/^[\p{L}\p{N}_-]+$/u'],
                'password' => [
                    'required',
                    Password::min(8)->mixedCase()->letters()->numbers()->uncompromised(),
                ],
                'email'   => 'required|string|email|max:70|unique:users',
                'captcha' => 'hiddencaptcha',
            ]);
        }

        if ($v->fails()) {
            return to_route('registrationForm', ['code' => $code])
                ->withErrors($v->errors());
        }

        $user->save();
        $userPrivacy = new UserPrivacy();
        $userPrivacy->setDefaultValues();
        $userPrivacy->user_id = $user->id;
        $userPrivacy->save();
        $userNotification = new UserNotification();
        $userNotification->setDefaultValues();
        $userNotification->user_id = $user->id;
        $userNotification->save();

        if ($key) {
            // Update The Invite Record
            $key->accepted_by = $user->id;
            $key->accepted_at = new Carbon();
            $key->save();
        }

        // Handle The Activation System
        $token = hash_hmac('sha256', $user->username.$user->email.Str::random(16), (string) config('app.key'));
        $userActivation = new UserActivation();
        $userActivation->user_id = $user->id;
        $userActivation->token = $token;
        $userActivation->save();
        dispatch(new SendActivationMail($user, $token));

        // Select A Random Welcome Message
        $profileUrl = href_profile($user);
        $welcomeArray = [
            sprintf('[url=%s]%s[/url], 欢迎来到 ', $profileUrl, $user->username).config('other.title').'!  :rocket:',
            sprintf("[url=%s]%s[/url], 等你好久啦！ :space_invader:", $profileUrl, $user->username),
            sprintf("[url=%s]%s[/url] 离开啦！ :cry:", $profileUrl, $user->username),
            sprintf("有人来了，瞅瞅是谁～呀，是 [url=%s]%s[/url]！", $profileUrl, $user->username),
            sprintf('大佬 [url=%s]%s[/url] 登场喽！', $profileUrl, $user->username),
            sprintf('疯狗 [url=%s]%s[/url] 出现啦！', $profileUrl, $user->username),
            '欢迎来到 '.config('other.title').sprintf(' [url=%s]%s[/url]. 我们等你好久啦！', $profileUrl, $user->username),
        ];
        $selected = random_int(0, \count($welcomeArray) - 1);
        $this->chatRepository->systemMessage(
            $welcomeArray[$selected]
        );

        // Send Welcome PM
        $privateMessage = new PrivateMessage();
        $privateMessage->sender_id = 1;
        $privateMessage->receiver_id = $user->id;
        $privateMessage->subject = config('welcomepm.subject');
        $privateMessage->message = config('welcomepm.message');
        $privateMessage->save();

        return to_route('login')
            ->withSuccess(trans('auth.register-thanks'));
    }
}
