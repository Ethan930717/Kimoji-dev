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

namespace App\Notifications;

use App\Models\Comment;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;

class NewCommentTag extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * NewCommentTag Constructor.
     */
    public function __construct(public string $type, public Comment $comment)
    {
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database'];
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        if ($this->type === 'torrent') {
            if ($this->comment->anon == 0) {
                return [
                    'title' => $this->comment->user->username.' 标记了你',
                    'body'  => $this->comment->user->username.' 在种子 '.$this->comment->commentable->name.' 的评论中标记了你',
                    'url'   => '/torrents/'.$this->comment->commentable->id,
                ];
            }

            return [
                'title' => '你被标记了',
                'body'  => '一位匿名用户在种子 '.$this->comment->commentable->name.' 的评论中标记了你',
                'url'   => '/torrents/'.$this->comment->commentable->id,
            ];
        }

        if ($this->type === 'torrent request') {
            if ($this->comment->anon == 0) {
                return [
                    'title' => $this->comment->user->username.' 标记了你',
                    'body'  => $this->comment->user->username.' 在求种信息 '.$this->comment->commentable->name.' 的评论中标记了你',
                    'url'   => '/requests/'.$this->comment->commentable->id,
                ];
            }

            return [
                'title' => '你被标记了',
                'body'  => '一位匿名用户在种子请求 '.$this->comment->commentable->name.' 的评论中标记了你',
                'url'   => '/requests/'.$this->comment->commentable->id,
            ];
        }

        if ($this->type === 'ticket') {
            if ($this->comment->anon == 0) {
                return [
                    'title' => $this->comment->user->username.' 标记了你',
                    'body'  => $this->comment->user->username.' 在工单 '.$this->comment->commentable->subject.' 的评论中标记了你',
                    'url'   => '/tickets/'.$this->comment->commentable->id,
                ];
            }

            return [
                'title' => '你被标记了',
                'body'  => '一位匿名用户在工单 '.$this->comment->commentable->subject.' 的评论中标记了你',
                'url'   => '/tickets/'.$this->comment->commentable->id,
            ];
        }

        if ($this->type === 'playlist') {
            if ($this->comment->anon == 0) {
                return [
                    'title' => $this->comment->user->username.' 标记了你',
                    'body'  => $this->comment->user->username.' 在播单 '.$this->comment->commentable->name.' 的评论中标记了你',
                    'url'   => '/playlists/'.$this->comment->commentable->id,
                ];
            }

            return [
                'title' => '你被标记了',
                'body'  => '一位匿名用户在集合 '.$this->comment->commentable->name.' 的评论中标记了你',
                'url'   => '/playlists/'.$this->comment->commentable->id,
            ];
        }

        if ($this->type === 'collection') {
            if ($this->comment->anon == 0) {
                return [
                    'title' => $this->comment->user->username.' 标记了你',
                    'body'  => $this->comment->user->username.' 在收藏列表 '.$this->comment->commentable->name.' 的评论中标记了你',
                    'url'   => '/mediahub/collections/'.$this->comment->commentable->id,
                ];
            }

            return [
                'title' => '你被标记了',
                'body'  => '一位匿名用户在收藏列表 '.$this->comment->commentable->name.' 的评论中标记了你',
                'url'   => '/mediahub/collections/'.$this->comment->commentable->id,
            ];
        }

        if ($this->comment->anon == 0) {
            return [
                'title' => $this->comment->user->username.' 标记了你',
                'body'  => $this->comment->user->username.' 在公告 '.$this->comment->commentable->title.' 的评论中标记了你',
                'url'   => '/articles/'.$this->comment->commentable->id,
            ];
        }

        return [
            'title' => '你被标记了',
            'body'  => '一位匿名用户在公告 '.$this->comment->commentable->title.' 的评论中标记了你',
            'url'   => '/articles/'.$this->comment->commentable->id,
        ];
    }
}
