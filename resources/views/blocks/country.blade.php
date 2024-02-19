@if ($countries->isNotEmpty())
    <section class="panelV2">
        <h2 class="panel__heading">
            {{ __('artists.country') }}
        </h2>
        <div x-data>
            <ul class="featured-carousel" x-ref="featured">
                @foreach ($countries as $country)
                    <li class="featured-carousel__slide" style="margin-right: 8px; margin-left: 8px; flex:none">
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
