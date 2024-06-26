<section class="panelV2">
  <header class="panel__header">
    <h2 class="panel__heading">{{ __('secret_garden.logs') }}</h2>
    <div class="panel__actions">
      <div class="panel__action">
        <div class="form__group">
          <input
            id="username"
            class="form__text"
            type="text"
            wire:model="username"
            placeholder=" "
          />
          <label class="form__label form__label--floating" for="username">
            {{ __('common.username') }}
          </label>
        </div>
      </div>
      <div class="panel__action">
        <div class="form__group">
          <input
            id="userId"
            class="form__text"
            type="text"
            wire:model="userId"
            placeholder=" "
          />
          <label class="form__label form__label--floating" for="userId">
            {{ __('common.user_id') }}
          </label>
        </div>
      </div>
      <div class="panel__action">
        <div class="form__group">
          <input
            id="url"
            class="form__text"
            type="text"
            wire:model="url"
            placeholder=" "
          />
          <label class="form__label form__label--floating" for="url">
            {{ __('common.url') }}
          </label>
        </div>
      </div>
      <div class="panel__action">
        <div class="form__group">
          <input
            id="ipAddress"
            class="form__text"
            type="text"
            wire:model="ipAddress"
            placeholder=" "
          />
          <label class="form__label form__label--floating" for="ipAddress">
            {{ __('common.ip_address') }}
          </label>
        </div>
      </div>
      <div class="panel__action">
        <div class="form__group">
          <select id="quantity" class="form__select" wire:model="perPage" required>
            <option>25</option>
            <option>50</option>
            <option>100</option>
          </select>
          <label class="form__label form__label--floating" for="quantity">
            {{ __('common.quantity') }}
          </label>
        </div>
      </div>
    </div>
  </header>
  <table class="data-table">
    <thead>
    <tr>
      <th wire:click="sortBy('user_id')" role="columnheader button">
        {{ __('common.user_id') }}
        @include('livewire.includes._sort-icon', ['field' => 'user_id'])
      </th>
      <th wire:click="sortBy('username')" role="columnheader button">
        {{ __('common.username') }}
        @include('livewire.includes._sort-icon', ['field' => 'username'])
      </th>
      <th wire:click="sortBy('email')" role="columnheader button">
        {{ __('common.email') }}
        @include('livewire.includes._sort-icon', ['field' => 'email'])
      </th>
      <th wire:click="sortBy('url')" role="columnheader button">
        {{ __('common.url') }}
        @include('livewire.includes._sort-icon', ['field' => 'url'])
      </th>
      <th>{{ __('common.ip_address') }}</th>
      <th wire:click="sortBy('created_at')" role="columnheader button">
        {{ __('common.created_at') }}
        @include('livewire.includes._sort-icon', ['field' => 'created_at'])
      </th>
    </tr>
    </thead>
    <tbody>
    @forelse ($logs as $log)
    <tr>
      <td>{{ $log->user_id }}</td>
      <td>{{ $log->username }}</td>
      <td>{{ $log->email }}</td>
      <td>{{ $log->url }}</td>
      <td>{{ $log->ip_address }}</td>
      <td>
        <time
          datetime="{{ $log->created_at }}"
          title="{{ $log->created_at }}"
        >
          {{ $log->created_at }}
        </time>
      </td>
    </tr>
    @empty
    <tr>
      <td colspan="6">No logs found</td>
    </tr>
    @endforelse
    </tbody>
  </table>
  {{ $logs->links('partials.pagination') }}
</section>
