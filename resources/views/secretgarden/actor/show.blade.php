@extends('layout.default')

@section('title')
    <title>{{ $actor->name }} - {{ config('other.title') }}</title>
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
        {{ $actor->name }}
    </li>
@endsection

@section('content')
    <div class="artist-detail-container" style="display: flex; justify-content: space-between; align-items: flex-start; flex-wrap: wrap;">
        <div class="artist-header" style="flex: 1; min-width: 500px;">
            <img
                alt="{{ $actor->name }}"
                src="{{ $actor->image_url ? $actor->image_url : 'https://via.placeholder.com/160x240' }}"
                class="artist-image"
                style="width: 500px; height: 500px; border-radius:16px; object-fit: cover;"
            />
        </div>

        <div class="artist-info" style="flex: 2;min-width: 300px;margin-left: 40px;">
            <div class="artist-header" style="display: flex; align-items: center; min-width: 300px;">
                <h2 class="artist-name" style="margin: 0;">{{ $actor->name }}</h2>
            </div>
            @if($actor->birth_date)
                <p><strong>{{ __('actor.born') }}:</strong> {{ $actor->birth_date }}</p>
            @endif
            @if($actor->zodiac)
                <p><strong>{{ __('actor.zodiac') }}:</strong> {{ $actor->zodiac }}</p>
            @endif
            @if($actor->blood_type)
                <p><strong>{{ __('actor.blood_type') }}:</strong> {{ $actor->blood_type }}</p>
            @endif
            @if($actor->measurements)
                <p><strong>{{ __('actor.measurements') }}:</strong> {{ $actor->measurements }}</p>
            @endif
            @if($actor->birth_place)
                <p><strong>{{ __('artists.birth_place') }}:</strong> {{ $actor->birth_place }}</p>
            @endif
            @if($actor->hobbies_skills)
                <p><strong>{{ __('artists.hobbies_skills') }}:</strong> {{ $actor->hobbies_skills }}</p>
            @endif
            @if($actor->description)
                <div style="max-height: 200px; overflow-y: auto;">
                    <p><strong>{{ __('artists.description') }}:</strong> {!! nl2br(e($actor->description)) !!}</p>
                </div>
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
                    <a href="{{ route('users.torrent_zip.downloadArtistTorrents', ['user' => auth()->user()->username, 'artistId' => $actor->id]) }}" class="form__button form__button--outlined">
                        <i class="{{ config('other.font-awesome') }} fa-star"></i> Download
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
