<section class="panelV2">
    <header class="panel__header">
        <h2 class="panel__heading">{{ __('mediahub.genres') }}</h2>
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
                        {{ __('torrent.search-by-name') }}
                    </label>
                </div>
            </div>
        </div>
    </header>
    {{ $genres->links('partials.pagination') }}
    <div class="panel__body">
        <ul class="mediahub-card__list">
            @forelse ($genres as $genre)
                <li class="custom-card__list-item">
                    <a
                        href="{{ route('torrents.index', ['view' => 'group', 'genreId' => $genre->id]) }}"
                        class="mediahub-card"
                    >
                        @isset($genre->poster)
                            <img
                                class="custom-card__image"
                                src="{{ url('secretgarden/poster/' . $genre->poster) }}"
                                alt="{{ $genre->name }}"
                            />
                        @endisset
                        <h2 class="custom-card__heading">
                            {{ $genre->name }}
                        </h2>
                        <h3 class="custom-card__subheading">
                            <i class="{{ config('other.font-awesome') }} fa-tag"></i>
                            {{ $genre->name }} | {{ $genre->videos_count }} Videos
                        </h3>
                    </a>
                </li>
            @empty
                No {{ __('mediahub.genres') }}
            @endforelse
        </ul>
    </div>
    {{ $genres->links('partials.pagination') }}
</section>
