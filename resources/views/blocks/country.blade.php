@if ($countries->isNotEmpty())
    <section class="panelV2">
        <h2 class="panel__heading">
            <a href="{{ route('mediahub.country.index') }}" style="text-shadow: 0 0 5px #fb7171;">
                {{ __('artists.country_title') }}
            </a>
        </h2>
        <div x-data>
            <ul
                    class="featured-carousel"
                    x-ref="countryCarousel"
                    x-init="
                    setInterval(function () {
                        $refs.countryCarousel.parentNode.matches(':hover')
                            ? null
                            : $refs.countryCarousel.scrollLeft + $refs.countryCarousel.offsetWidth >= $refs.countryCarousel.scrollWidth
                              ? ($refs.countryCarousel.scrollLeft = 0)
                              : ($refs.countryCarousel.scrollLeft += $refs.countryCarousel.offsetWidth / 2 + 16);
                    }, 5000)
                "
            >
                @foreach ($countries as $country)
                    <li class="featured-carousel__slide" style="margin-right: 8px; margin-left: 8px; flex:none">
                        <a href="{{ route('artists.country.show', ['country_name' => $country->country]) }}">
                            <div class="country-image-container">
                                <img src="/img/country/{{ $country->country }}.webp" alt="{{ $country->country }}" />
                            </div>
                            <div>{{ __('country.' . $country->country) }}</div>
                        </a>
                    </li>
                @endforeach
            </ul>
            <nav class="featured-carousel__nav">
                <button
                        class="featured-carousel__previous"
                        x-on:click="
                        $refs.countryCarousel.scrollLeft - $refs.countryCarousel.offsetWidth / 2 <= 0
                            ? ($refs.countryCarousel.scrollLeft = $refs.countryCarousel.scrollWidth - $refs.countryCarousel.offsetWidth)
                            : ($refs.countryCarousel.scrollLeft -= $refs.countryCarousel.offsetWidth / 2)
                    "
                >
                    <i class="{{ \config('other.font-awesome') }} fa-angle-left"></i>
                </button>
                <button
                        class="featured-carousel__next"
                        x-on:click="
                        $refs.countryCarousel.scrollLeft + $refs.countryCarousel.offsetWidth / 2 >= $refs.countryCarousel.scrollWidth
                            ? ($refs.countryCarousel.scrollLeft = 0)
                            : ($refs.countryCarousel.scrollLeft += $refs.countryCarousel.offsetWidth / 2)
                    "
                >
                    <i class="{{ \config('other.font-awesome') }} fa-angle-right"></i>
                </button>
            </nav>
        </div>
    </section>
@endif
