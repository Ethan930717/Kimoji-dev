<section class="panelV2">
    <header class="panel__header">
        <h2 class="panel__heading">{{ __('actors.title') }}</h2>
        <div class="panel__actions">
            <div class="panel__action">
                <div class="form__group">
                    <input
                        id="name"
                        class="form__text"
                        placeholder=" "
                        type="text"
                        wire:model.debounce.250ms="search"
                    />
                    <label class="form__label form__label--floating" for="name">
                        {{ __('actors.search-by-name') }}
                    </label>
                    <div wire:loading wire:target="search">
                        <span style="font-size: 12px">Searching...</span>
                    </div>
                </div>
            </div>
        </div>
    </header>
    {{ $actors->links('partials.pagination') }}
    <div class="panel__body">
        <table class="data-table">
            <thead>
            <tr>
                <th></th> <!-- 用于演员头像 -->
                <th>{{ __('actors.artname') }}</th>
                <th>
                    <a href="#" wire:click.prevent="sortBy('english_name')">
                        {{ __('actors.english_name') }}
                        @if ($sortField == 'english_name')
                            @if ($sortDirection == 'asc')
                                &uarr;
                        @else
                            &darr;
                        @endif
                        @endif
                    </a>
                </th>
                <th>
                    <a href="#" wire:click.prevent="sortBy('birth_date')">
                        {{ __('actors.born') }}
                        @if ($sortField == 'birth_date')
                            @if ($sortDirection == 'asc')
                                &uarr;
                        @else
                            &darr;
                        @endif
                        @endif
                    </a>
                </th>
                <th>
                    <a href="#" wire:click.prevent="sortBy('measurements')">
                        {{ __('actors.measurements') }}
                        @if ($sortField == 'measurements')
                            @if ($sortDirection == 'asc')
                                &uarr;
                        @else
                            &darr;
                        @endif
                        @endif
                    </a>
                </th>
            </tr>
            </thead>
            <tbody>
            @forelse ($actors as $actor)
                <tr>
                    <td>
                        <a href="{{ route('secretgarden.actor.show', ['id' => $actor->id]) }}">
                            <img
                                alt="{{ $actor->name }}"
                                src="{{ $actor->image_url ? $actor->image_url : 'https://via.placeholder.com/160x240' }}"
                                style="width: 100px; height: 100px; object-fit: cover; border-radius: 10px"
                            />
                        </a>
                    </td>
                    <td><a href="{{ route('secretgarden.actor.show', ['id' => $actor->id]) }}">{{ $actor->name }}</a></td>
                    <td>{{ str_replace('_', ' ', $actor->english_name) }}</td>
                    <td>{{ $actor->birth_date }}</td>
                    <td>{{ $actor->measurements }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="5">{{ __('No Result') }}</td>
                </tr>
            @endforelse
            </tbody>
        </table>
    </div>
    {{ $actors->links('partials.pagination') }}
</section>
