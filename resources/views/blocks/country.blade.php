@if ($countries->isNotEmpty())
    <section class="panelV2">
        <h2 class="panel__heading">
            {{ __('artists.country') }}
        </h2>
        <div x-data>
            <ul class="featured-carousel" x-ref="featured">
                @foreach ($countries as $country)
                    <li class="featured-carousel__slide">
                        <a href="{{ route('artists.country.show', ['country_name' => urlencode($country->country)]) }}">
                            <div class="country-image-container">
                                <img src="/img/country/{{ $country->country }}.webp" alt="{{ $country->country }}" />
                            </div>
                            <div>{{ $country->country }}</div>
                        </a>
                    </li>
                @endforeach
            </ul>
            <nav class="featured-carousel__nav">
                <button
                    class="featured-carousel__previous"
                    x-on:click="
                    $refs.featured.scrollLeft == 16
                        ? ($refs.featured.scrollLeft = $refs.featured.scrollWidth)
                        : ($refs.featured.scrollLeft -= ($refs.featured.children[0].offsetWidth + 16) / 2 + 2)
                "
                >
                    <i class="{{ \config('other.font-awesome') }} fa-angle-left"></i>
                </button>
                <button
                    class="featured-carousel__next"
                    x-on:click="
                    $refs.featured.scrollLeft == $refs.featured.scrollWidth - $refs.featured.offsetWidth - 16
                        ? ($refs.featured.scrollLeft = 0)
                        : ($refs.featured.scrollLeft += ($refs.featured.children[0].offsetWidth + 16) / 2 + 2)
                "
                >
                    <i class="{{ \config('other.font-awesome') }} fa-angle-right"></i>
                </button>
            </nav>
        </div>
    </section>
@endif
