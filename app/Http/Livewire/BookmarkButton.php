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

namespace App\Http\Livewire;

use App\Models\Torrent;
use App\Models\User;
use Livewire\Component;

class BookmarkButton extends Component
{
    public Torrent $torrent;
    public User $user;
    public bool $isBookmarked;

    final public function store(): void
    {
        if ($this->user->bookmarks()->where('torrent_id', '=', $this->torrent->id)->exists()) {
            $this->dispatchBrowserEvent('error', ['type' => 'error',  'message' => '当前资源已在收藏列表中']);

            return;
        }

        $this->user->bookmarks()->attach($this->torrent->id);
        $this->isBookmarked = true;
        $this->dispatchBrowserEvent('success', ['type' => 'success',  'message' => '收藏成功']);
    }

    final public function destroy(): void
    {
        $this->user->bookmarks()->detach($this->torrent->id);
        $this->isBookmarked = false;
        $this->dispatchBrowserEvent('success', ['type' => 'success',  'message' => '取消收藏成功']);
    }

    final public function render(): \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Contracts\Foundation\Application
    {
        return view('livewire.bookmark-button');
    }
}
