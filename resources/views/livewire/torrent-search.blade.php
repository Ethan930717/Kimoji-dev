<div class="page__torrents torrent-search__component">
    <section class="panelV2 torrent-search__filters" x-data="{ open: false }">
        <header class="panel__header">
            <h2 class="panel__heading">{{ __('common.search') }}</h2>
            <div class="panel__actions">
                <div class="panel__action">
                    <button
                        class="form__button form__button--outlined form__button--centered"
                        x-on:click="open = ! open"
                        x-text="open ? '{{ __('common.search-hide') }}' : '{{ __('common.search-advanced') }}'"
                    ></button>
                </div>
            </div>
        </header>
        <div class="panel__body" style="padding: 5px">
            <div class="form__group--horizontal">
                <p class="form__group">
                    <input
                        id="name"
                        wire:model="name"
                        class="form__text"
                        placeholder=" "
                        autofocus
                    />
                    <label class="form__label form__label--floating" for="name">
                        {{ __('torrent.name') }}
                    </label>
                </p>
            </div>
            <form class="form" x-cloak x-show="open">
                <div class="form__group--short-horizontal">
                    <p class="form__group">
                        <input
                            id="description"
                            wire:model="description"
                            class="form__text"
                            placeholder=" "
                        />
                        <label class="form__label form__label--floating" for="description">
                            {{ __('torrent.description') }}
                        </label>
                    </p>
                </div>
                <div class="form__group--short-horizontal">
                    <div class="form__group--short-horizontal">
                        <p class="form__group">
                            <input
                                id="minSize"
                                wire:model="minSize"
                                class="form__text"
                                placeholder=" "
                            />
                            <label class="form__label form__label--floating" for="minSize">
                                Minimum Size
                            </label>
                        </p>
                        <p class="form__group">
                            <select
                                id="minSizeMultiplier"
                                wire:model="minSizeMultiplier"
                                class="form__select"
                                placeholder=" "
                            >
                                <option value="1" selected>Bytes</option>
                                <option value="1000">KB</option>
                                <option value="1024">KiB</option>
                                <option value="1000000">MB</option>
                                <option value="1000000000">GB</option>
                                <option value="1000000000000">TB</option>
                            </select>
                            <label
                                class="form__label form__label--floating"
                                for="minSizeMultiplier"
                            >
                                Unit
                            </label>
                        </p>
                    </div>
                    <div class="form__group--short-horizontal">
                        <p class="form__group">
                            <input
                                id="maxSize"
                                wire:model="maxSize"
                                class="form__text"
                                placeholder=" "
                            />
                            <label class="form__label form__label--floating" for="maxSize">
                                Maximum Size
                            </label>
                        </p>
                        <p class="form__group">
                            <select
                                id="maxSizeMultiplier"
                                wire:model="maxSizeMultiplier"
                                class="form__select"
                                placeholder=" "
                            >
                                <option value="1" selected>Bytes</option>
                                <option value="1000">KB</option>
                                <option value="1024">KiB</option>
                                <option value="1000000">MB</option>
                                <option value="1000000000">GB</option>
                                <option value="1000000000000">TB</option>
                            </select>
                            <label
                                class="form__label form__label--floating"
                                for="maxSizeMultiplier"
                            >
                                Unit
                            </label>
                        </p>
                    </div>
                </div>
                <div class="form__group">
                        <fieldset class="form__fieldset">
                            <legend class="form__legend">{{ __('torrent.type') }}</legend>
                            <div class="form__fieldset-checkbox-container">
                                @php
                                    $types = cache()->remember(
                                        'types',
                                        3_600,
                                        fn () => App\Models\Type::orderBy('position')->get()
                                    )
                                @endphp

                                @foreach ($types as $type)
                                    <p class="form__group">
                                        <label class="form__label">
                                            <input
                                                class="form__checkbox"
                                                type="checkbox"
                                                value="{{ $type->id }}"
                                                wire:model="types"
                                            />
                                            {{ $type->name }}
                                        </label>
                                    </p>
                                @endforeach
                            </div>
                        </fieldset>
                    </div>
                <div class="form__group">
                    <fieldset class="form__fieldset">
                        <legend class="form__legend">{{ __('distributor.label') }}</legend>
                        <div class="form__fieldset-checkbox-container">
                            @php
                                $distributors = cache()->remember(
                                    'distributors',
                                    3_600,
                                    fn () => App\Models\Distributor::orderBy('name')->get()
                                );
                            @endphp

                            @foreach ($distributors as $distributor)
                                <p class="form__group">
                                    <label class="form__label">
                                        <input
                                            class="form__checkbox"
                                            type="checkbox"
                                            value="{{ $distributor->id }}"
                                            wire:model="selectedDistributors"
                                        />
                                        {{ $distributor->name }}
                                    </label>
                                </p>
                            @endforeach
                        </div>
                    </fieldset>
                </div>
                <div class="form__group">
                        <fieldset class="form__fieldset">
                            <legend class="form__legend">Buff</legend>
                            <div class="form__fieldset-checkbox-container">
                                <p class="form__group">
                                    <label class="form__label">
                                        <input
                                            class="form__checkbox"
                                            type="checkbox"
                                            value="0"
                                            wire:model="free"
                                        />
                                        0% Freeleech
                                    </label>
                                </p>
                                <p class="form__group">
                                    <label class="form__label">
                                        <input
                                            class="form__checkbox"
                                            type="checkbox"
                                            value="25"
                                            wire:model="free"
                                        />
                                        25% Freeleech
                                    </label>
                                </p>
                                <p class="form__group">
                                    <label class="form__label">
                                        <input
                                            class="form__checkbox"
                                            type="checkbox"
                                            value="50"
                                            wire:model="free"
                                        />
                                        50% Freeleech
                                    </label>
                                </p>
                                <p class="form__group">
                                    <label class="form__label">
                                        <input
                                            class="form__checkbox"
                                            type="checkbox"
                                            value="75"
                                            wire:model="free"
                                        />
                                        75% Freeleech
                                    </label>
                                </p>
                                <p class="form__group">
                                    <label class="form__label">
                                        <input
                                            class="form__checkbox"
                                            type="checkbox"
                                            value="100"
                                            wire:model="free"
                                        />
                                        100% Freeleech
                                    </label>
                                </p>
                                <p class="form__group">
                                    <label class="form__label">
                                        <input
                                            class="form__checkbox"
                                            type="checkbox"
                                            value="1"
                                            wire:model="doubleup"
                                        />
                                        Double Upload
                                    </label>
                                </p>
                                <p class="form__group">
                                    <label class="form__label">
                                        <input
                                            class="form__checkbox"
                                            type="checkbox"
                                            value="1"
                                            wire:model="featured"
                                        />
                                        Featured
                                    </label>
                                </p>
                                <p class="form__group">
                                    <label class="form__label">
                                        <input
                                            class="form__checkbox"
                                            type="checkbox"
                                            value="1"
                                            wire:model="refundable"
                                        />
                                        Refundable
                                    </label>
                                </p>
                            </div>
                        </fieldset>
                    </div>
                <div class="form__group">
                        <fieldset class="form__fieldset">
                            <legend class="form__legend">{{ __('torrent.health') }}</legend>
                            <div class="form__fieldset-checkbox-container">
                                <p class="form__group">
                                    <label class="form__label">
                                        <input
                                            class="form__checkbox"
                                            type="checkbox"
                                            value="1"
                                            wire:model="alive"
                                        />
                                        {{ __('torrent.alive') }}
                                    </label>
                                </p>
                                <p class="form__group">
                                    <label class="form__label">
                                        <input
                                            class="form__checkbox"
                                            type="checkbox"
                                            value="1"
                                            wire:model="dying"
                                        />
                                        Dying
                                    </label>
                                </p>
                                <p class="form__group">
                                    <label class="form__label">
                                        <input
                                            class="form__checkbox"
                                            type="checkbox"
                                            value="1"
                                            wire:model="dead"
                                        />
                                        Dead
                                    </label>
                                </p>
                                <p class="form__group">
                                    <label class="form__label">
                                        <input
                                            class="form__checkbox"
                                            type="checkbox"
                                            value="1"
                                            wire:model="graveyard"
                                        />
                                        {{ __('graveyard.graveyard') }}
                                    </label>
                                </p>
                            </div>
                        </fieldset>
                    </div>
                <div class="form__group">
                        <fieldset class="form__fieldset">
                            <legend class="form__legend">{{ __('torrent.history') }}</legend>
                            <div class="form__fieldset-checkbox-container">
                                <p class="form__group">
                                    <label class="form__label">
                                        <input
                                            class="form__checkbox"
                                            type="checkbox"
                                            value="1"
                                            wire:model="notDownloaded"
                                        />
                                        Not Downloaded
                                    </label>
                                </p>
                                <p class="form__group">
                                    <label class="form__label">
                                        <input
                                            class="form__checkbox"
                                            type="checkbox"
                                            value="1"
                                            wire:model="downloaded"
                                        />
                                        Downloaded
                                    </label>
                                </p>
                                <p class="form__group">
                                    <label class="form__label">
                                        <input
                                            class="form__checkbox"
                                            type="checkbox"
                                            value="1"
                                            wire:model="seeding"
                                        />
                                        Seeding
                                    </label>
                                </p>
                                <p class="form__group">
                                    <label class="form__label">
                                        <input
                                            class="form__checkbox"
                                            type="checkbox"
                                            value="1"
                                            wire:model="leeching"
                                        />
                                        Leeching
                                    </label>
                                </p>
                                <p class="form__group">
                                    <label class="form__label">
                                        <input
                                            class="form__checkbox"
                                            type="checkbox"
                                            value="1"
                                            wire:model="incomplete"
                                        />
                                        Incomplete
                                    </label>
                                </p>
                            </div>
                        </fieldset>
                    </div>
            </form>
        </div>
    </section>
    <section class="panelV2 torrent-search__results">
        <header class="panel__header">
            <h2 class="panel__heading">{{ __('torrent.torrents') }}</h2>
            <div class="panel__actions">
                <div class="panel__action">
                    <div class="form__group">
                        <select id="view" class="form__select" wire:model="view" required>
                            <option value="list">{{ __('torrent.list') }}</option>
                            <option value="card">{{ __('torrent.cards') }}</option>
                            <option value="group">{{ __('torrent.groupings') }}</option>
                            <option value="poster">{{ __('torrent.poster') }}</option>
                        </select>
                        <label class="form__label form__label--floating" for="view">{{ __('common.layout') }}</label>
                    </div>
                </div>
                <div class="panel__action">
                    <div class="form__group">
                        <select id="perPage" class="form__select" wire:model="perPage" required>
                            @if (\in_array($view, ['card', 'poster']))
                                <option>24</option>
                                <option>48</option>
                                <option>72</option>
                                <option>96</option>
                            @else
                                <option>25</option>
                                <option>50</option>
                                <option>75</option>
                                <option>100</option>
                            @endif
                        </select>
                        <label class="form__label form__label--floating" for="perPage">
                            {{ __('common.quantity') }}
                        </label>
                    </div>
                </div>
            </div>
        </header>
        {{ $torrents->links('partials.pagination') }}

        @switch(true)
            @case($view === 'list')
                <div class="data-table-wrapper torrent-search--list__results">
                    <table class="data-table">
                        <thead>
                            <tr
                                @class([
                                    'torrent-search--list__headers' => auth()->user()->show_poster,
                                    'torrent-search--list__no-poster-headers' => ! auth()->user()->show_poster,
                                ])
                            >
                                @if (auth()->user()->show_poster)
                                    <th class="torrent-search--list__poster-header">Poster</th>
                                @endif

                                <th class="torrent-search--list__format-header">Format</th>
                                <th
                                    class="torrent-search--list__name-header"
                                    wire:click="sortBy('name')"
                                    role="columnheader button"
                                >
                                    {{ __('torrent.name') }}
                                    @include('livewire.includes._sort-icon', ['field' => 'name'])
                                </th>
                                <th class="torrent-search--list__actions-header">
                                    {{ __('common.actions') }}
                                </th>
                                <th class="torrent-search--list__ratings-header">Rating</th>
                                <th
                                    class="torrent-search--list__size-header"
                                    wire:click="sortBy('size')"
                                    role="columnheader button"
                                >
                                    {{ __('torrent.size') }}
                                    @include('livewire.includes._sort-icon', ['field' => 'size'])
                                </th>
                                <th
                                    class="torrent-search--list__seeders-header"
                                    wire:click="sortBy('seeders')"
                                    role="columnheader button"
                                    title="{{ __('torrent.seeders') }}"
                                >
                                    <i class="fas fa-arrow-alt-circle-up"></i>
                                    @include('livewire.includes._sort-icon', ['field' => 'seeders'])
                                </th>
                                <th
                                    class="torrent-search--list__leechers-header"
                                    wire:click="sortBy('leechers')"
                                    role="columnheader button"
                                    title="{{ __('torrent.leechers') }}"
                                >
                                    <i class="fas fa-arrow-alt-circle-down"></i>
                                    @include('livewire.includes._sort-icon', ['field' => 'leechers'])
                                </th>
                                <th
                                    class="torrent-search--list__completed-header"
                                    wire:click="sortBy('times_completed')"
                                    role="columnheader button"
                                    title="{{ __('torrent.completed') }}"
                                >
                                    <i class="fas fa-check-circle"></i>
                                    @include('livewire.includes._sort-icon', ['field' => 'times_completed'])
                                </th>
                                <th
                                    class="torrent-search--list__age-header"
                                    wire:click="sortBy('created_at')"
                                    role="columnheader button"
                                >
                                    {{ __('torrent.age') }}
                                    @include('livewire.includes._sort-icon', ['field' => 'created_at'])
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($torrents as $torrent)
                                <x-torrent.row
                                    :meta="$torrent->meta"
                                    :torrent="$torrent"
                                    :personalFreeleech="$personalFreeleech"
                                />
                            @empty
                                <tr>
                                    <td colspan="10">{{ __('common.no-result') }}</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @break
            @case($view === 'card')
                <table class="data-table">
                    <thead>
                        <tr>
                            <th
                                class="torrent-search--list__name-header"
                                wire:click="sortBy('name')"
                                role="columnheader button"
                            >
                                {{ __('torrent.name') }}
                                @include('livewire.includes._sort-icon', ['field' => 'name'])
                            </th>
                            <th
                                class="torrent-search--list__size-header"
                                wire:click="sortBy('size')"
                                role="columnheader button"
                            >
                                {{ __('torrent.size') }}
                                @include('livewire.includes._sort-icon', ['field' => 'size'])
                            </th>
                            <th
                                class="torrent-search--list__seeders-header"
                                wire:click="sortBy('seeders')"
                                role="columnheader button"
                                title="{{ __('torrent.seeders') }}"
                            >
                                <i class="fas fa-arrow-alt-circle-up"></i>
                                @include('livewire.includes._sort-icon', ['field' => 'seeders'])
                            </th>
                            <th
                                class="torrent-search--list__leechers-header"
                                wire:click="sortBy('leechers')"
                                role="columnheader button"
                                title="{{ __('torrent.leechers') }}"
                            >
                                <i class="fas fa-arrow-alt-circle-down"></i>
                                @include('livewire.includes._sort-icon', ['field' => 'leechers'])
                            </th>
                            <th
                                class="torrent-search--list__completed-header"
                                wire:click="sortBy('times_completed')"
                                role="columnheader button"
                                title="{{ __('torrent.completed') }}"
                            >
                                <i class="fas fa-check-circle"></i>
                                @include('livewire.includes._sort-icon', ['field' => 'times_completed'])
                            </th>
                            <th
                                class="torrent-search--list__age-header"
                                wire:click="sortBy('created_at')"
                                role="columnheader button"
                            >
                                {{ __('common.created_at') }}
                                @include('livewire.includes._sort-icon', ['field' => 'created_at'])
                            </th>
                        </tr>
                    </thead>
                </table>
                <div class="panel__body torrent-search--card__results">
                    @forelse ($torrents as $torrent)
                        <x-torrent.card :meta="$torrent->meta" :torrent="$torrent" />
                    @empty
                        {{ __('common.no-result') }}
                    @endforelse
                </div>

                @break
            @case($view === 'group')
                <table class="data-table">
                    <thead>
                        <tr>
                            <th
                                class="torrent-search--list__completed-header"
                                wire:click="sortBy('times_completed')"
                                role="columnheader button"
                                title="{{ __('torrent.completed') }}"
                            >
                                <i class="fas fa-check-circle"></i>
                                @include('livewire.includes._sort-icon', ['field' => 'times_completed'])
                            </th>
                            <th
                                class="torrent-search--list__age-header"
                                wire:click="sortBy('created_at')"
                                role="columnheader button"
                            >
                                {{ __('common.created_at') }}
                                @include('livewire.includes._sort-icon', ['field' => 'created_at'])
                            </th>
                        </tr>
                    </thead>
                </table>
                <div class="panel__body torrent-search--grouped__results">
                    @forelse ($torrents as $group)
                        @switch($group->meta)
                            @case('movie')
                                <x-movie.card
                                    :media="$group"
                                    :personalFreeleech="$personalFreeleech"
                                />

                                @break
                            @case('tv')
                                <x-tv.card
                                    :media="$group"
                                    :personalFreeleech="$personalFreeleech"
                                />

                                @break
                        @endswitch
                    @empty
                        {{ __('common.no-result') }}
                    @endforelse
                </div>

                @break
            @case($view === 'poster')
                <table class="data-table">
                    <thead>
                        <tr>
                            <th
                                class="torrent-search--list__completed-header"
                                wire:click="sortBy('times_completed')"
                                role="columnheader button"
                                title="{{ __('torrent.completed') }}"
                            >
                                <i class="fas fa-check-circle"></i>
                                @include('livewire.includes._sort-icon', ['field' => 'times_completed'])
                            </th>
                            <th
                                class="torrent-search--list__age-header"
                                wire:click="sortBy('created_at')"
                                role="columnheader button"
                            >
                                {{ __('common.created_at') }}
                                @include('livewire.includes._sort-icon', ['field' => 'created_at'])
                            </th>
                        </tr>
                    </thead>
                </table>
                <div class="panel__body torrent-search--poster__results">
                    @forelse ($torrents as $group)
                        @switch($group->meta)
                            @case('movie')
                                <x-movie.poster
                                    :categoryId="$group->category_id"
                                    :movie="$group->movie"
                                    :tmdb="$group->tmdb"
                                />

                                @break
                            @case('tv')
                                <x-tv.poster
                                    :categoryId="$group->category_id"
                                    :tv="$group->tv"
                                    :tmdb="$group->tmdb"
                                />

                                @break
                        @endswitch
                    @empty
                        {{ __('common.no-result') }}
                    @endforelse
                </div>

                @break
        @endswitch
        {{ $torrents->links('partials.pagination') }}
    </section>
</div>
