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

use App\Models\Ticket;
use App\Models\TicketAttachment;
use App\Models\User;
use Livewire\Component;
use Livewire\WithFileUploads;

class AttachmentUpload extends Component
{
    use WithFileUploads;

    public ?User $user = null;

    public ?int $ticket = null;

    public $attachment;

    public $storedImage;

    final public function mount(int $id): void
    {
        $this->user = auth()->user();
        $this->ticket = $id;
    }

    final public function upload(): void
    {
        $this->validate([
            'attachment' => 'image|max:1024', // 1MB Max
        ]);

        $fileName = uniqid('', true).'.'.$this->attachment->getClientOriginalExtension();

        $this->attachment->storeAs('attachments', $fileName, 'attachments');

        $attachment = new TicketAttachment();
        $attachment->user_id = $this->user->id;
        $attachment->ticket_id = $this->ticket;
        $attachment->file_name = $fileName;
        $attachment->file_size = $this->attachment->getSize();
        $attachment->file_extension = $this->attachment->getMimeType();
        $attachment->save();

        $this->dispatchBrowserEvent('success', ['type' => 'success',  'message' => '附件图片上传成功!']);
    }

    final public function getAttachmentsProperty(): \Illuminate\Database\Eloquent\Collection
    {
        return Ticket::find($this->ticket)->attachments;
    }

    final public function render(): \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Contracts\Foundation\Application
    {
        return view('livewire.attachment-upload', ['attachments' => $this->attachments]);
    }
}
