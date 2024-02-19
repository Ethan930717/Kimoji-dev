<div>
    <input type="text" wire:model="search" placeholder="{{ __('搜索国家') }}" class="form__text" />

    <div class="panel__body">
        <ul class="list">
            @forelse ($countries as $country)
                <li>
                    <a href="{{ route('artists.country.show', ['country_name' => urlencode($country->country)]) }}">
                        <div class="country-image-container">
                            <img src="/img/country/{{ $country->country }}.webp" alt="{{ $country->country }}" />
                        </div>
                        <div>{{ $country->country }}</div>
                    </a>
                </li>
            @empty
                <p>{{ __('未找到国家信息') }}</p>
            @endforelse
        </ul>
    </div>
</div>
