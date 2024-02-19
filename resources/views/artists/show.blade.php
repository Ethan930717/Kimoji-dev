@extends('layout.default')

@section('title')
    <title>{{ $artist->name }} - {{ config('other.title') }}</title>
@endsection

@section('meta')
    <meta name="description" content="Artist biography" />
@endsection

@section('breadcrumbs')
    <li class="breadcrumb--active">
        {{ __('artists.title') }}
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
                style="width: 500px; height: 500px; object-fit: cover;"
            />
        </div>

        <div class="artist-info" style="flex: 2;min-width: 300px;margin-left: 40px;">
            <div class="artist-header" style="display: flex; align-items: center; min-width: 300px; margin-left: 40px;">
                <h2 class="artist-name" style="margin: 0;">{{ $artist->name }}</h2>
                <a href="{{ route('artists.edit', $artist->id) }}" class="form__button form__button--outlined" style="margin-left: 10px; text-decoration: none;">编辑歌手词条</a>
            </div>
            @if($artist->birthday)
                <p><strong>{{ $artist->member ? __('artists.established') : __('artists.born') }}:</strong> {{ $artist->birthday }}</p>
            @endif
            @if($artist->deathday)
                <p><strong>{{ $artist->member ? __('artists.disbanded') : __('artists.died') }}:</strong> {{ $artist->deathday }}</p>
            @endif
            @if($artist->country)
                <p><strong>{{ __('artists.country') }}:</strong> {{ $artist->country }}</p>
            @endif
            @if($artist->member)
                <p><strong>{{ __('artists.member') }}:</strong> {{ $artist->member }}</p>
            @endif
            @if($artist->label)
                <p><strong>{{ __('artists.label') }}:</strong> {{ $artist->label }}</p>
            @endif
            @if($artist->genre)
                <p><strong>{{ __('artists.genre') }}:</strong> {{ $artist->genre }}</p>
            @endif
            @if($artist->biography)
                <div style="max-height: 200px; overflow-y: auto;">
                    <p><strong>{{ __('artists.biography') }}:</strong> {!! nl2br(e($artist->biography)) !!}</p>
                </div>
            @else
                <p><strong>{{ __('artists.biography') }}:</strong> {{ __('artists.nobiography') }}</p>
            @endif
        </div>
    </div>

    {{-- 艺术家资源展示 --}}
        @if ($torrents->isNotEmpty())
            <section class="panelV2" style="margin-top: 20px">
                <h2 class="panel__heading">
                    {{ __('artists.artist-torrents') }} ({{ $torrents->count() }})
                </h2>
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
