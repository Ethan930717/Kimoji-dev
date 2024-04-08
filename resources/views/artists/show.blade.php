@extends('layout.default')

@section('title')
    <title>{{ $artist->name }} - {{ config('other.title') }}</title>
@endsection

@section('meta')
    <meta name="description" content="Artist biography" />
@endsection

@section('breadcrumbs')
    <li class="breadcrumb--active">
        <a class="breadcrumb__link" href="{{ route('artists.index') }}">
            {{ __('artists.title') }}
        </a>
    </li>
    <li class="breadcrumb--active">
        {{ $artist->name }}
    </li>
@endsection

@section('content')
    <div class="artist-detail-container" style="display: flex; justify-content: space-between; align-items: flex-start; flex-wrap: wrap;">
        <div class="artist-header" style="flex: 1; min-width: 500px;">
            <img
                alt="{{ $artist->name }}"
                src="{{ $artist->image_url ? $artist->image_url : 'https://via.placeholder.com/160x240' }}"
                class="artist-image"
                style="width: 500px; height: 500px; border-radius:16px; object-fit: cover;"
            />
        </div>

        <div class="artist-info" style="flex: 2;min-width: 300px;margin-left: 40px;">
            <div class="artist-header" style="display: flex; align-items: center; min-width: 300px;">
                {{ $artist->name }} ({{ preg_replace('/\d/', '', str_replace('_', ' ', $artist->english_name)) }})
                <a href="{{ route('artists.edit', $artist->id) }}" class="form__button form__button--outlined" style="margin-left: 10px; text-decoration: none;">Edit</a>
            </div>
            @if($artist->birth_date)
                <p><strong>{{__('artists.born') }}:</strong> {{ $artist->birth_date }}</p>
            @endif
            @if($artist->zodiac)
                <p><strong>{{__('artists.zodiac')}}:</strong> {{ $artist->zodiac }}</p>
            @endif
            @if($artist->blood_type)
                <p><strong>{{ __('artists.blood_type') }}:</strong> {{ $artist->blood_type }}</p>
            @endif
            @if($artist->measurements)
                <p><strong>{{ __('artists.measurements') }}:</strong> {{ $artist->measurements }}</p>
            @endif
            @if($artist->birth_place)
                <p><strong>{{ __('artists.birth_place') }}:</strong> {{ $artist->birth_place }}</p>
            @endif
            @if($artist->hobbies_skills)
                <p><strong>{{ __('artists.hobbies_skills') }}:</strong> {{ $artist->hobbies_skills }}</p>
            @endif
            @if($artist->description)
                <div style="max-height: 200px; overflow-y: auto;">
                    <p><strong>{{ __('artists.description') }}:</strong> {!! nl2br(e($artist->description)) !!}</p>
                </div>
            @else
                <p><strong>{{ __('artists.biography') }}:</strong> {{ __('artists.nobiography') }}</p>
            @endif
        </div>
    </div>

    {{-- 艺术家资源展示 --}}
        @if ($torrents->isNotEmpty())
            <section class="panelV2" style="margin-top: 20px">
                <div class="panel__heading-container" style="display: flex; align-items: center; justify-content: space-between;" x-data>
                    <h2 class="panel__heading">
                        {{ __('artists.artist-torrents') }} ({{ $torrents->count() }})
                    </h2>
                    <a href="{{ route('users.torrent_zip.downloadArtistTorrents', ['user' => auth()->user()->username, 'artistId' => $artist->id]) }}" class="form__button form__button--outlined">
                        <i class="{{ config('other.font-awesome') }} fa-star"></i> Pack Download
                    </a>
                </div>
                <div x-data>
                    <ul class="featured-carousel" x-ref="featured">
                        @foreach ($torrents as $torrent)
                            <li class="featured-carousel__slide">
                                <x-torrent.card :torrent="$torrent" />
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

    </section>
@endsection
