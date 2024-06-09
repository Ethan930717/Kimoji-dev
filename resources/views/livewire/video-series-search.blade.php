<section class="panelV2">
    <header class="panel__header">
        <h2 class="panel__heading">{{ __('mediahub.series') }}</h2>
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
    {{ $series->links('partials.pagination') }}
    <div class="panel__body">
        <ul class="mediahub-card__list">
            @forelse ($series as $series)
                <li class="custom-card__list-item" style="background-image: url('{{ url('secretgarden/poster/' . $series->poster) }}');">
                    <a href="{{ route('secretgarden.video_series.show', ['id' => $series->id]) }}">
                        <h2 class="custom-card__heading">
                            <i class="{{ config('other.font-awesome') }} fa-tag"></i>
                            {{ $series->name }} | {{ $series->videos_count }} Videos
                        </h2>
                    </a>
                </li>
            @empty
                No {{ __('mediahub.series') }}
            @endforelse
        </ul>
    </div>
    {{ $series->links('partials.pagination') }}
</section>
