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

class TicketCategoriesTableSeeder extends Seeder
{
    private array $categories;

    public function __construct()
    {
        $this->categories = $this->getTicketCategories();
    }

    /**
     * Auto generated seed file.
     */
    final public function run(): void
    {
        foreach ($this->categories as $category) {
            TicketCategory::updateOrCreate($category);
        }
    }

    /**
     * @return array[]
     */
    private function getTicketCategories(): array
    {
        return [
            [
                'name'     => '账号',
                'position' => 0,
            ],
            [
                'name'     => '申诉',
                'position' => 1,
            ],
            [
                'name'     => '论坛',
                'position' => 2,
            ],
            [
                'name'     => '求种',
                'position' => 3,
            ],
            [
                'name'     => '字幕',
                'position' => 4,
            ],
            [
                'name'     => '种子',
                'position' => 5,
            ],
            [
                'name'     => '影视库',
                'position' => 6,
            ],
            [
                'name'     => '技术相关',
                'position' => 7,
            ],
            [
                'name'     => '播单',
                'position' => 8,
            ],
            [
                'name'     => '上报BUG',
                'position' => 9,
            ],
            [
                'name'     => '其他',
                'position' => 10,
            ],
        ];
    }
}
