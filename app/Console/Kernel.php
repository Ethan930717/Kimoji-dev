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

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {
        $schedule->command('auto:upsert_peers')->everyFiveSeconds();
        $schedule->command('auto:upsert_histories')->everyFiveSeconds();
        $schedule->command('auto:upsert_announces')->everyFiveSeconds();
        $schedule->command('auto:update_user_last_actions')->everyFiveSeconds();
        $schedule->command('auto:delete_stopped_peers')->everyTwoMinutes();
        $schedule->command('auto:cache_user_leech_counts')->everyThirtyMinutes();
        $schedule->command('auto:group ')->daily();
        $schedule->command('auto:nerdstat ')->hourly();
        $schedule->command('auto:reward_resurrection')->daily();
        $schedule->command('auto:highspeed_tag')->hourly();
        $schedule->command('auto:prewarning')->hourly();
        $schedule->command('auto:warning')->daily();
        $schedule->command('auto:deactivate_warning')->hourly();
        $schedule->command('auto:flush_peers')->hourly();
        $schedule->command('auto:bon_allocation')->hourly();
        $schedule->command('auto:remove_personal_freeleech')->hourly();
        $schedule->command('auto:remove_featured_torrent')->hourly();
        $schedule->command('auto:recycle_invites')->daily();
        $schedule->command('auto:recycle_activity_log')->daily();
        $schedule->command('auto:recycle_failed_logins')->daily();
        $schedule->command('auto:disable_inactive_users')->daily();
        $schedule->command('auto:softdelete_disabled_users')->daily();
        $schedule->command('auto:recycle_claimed_torrent_requests')->daily();
        $schedule->command('auto:correct_history')->daily();
        $schedule->command('auto:sync_peers')->hourly();
        $schedule->command('auto:email-blacklist-update')->weekends();
        $schedule->command('auto:reset_user_flushes')->daily();
        $schedule->command('auto:stats_clients')->daily();
        $schedule->command('auto:remove_torrent_buffs')->hourly();
        $schedule->command('auto:refund_download')->daily();
        $schedule->command('auto:torrent_balance')->hourly();
        $schedule->command('auto:check_pending_torrents')->everyThirtyMinutes();

        //$schedule->command('auto:ban_disposable_users')->weekends();
        //$schedule->command('backup:clean')->daily();
        //$schedule->command('backup:run --only-db')->daily();
    }

    /**
     * Register the Closure based commands for the application.
     */
    protected function commands(): void
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
