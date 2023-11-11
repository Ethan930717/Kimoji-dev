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

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategoriesTableSeeder extends Seeder
{
    private $categories;

    public function __construct()
    {
        $this->categories = $this->getCategories();
    }

    /**
     * Auto generated seed file.
     */
    public function run(): void
    {
        foreach ($this->categories as $category) {
            Category::updateOrCreate($category);
        }
    }

    private function getCategories(): array
    {
        return [
            [
                'id'          => 1,
                'name'        => '电影',
                'position'    => 0,
                'icon'        => config('other.font-awesome').' fa-film',
                'num_torrent' => 0,
                'image'       => null,
                'movie_meta'  => 1,
                'tv_meta'     => 0,
                'game_meta'   => 0,
                'music_meta'  => 0,
                'no_meta'     => 0,
            ],
            [
                'id'          => 2,
                'name'        => '剧集',
                'position'    => 1,
                'icon'        => config('other.font-awesome').' fa-tv-retro',
                'num_torrent' => 0,
                'image'       => null,
                'movie_meta'  => 0,
                'tv_meta'     => 1,
                'game_meta'   => 0,
                'music_meta'  => 0,
                'no_meta'     => 0,
            ],
            [
                'id'          => 3,
                'name'        => '动漫｜剧场',
                'position'    => 2,
                'icon'        => config('other.font-awesome').' fa-palette',
                'num_torrent' => 0,
                'image'       => null,
                'movie_meta'  => 1,
                'tv_meta'     => 0,
                'game_meta'   => 0,
                'music_meta'  => 0,
                'no_meta'     => 0,
            ],
            [
                'id'          => 4,
                'name'        => '动漫｜番剧',
                'position'    => 3,
                'icon'        => config('other.font-awesome').' fa-palette',
                'num_torrent' => 0,
                'image'       => null,
                'movie_meta'  => 0,
                'tv_meta'     => 1,
                'game_meta'   => 0,
                'music_meta'  => 0,
                'no_meta'     => 0,
            ],
            [
                'id'          => 5,
                'name'        => '综艺',
                'position'    => 4,
                'icon'        => config('other.font-awesome').' fa-gamepad',
                'num_torrent' => 0,
                'image'       => null,
                'movie_meta'  => 0,
                'tv_meta'     => 1,
                'game_meta'   => 0,
                'music_meta'  => 0,
                'no_meta'     => 0,
            ],
            [
                'id'          => 6,
                'name'        => '纪录片',
                'position'    => 5,
                'icon'        => config('other.font-awesome').' fa-book',
                'num_torrent' => 0,
                'image'       => null,
                'movie_meta'  => 0,
                'tv_meta'     => 1,
                'game_meta'   => 0,
                'music_meta'  => 0,
                'no_meta'     => 0,
            ],
            [
                'id'          => 7,
                'name'        => '音乐',
                'position'    => 6,
                'icon'        => config('other.font-awesome').' fa-music',
                'num_torrent' => 0,
                'image'       => null,
                'movie_meta'  => 0,
                'tv_meta'     => 0,
                'game_meta'   => 0,
                'music_meta'  => 1,
                'no_meta'     => 0,
            ],
            [
                'id'          => 8,
                'name'        => '体育',
                'position'    => 7,
                'icon'        => config('other.font-awesome').' fa-person-running',
                'num_torrent' => 0,
                'image'       => null,
                'movie_meta'  => 0,
                'tv_meta'     => 1,
                'game_meta'   => 0,
                'music_meta'  => 0,
                'no_meta'     => 0,
            ],
        ];
    }
}
