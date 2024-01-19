<?php
/**
 * NOTICE OF LICENSE.
 *
 * UNIT3D Community Edition is open-sourced software licensed under the GNU Affero General Public License v3.0
 * The details is bundled with this project in the file LICENSE.txt.
 *
 * @project    UNIT3D Community Edition
 *
 * @author     Roardom <roardom@protonmail.com>
 * @license    https://www.gnu.org/licenses/agpl-3.0.en.html/ GNU Affero General Public License v3.0
 */

namespace App\Http\Livewire;

use App\Models\PrivateMessage;
use App\Models\User;
use App\Models\Warning;
use Illuminate\Support\Carbon;
use Livewire\Component;
use Livewire\WithPagination;

/**
 * @property \Illuminate\Contracts\Pagination\LengthAwarePaginator $warnings
 * @property int                                                   $automatedWarningsCount
 * @property int                                                   $manualWarningsCount
 * @property int                                                   $deletedWarningsCount
 */
class UserWarnings extends Component
{
    use WithPagination;

    public User $user;

    public string $warningTab = 'automated';

    public string $message = '';

    public int $perPage = 10;

    public ?string $sortField = null;

    public string $sortDirection = 'desc';

    protected $queryString = [
        'warningTab' => ['except' => 'automated'],
    ];

    protected $rules = [
        'message' => [
            'required',
            'filled',
            'max:255',
        ],
    ];

    final public function getWarningsProperty(): \Illuminate\Contracts\Pagination\LengthAwarePaginator
    {
        return $this->user
            ->userwarning()
            ->when(
                auth()->user()->group->is_modo,
                fn ($query) => $query->with('warneduser', 'staffuser', 'torrenttitle'),
                fn ($query) => $query->with('warneduser', 'torrenttitle'),
            )
            ->when($this->warningTab === 'automated', fn ($query) => $query->whereNotNull('torrent'))
            ->when($this->warningTab === 'manual', fn ($query) => $query->whereNull('torrent'))
            ->when($this->warningTab === 'deleted', fn ($query) => $query->onlyTrashed())
            ->when(
                $this->sortField === null,
                fn ($query) => $query->orderByDesc('active')->orderByDesc('created_at'),
                fn ($query) => $query->orderBy($this->sortField, $this->sortDirection),
            )
            ->paginate($this->perPage);
    }

    final public function getAutomatedWarningsCountProperty(): int
    {
        return $this->user->userwarning()->whereNotNull('torrent')->count();
    }

    final public function getManualWarningsCountProperty(): int
    {
        return $this->user->userwarning()->whereNull('torrent')->count();
    }

    final public function getDeletedWarningsCountProperty(): int
    {
        return $this->user->userwarning()->onlyTrashed()->count();
    }

    /**
     * Manually warn a user.
     */
    final public function store(): void
    {
        abort_unless(auth()->user()->group->is_modo, 403);

        $this->validate();

        Warning::create([
            'user_id'    => $this->user->id,
            'warned_by'  => auth()->user()->id,
            'torrent'    => null,
            'reason'     => $this->message,
            'expires_on' => Carbon::now()->addDays(config('hitrun.expire')),
            'active'     => '1',
        ]);

        PrivateMessage::create([
            'sender_id'   => User::SYSTEM_USER_ID,
            'receiver_id' => $this->user->id,
            'subject'     => '收到警告',
            'message'     => '您收到了一个[b]警告[/b]。理由:  '.$this->message,
        ]);

        $this->message = '';

        $this->dispatchBrowserEvent('success', ['type' => 'success', 'message' => '警告发送成功']);
    }

    /**
     * Deactivate A Warning.
     */
    final public function deactivate(Warning $warning): void
    {
        abort_unless(auth()->user()->group->is_modo, 403);

        $staff = auth()->user();

        $warning->update([
            'expires_on' => now(),
            'active'     => false,
        ]);

        PrivateMessage::create([
            'sender_id'   => $staff->id,
            'receiver_id' => $this->user->id,
            'subject'     => '撤销警告',
            'message'     => $staff->username.' 已决定撤销你的种子 '.$warning->torrent.' 的警告。你幸运了！[color=red][b]这是一个自动系统消息，请勿回复！[/b][/color]',
        ]);

        $this->dispatchBrowserEvent('success', ['type' => 'success', 'message' => '警告关闭成功']);
    }

