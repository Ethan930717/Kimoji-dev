<section class="panelV2">
    <header class="panel__header">
        <h2 class="panel__heading">{{ __('artists.country') }}</h2>
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
                        {{ __('搜索国家') }}
                    </label>
                </div>
            </div>
        </div>
    </header>
    <div
        class="panel__body"
        style="
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 2rem;
            justify-content: center;
            align-items: start;
            padding: 1rem;
        "
    >
        @forelse ($countries as $country)
            <figure style="display: flex; flex-direction: column; align-items: center; margin: 0;">
                <a href="{{ route('artists.country.show', ['country_name' => urlencode($country->country)]) }}">
                    <div class="country-image-container" style="width: 220px; height: 220px; overflow: hidden; border-radius: 8px;">
                        <img src="/img/country/{{ $country->country }}.webp" alt="{{ $country->country }}" style="width: 100%; height: auto;"/>
                    </div>
                    <figcaption style="text-align: center; font-size: 20px; margin-top: 10px;">
                        {{ $country->country }} ({{ $country->total_artists }})
                    </figcaption>
                </a>
            </figure>
        @empty
            <p>{{ __('未找到国家信息') }}</p>
        @endforelse
    </div>
</section>
