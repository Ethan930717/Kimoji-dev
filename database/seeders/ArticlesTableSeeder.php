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

use App\Models\Article;
use Illuminate\Database\Seeder;

class ArticlesTableSeeder extends Seeder
{
    public function run(): void
    {
        Article::upsert([
            [
                'id'         => 1,
                'title'      => '亲爱的旅人，欢迎来到'.config('other.title').' .',
                'content'    => '我们的乐园还在萌芽阶段，迫切需要一切形式的援助，如果你有网页开发，尤其是laravel架构开发的经验，或者你对发种、宣传等工种有浓厚的兴趣，亦或者你有足够的硬盘空间可以协助我们保种，请随时与我们联系！在导航栏的其他中，有我们的TG频道，欢迎你的加入！',
                'user_id'    => 3,
                'created_at' => '2023-11-11 17:22:37',
                'updated_at' => '2023-11-11 12:21:06',
            ],
        ], ['id']);
    }
}
