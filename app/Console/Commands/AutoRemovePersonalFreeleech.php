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

use App\Models\PersonalFreeleech;
use App\Models\PrivateMessage;
use App\Services\Unit3dAnnounce;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;

/**
 * @see \Tests\Unit\Console\Commands\AutoRemovePersonalFreeleechTest
 */
class AutoRemovePersonalFreeleech extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'auto:remove_personal_freeleech';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Automatically Removes A Users Personal Freeleech If It Has Expired';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        $current = Carbon::now();
        $personalFreeleech = PersonalFreeleech::where('created_at', '<', $current->copy()->subDays(1)->toDateTimeString())->get();

        foreach ($personalFreeleech as $pfl) {
            // Send Private Message
            $pm = new PrivateMessage();
            $pm->sender_id = 1;
            $pm->receiver_id = $pfl->user_id;
            $pm->subject = '免费令过期提醒';
            $pm->message = '你的 [b]免费令[/b] 过期了! 欢迎到KIMOJI商城回购 [color=red][b]这是一条系统消息，请勿回复[/b][/color]';
            $pm->save();

            // Delete The Record From DB
            $pfl->delete();

            cache()->put('personal_freeleech:'.$pfl->user_id, false);
            Unit3dAnnounce::removePersonalFreeleech($pfl->user_id);
        }

        $this->comment('免费令自动删除成功');
    }
}
