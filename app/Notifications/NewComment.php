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
use Illuminate\Notifications\Notification;

class NewComment extends Notification
{
    use Queueable;

    /**
     * NewComment Constructor.
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
                    'title' => '收到了新的种子评论',
                    'body'  => $this->comment->user->username.' 评论了'.$this->comment->commentable->name,
                    'url'   => '/torrents/'.$this->comment->commentable->id,
                ];
            }

            return [
                'title' => '收到了新的种子评论',
                'body'  => '一位匿名用户评论了 '.$this->comment->commentable->name,
                'url'   => '/torrents/'.$this->comment->commentable->id,
            ];
        }

        if ($this->type === 'torrent request') {
            if ($this->comment->anon == 0) {
                return [
                    'title' => '收到了新的求种评论',
                    'body'  => $this->comment->user->username.' 评论了 '.$this->comment->commentable->name,
                    'url'   => '/requests/'.$this->comment->commentable->id,
                ];
            }

            return [
                'title' => '收到了新的求种评论',
                'body'  => '一位匿名用户评论了 '.$this->comment->commentable->name,
                'url'   => '/requests/'.$this->comment->commentable->id,
            ];
        }

        if ($this->type === 'ticket') {
            if ($this->comment->anon == 0) {
                return [
                    'title' => '收到新的工单评论',
                    'body'  => $this->comment->user->username.' 评论了 '.$this->comment->commentable->subject,
                    'url'   => '/tickets/'.$this->comment->commentable->id,
                ];
            }

            return [
                'title' => '收到新的工单评论',
                'body'  => '一位匿名用户评论了 '.$this->comment->commentable->subject,
                'url'   => '/tickets/'.$this->comment->commentable->id,
            ];
        }

        if ($this->type === 'playlist') {
            if ($this->comment->anon == 0) {
                return [
                    'title' => '收到新的播单评论',
                    'body'  => $this->comment->user->username.' 评论了 '.$this->comment->commentable->name,
                    'url'   => '/playlists/'.$this->comment->commentable->id,
                ];
            }

            return [
                'title' => '收到新的播单评论',
                'body'  => '一位匿名用户评论了 '.$this->comment->commentable->name,
                'url'   => '/playlists/'.$this->comment->commentable->id,
            ];
        }

        if ($this->type === 'collection') {
            if ($this->comment->anon == 0) {
                return [
                    'title' => '收到新的收藏评论',
                    'body'  => $this->comment->user->username.' 评论了 '.$this->comment->commentable->name,
                    'url'   => '/mediahub/collections/'.$this->comment->commentable->id,
                ];
            }

            return [
                'title' => '收到新的收藏评论',
                'body'  => '一位匿名用户评论了 '.$this->comment->commentable->name,
                'url'   => '/mediahub/collections/'.$this->comment->commentable->id,
            ];
        }

        if ($this->comment->anon == 0) {
            return [
                'title' => '收到新的公告评论',
                'body'  => $this->comment->user->username.' 评论了 '.$this->comment->commentable->title,
                'url'   => '/articles/'.$this->comment->commentable->id,
            ];
        }

        return [
            'title' => '收到新的公告评论',
            'body'  => '一位匿名用户评论了 '.$this->comment->commentable->title,
            'url'   => '/articles/'.$this->comment->commentable->id,
        ];
    }
}
