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

use App\Models\Page;
use Illuminate\Database\Seeder;

class PagesTableSeeder extends Seeder
{
    private $pages;

    public function __construct()
    {
        $this->pages = $this->getPages();
    }

    /**
     * Auto generated seed file.
     */
    public function run(): void
    {
        foreach ($this->pages as $page) {
            if (Page::find($page['id']) == null) {
                Page::create($page);
            } else {
                Page::find($page['id'])->update($page);
            }
        }
    }

    private function getPages(): array
    {
        return [
            [
                'id'      => 1,
                'name'    => '规则',
                'content' => '规则列表还在建设中',
            ],
            [
                'id'      => 2,
                'name'    => '常见问题',
                'content' => 'FAQ列表还在建设中',
            ],
        ];
    }
}
