<?php

namespace App\Actions\Fortify;

use App\Models\Group;
use App\Models\Invite;
use App\Models\PrivateMessage;
use App\Models\User;
use App\Repositories\ChatRepository;
use App\Rules\EmailBlacklist;
use Exception;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Laravel\Fortify\Contracts\CreatesNewUsers;

class CreateNewUser implements CreatesNewUsers
{
    use PasswordValidationRules;

    public function __construct(private readonly ChatRepository $chatRepository)
    {
    }

    /**
     * Validate and create a newly registered user.
     *
     * @param  array<string, string> $input
     * @throws ValidationException
     * @throws Exception
     */
    public function create(array $input): RedirectResponse | User
    {
        Validator::make($input, [
            'username' => 'required|alpha_dash|string|between:3,25|unique:users',
            'password' => [
                'required',
                'confirmed',
                $this->passwordRules(),
            ],
            'email' => [
                'required',
                'string',
                'email',
                'max:70',
                'unique:users',
                Rule::when(config('email-blacklist.enabled') === true, fn () => new EmailBlacklist()),
            ],
            'captcha' => [
                Rule::excludeIf(config('captcha.enabled') === false),
                Rule::when(config('captcha.enabled') === true, 'hiddencaptcha'),
            ],
            'code' => 'required',
        ])->validate();

        // Make sure open reg is off and invite code exists and has not been used already
        $invite = Invite::query()->where('code', '=', $input['code'])->first();

        if (config('other.invite-only') === true && ($invite === null || $invite->accepted_by !== null)) {
            throw new HttpResponseException(
                to_route('register', ['code' => $input['code']])
                    ->withErrors(trans('auth.invalid-key'))
            );
        }

        $validatingGroup = cache()->rememberForever('validating_group', fn () => Group::query()->where('slug', '=', 'validating')->pluck('id'));

        $user = User::create([
            'username'   => $input['username'],
            'email'      => $input['email'],
            'password'   => Hash::make($input['password']),
            'passkey'    => md5(random_bytes(60)),
            'rsskey'     => md5(random_bytes(60)),
            'uploaded'   => config('other.default_upload'),
            'downloaded' => config('other.default_download'),
            'style'      => config('other.default_style', 0),
            'locale'     => config('app.locale'),
            'group_id'   => $validatingGroup[0],
        ]);

        $user->passkeys()->create(['content' => $user->passkey]);

        $user->rsskeys()->create(['content' => $user->rsskey]);

        if ($invite !== null) {
            $invite->update([
                'accepted_by' => $user->id,
                'accepted_at' => new Carbon(),
            ]);
        }

        // Select A Random Welcome Message
        $profileUrl = href_profile($user);

        $welcomeArray = [
            sprintf('[url=%s]欢迎你，%s[/url]！来到%s，希望你能喜欢KIMOJI的大家庭！ :rocket:', $profileUrl, $user->username, config('other.title')),
            sprintf("[url=%s]哎呀，%s[/url]，我们一直在等你呢！ :space_invader:", $profileUrl, $user->username),
            sprintf("[url=%s]%s[/url] 来啦。 :cry:", $profileUrl, $user->username),
            sprintf("是小鸟！是飞机！咦，其实是[url=%s]%s[/url]来啦。", $profileUrl, $user->username),
            sprintf('准备好了吗，[url=%s]%s[/url]。', $profileUrl, $user->username),
            sprintf('野生的[url=%s]%s[/url]出现了。', $profileUrl, $user->username),
            sprintf('欢迎来到%s[url=%s]%s[/url]。我们已经等你很久了 ( ͡° ͜ʖ ͡°)', config('other.title'), $profileUrl, $user->username),
        ];

        $this->chatRepository->systemMessage(
            $welcomeArray[array_rand($welcomeArray)]
        );

        // Send Welcome PM
        PrivateMessage::create([
            'sender_id'   => 1,
            'receiver_id' => $user->id,
            'subject'     => config('welcomepm.subject'),
            'message'     => config('welcomepm.message'),
        ]);

        return $user;
    }
}
