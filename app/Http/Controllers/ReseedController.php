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

namespace App\Http\Controllers;

use App\Models\History;
use App\Models\Torrent;
use App\Models\User;
use App\Notifications\NewReseedRequest;
use App\Repositories\ChatRepository;
use Illuminate\Http\Request;

class ReseedController extends Controller
{
    /**
     * ReseedController Constructor.
     */
    public function __construct(private readonly ChatRepository $chatRepository)
    {
    }

    /**
     * Reseed Request A Torrent.
     */
    public function store(Request $request, int $id): \Illuminate\Http\RedirectResponse
    {
        // TODO: Store reseed requests so can be viewed in a table view.

        $torrent = Torrent::findOrFail($id);
        $potentialReseeds = History::where('torrent_id', '=', $torrent->id)->where('active', '=', 0)->get();

        if ($torrent->seeders <= 2) {
            // Send Notification
            foreach ($potentialReseeds as $potentialReseed) {
                User::find($potentialReseed->user_id)->notify(new NewReseedRequest($torrent));
            }

            $torrentUrl = href_torrent($torrent);

            $this->chatRepository->systemMessage(
                sprintf('Ladies and Gents, a reseed request was just placed on [url=%s]%s[/url] can you help out :question:', $torrentUrl, $torrent->name)
            );

            return to_route('torrents.show', ['id' => $torrent->id])
                ->withSuccess('已向下载此种子的所有用户以及原始上传者发送了通知');
        }

        return to_route('torrents.show', ['id' => $torrent->id])
            ->withErrors('这个种子不符合重新做种规则');
    }
}
