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

use App\Models\Resolution;
use Illuminate\Database\Seeder;

class ResolutionsTableSeeder extends Seeder
{
    private $resolutions;

    public function __construct()
    {
        $this->resolutions = $this->getResolutions();
    }

    /**
     * Auto generated seed file.
     */
    public function run(): void
    {
        foreach ($this->resolutions as $resolution) {
            Resolution::updateOrCreate($resolution);
        }
    }

    private function getResolutions(): array
    {
        return [
            [
                'id'       => 1,
                'name'     => '8K｜4320p',
                'position' => 0,
            ],
            [
                'id'       => 2,
                'name'     => '4K｜2160p',
                'position' => 1,
            ],
            [
                'id'       => 3,
                'name'     => '1080p',
                'position' => 2,
            ],
            [
                'id'       => 4,
                'name'     => '1080i',
                'position' => 4,
            ],
            [
                'id'       => 5,
                'name'     => '720p',
                'position' => 5,
            ],
            [
                'id'       => 6,
                'name'     => 'Other',
                'position' => 6,
            ],
        ];
    }
}
