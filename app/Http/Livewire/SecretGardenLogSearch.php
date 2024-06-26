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

use App\Models\SecretGardenLog;
use Livewire\Component;
use Livewire\WithPagination;

class SecretGardenLogSearch extends Component
{
    use WithPagination;

    public string $username = '';
    public string $userId = '';
    public string $url = '';
    public string $ipAddress = '';
    public int $perPage = 25;
    public string $sortField = 'created_at';
    public string $sortDirection = 'desc';

    protected $queryString = [
        'username' => ['except' => ''],
        'userId' => ['except' => ''],
        'url' => ['except' => ''],
        'ipAddress' => ['except' => ''],
        'page' => ['except' => 1],
        'perPage' => ['except' => ''],
    ];

    public function updatedPage(): void
    {
        $this->emit('paginationChanged');
    }

    public function sortBy($field): void
    {
        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortDirection = 'asc';
        }

        $this->sortField = $field;
    }

    public function getLogsProperty()
    {
        return SecretGardenLog::query()
            ->when($this->username, fn($query) => $query->where('username', 'LIKE', '%' . $this->username . '%'))
            ->when($this->userId, fn($query) => $query->where('user_id', $this->userId))
            ->when($this->url, fn($query) => $query->where('url', 'LIKE', '%' . $this->url . '%'))
            ->when($this->ipAddress, fn($query) => $query->where('ip_address', 'LIKE', '%' . $this->ipAddress . '%'))
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate($this->perPage);
    }

    public function render()
    {
        return view('livewire.secret-garden-log-search', [
            'logs' => $this->logs,
        ]);
    }
}

