<section class="panelV2">
    <header class="panel__header">
        <h2 class="panel__heading">{{ __('artists.title') }}</h2>
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
                        {{ __('artists.search-by-name') }}
                    </label>
                </div>
            </div>
        </div>
    </header>
    {{ $artists->links('partials.pagination') }}
    <div
        class="panel__body"
        style="
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(140px, 1fr));
            gap: 2rem;
        "
    >
        @forelse ($artists as $artist)
            <figure style="display: flex; flex-direction: column; align-items: center">
                <a href="{{ route('artists.show', ['id' => $artist->id]) }}">
                    <img
                        alt="{{ $artist->name }}"
                        src="{{ $artist->image_url ? $artist->image_url : 'https://via.placeholder.com/160x240' }}"
                        style="width: 140px; height: 140px; object-fit: cover; border-radius: 50%"
                    />
                </a>
                <figcaption>{{ $artist->name }}</figcaption>
            </figure>
        @empty
            {{ __('No Result') }}
        @endforelse
    </div>
    {{ $artists->links('partials.pagination') }}
</section>
