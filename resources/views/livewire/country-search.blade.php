<div>
    <input type="text" wire:model="search" placeholder="{{ __('搜索国家') }}" class="form__text" />

    <div
        class="panel__body"
        style="display: grid; grid-template-columns: repeat(auto-fit, minmax(140px, 1fr)); gap: 2rem;">
            @forelse ($countries as $country)
            <figure style="display: flex; flex-direction: column; align-items: center">
                <a href="{{ route('artists.country.show', ['country_name' => urlencode($country->country)]) }}">
                    <div class="country-image-container">
                        <img src="/img/country/{{ $country->country }}.webp" alt="{{ $country->country }}" />
                    </div>
                    <figcaption>{{ $country->country }}</figcaption>
                </a>
            </figure>
            @empty
                <p>{{ __('未找到国家信息') }}</p>
            @endforelse
    </div>
</div>
