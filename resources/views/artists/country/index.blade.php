@if ($countries->isNotEmpty())
    <section class="panelV2" style="margin-top: 20px">
        <div class="panel__heading">
            <h2>{{ __('artists.country_title') }}</h2>
        </div>
        <div class="panel__body">
            <ul class="list">
                @foreach ($countries as $country)
                    <li>
                        <a href="{{ route('artists.country.show', ['country_name' => urlencode($country->country)]) }}">
                            <div class="country-image-container">
                                <img src="/img/country/{{ $country->country }}.webp" alt="{{ $country->country }}" />
                            </div>
                            <div>{{ $country->country }}</div>
                        </a>
                    </li>
                @endforeach
            </ul>
        </div>
    </section>
@endif