    /**
     * Reactivate A Warning.
     */
    final public function reactivate(Warning $warning): void
    {
        abort_unless(auth()->user()->group->is_modo, 403);

        $warning->update([
            'expires_on' => $warning->created_at->addDays(config('hitrun.expire')),
            'active'     => true,
        ]);

        $this->dispatchBrowserEvent('success', ['type' => 'success', 'message' => '警告激活成功']);
    }

    /**
     * Deactivate All Warnings.
     */
    final public function massDeactivate(): void
    {
        abort_unless(auth()->user()->group->is_modo, 403);

        $staff = auth()->user();

        $this->user
            ->warnings()
            ->where('active', '=', 1)
            ->update([
                'expires_on' => now(),
                'active'     => false,
            ]);

        PrivateMessage::create([
            'sender_id'   => $staff->id,
            'receiver_id' => $this->user->id,
            'subject'     => '撤销所有警告',
            'message'     => $staff->username.' 撤销了你的所有警告，你解放了！ [color=red][b]这是一条系统消息，请勿回复！[/b][/color]',
        ]);

        $this->dispatchBrowserEvent('success', ['type' => 'success', 'message' => '所有警告关闭成功']);
    }

    /**
     * Delete A Warning.
     */
    final public function destroy(Warning $warning): void
    {
        abort_unless(auth()->user()->group->is_modo, 403);

        $staff = auth()->user();

        $warning->update([
            'deleted_by' => $staff->id,
            'active'     => false,
            'expires_on' => now(),
        ]);

        $warning->delete();

        PrivateMessage::create([
            'sender_id'   => $staff->id,
            'receiver_id' => $this->user->id,
            'subject'     => 'Hit and Run Warning Deleted',
            'message'     => $staff->username.' 撤销了种子 '.$warning->torrent.' 的警告，你解放了！[color=red][b]这是一条系统消息，请勿回复！[/b][/color]',
        ]);

        $this->dispatchBrowserEvent('success', ['type' => 'success', 'message' => '警告删除成功']);
    }

    /**
     * Delete All Warnings.
     */
    final public function massDestroy(): void
    {
        abort_unless(auth()->user()->group->is_modo, 403);

        $staff = auth()->user();

        $this->user->warnings()->update([
            'deleted_by' => $staff->id,
            'active'     => false,
            'expires_on' => now(),
        ]);

        $this->user->warnings()->delete();

        PrivateMessage::create([
            'sender_id'   => $staff->id,
            'receiver_id' => $this->user->id,
            'subject'     => '撤销所有警告',
            'message'     => $staff->username.' 撤销了你的所有警告，你解放了！ [color=red][b]这是一条系统消息，请勿回复！[/b][/color]',
        ]);

        $this->dispatchBrowserEvent('success', ['type' => 'success', 'message' => '所有警告删除成功']);
    }

    /**
     * Restore A Soft Deleted Warning.
     */
    final public function restore(int $id): void
    {
        abort_unless(auth()->user()->group->is_modo, 403);

        Warning::withTrashed()->findOrFail($id)->restore();

        $this->dispatchBrowserEvent('success', ['type' => 'success', 'message' => '警告恢复成功']);
    }

    final public function sortBy(string $field): void
    {
        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortDirection = 'asc';
        }

        $this->sortField = $field;
    }

    final public function render(): \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Contracts\Foundation\Application
    {
        return view('livewire.user-warnings', [
            'warnings'               => $this->warnings,
            'automatedWarningsCount' => $this->automatedWarningsCount,
            'manualWarningsCount'    => $this->manualWarningsCount,
            'deletedWarningsCount'   => $this->deletedWarningsCount,
        ]);
    }
}
