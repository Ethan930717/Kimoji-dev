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

namespace Database\Seeders;

use App\Models\TicketCategory;
use Illuminate\Database\Seeder;
use const _PHPStan_39fe102d2\__;

class TicketCategoriesTableSeeder extends Seeder
{
    final public function run(): void
    {
        TicketCategory::upsert([
            [
                'name'     => __('ticket.Accounts'),
                'position' => 0,
            ],
            [
                'name'     => __('ticket.Appeals'),
                'position' => 1,
            ],
            [
                'name'     => __('ticket.Forums'),
                'position' => 2,
            ],
            [
                'name'     => __('ticket.Requests'),
                'position' => 3,
            ],
            [
                'name'     => __('ticket.Subtitles'),
                'position' => 4,
            ],
            [
                'name'     => __('ticket.Torrents'),
                'position' => 5,
            ],
            [
                'name'     => __('ticket.MediaHub'),
                'position' => 6,
            ],
            [
                'name'     => __('ticket.Technical'),
                'position' => 7,
            ],
            [
                'name'     => __('ticket.Playlists'),
                'position' => 8,
            ],
            [
                'name'     => __('ticket.Bugs'),
                'position' => 9,
            ],
            [
                'name'     => __('ticket.Other'),
                'position' => 10,
            ],
        ], ['id']);
    }
}
