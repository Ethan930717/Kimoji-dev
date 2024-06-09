<section class="panelV2">
    <header class="panel__header">
        <h2 class="panel__heading">{{ __('mediahub.makers') }}</h2>
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
    {{ $makers->links('partials.pagination') }}
    <div class="panel__body">
        <ul class="mediahub-card__list">
            @forelse ($makers as $maker)
                <li class="mediahub-card__list-item">
                    <a
                        href="{{ route('secretgarden.video_makers.show', ['id' => $maker->id]) }}"
                        class="mediahub-card"
                    >
                        <h2 class="mediahub-card__heading">
                            @isset($maker->poster)
                                <img
                                    class="mediahub-card__image"
                                    src="{{ url('secretgarden/poster/' . $maker->poster) }}"
                                    alt="{{ $maker->name }}"
                                />
                            @else
                                {{ $maker->name }}
                            @endisset
                        </h2>
                        <h3 class="mediahub-card__subheading">
                            <i class="{{ config('other.font-awesome') }} fa-tag"></i>
                            {{ $maker->name }} | {{ $maker->videos_count }} Videos
                        </h3>
                    </a>
                </li>
            @empty
                No {{ __('mediahub.makers') }}
            @endforelse
        </ul>
    </div>
    {{ $makers->links('partials.pagination') }}
</section>
