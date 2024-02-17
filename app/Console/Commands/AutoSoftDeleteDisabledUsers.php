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

namespace App\Console\Commands;

use App\Enums\UserGroup;
use App\Jobs\SendDeleteUserMail;
use App\Models\Comment;
use App\Models\FailedLoginAttempt;
use App\Models\FreeleechToken;
use App\Models\Group;
use App\Models\Ban;
use App\Models\History;
use App\Models\Like;
use App\Models\Message;
use App\Models\Peer;
use App\Models\Post;
use App\Models\PrivateMessage;
use App\Models\Scopes\ApprovedScope;
use App\Models\Thank;
use App\Models\Topic;
use App\Models\Torrent;
use App\Models\User;
use App\Services\Unit3dAnnounce;
use Illuminate\Console\Command;
use Exception;

/**
 * @see \Tests\Unit\Console\Commands\AutoSoftDeleteDisabledUsersTest
 */
class AutoSoftDeleteDisabledUsers extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'auto:softdelete_disabled_users';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'User account must be In disabled group for at least x days';

    /**
     * Execute the console command.
     *
     * @throws Exception
     */
    public function handle(): void
    {
        if (config('pruning.user_pruning')) {
            $disabledGroup = cache()->rememberForever('disabled_group', function () {
                return Group::where('slug', '=', 'disabled')->first()->id; // 直接获取id
            });

            $users = User::where('group_id', '=', $disabledGroup)
                ->where('disabled_at', '<', now()->copy()->subDays(config('pruning.soft_delete'))->toDateTimeString())
                ->get();

            foreach ($users as $user) {
                $user->update([
                    'group_id'     => UserGroup::BANNED->value,
                ]);

                Ban::create([
                    'owned_by' => $user->id,
                    'created_by' => 1,
                    'ban_reason' => "注册之日起30天内未达到100GB官方音乐资源保种要求",
                ]);
            }
        }

        $this->comment('Automated Soft Delete Disabled Users Command Complete');
    }
}
