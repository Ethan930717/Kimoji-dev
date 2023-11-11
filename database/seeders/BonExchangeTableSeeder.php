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

use App\Helpers\ByteUnits;
use App\Models\BonExchange;
use Illuminate\Database\Seeder;

class BonExchangeTableSeeder extends Seeder
{
    private $bonExchanges;

    public function __construct(private ByteUnits $byteUnits)
    {
        $this->bonExchanges = $this->getBonExchanges();
    }

    /**
     * Auto generated seed file.
     */
    public function run(): void
    {
        foreach ($this->bonExchanges as $be) {
            BonExchange::updateOrCreate($be);
        }
    }

    private function getBonExchanges(): array
    {
        return [
            [
                'id'                 => 1,
                'description'        => '10 GiB 上传',
                'value'              => $this->byteUnits->bytesFromUnit('10GiB'),
                'cost'               => 1000,
                'upload'             => 1,
                'download'           => 0,
                'personal_freeleech' => 0,
                'invite'             => 0,
            ],
            [
                'id'                 => 2,
                'description'        => '50 GiB 上传',
                'value'              => $this->byteUnits->bytesFromUnit('50GiB'),
                'cost'               => 4000,
                'upload'             => 1,
                'download'           => 0,
                'personal_freeleech' => 0,
                'invite'             => 0,
            ],
            [
                'id'                 => 3,
                'description'        => '100 GiB 上传',
                'value'              => $this->byteUnits->bytesFromUnit('100GiB'),
                'cost'               => 7000,
                'upload'             => 1,
                'download'           => 0,
                'personal_freeleech' => 0,
                'invite'             => 0,
            ],
            [
                'id'                 => 4,
                'description'        => '1 免费令',
                'value'              => 1,
                'cost'               => 7500,
                'upload'             => 0,
                'download'           => 0,
                'personal_freeleech' => 1,
                'invite'             => 0,
            ],
            [
                'id'                 => 9,
                'description'        => '1 邀请名额',
                'value'              => 1,
                'cost'               => 9999999,
                'upload'             => 0,
                'download'           => 0,
                'personal_freeleech' => 0,
                'invite'             => 1,
            ],
        ];
    }
}
