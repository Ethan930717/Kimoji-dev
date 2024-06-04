@extends('layout.default')

@section('title')
    <title>{{ $video->title }} - {{ config('other.title') }}</title>
@endsection

@section('meta')
    <meta name="description" content="Video detail page" />
@endsection

@section('breadcrumbs')
    <li class="breadcrumb--active">
        <a class="breadcrumb__link" href="{{ route('secretgarden.home') }}">
            {{ __('secretgarden.secretgarden') }}
        </a>
    </li>
    <li class="breadcrumb--active">
        <a class="breadcrumb__link" href="{{ route('secretgarden.video.index') }}">
            {{ __('secretgarden.videos') }}
        </a>
    </li>
    <li class="breadcrumb--active">
        {{ $video->title }}
    </li>
@endsection

@section('content')
    <div class="video-detail-container" style="display: flex; justify-content: space-between; align-items: flex-start; flex-wrap: wrap;">
        <div class="video-header" style="flex: 1; min-width: 500px;">
            <img
                alt="{{ $video->title }}"
                src="/secretgarden/poster/{{ $video->poster_url }}"
                class="video-poster"
                style="width: 500px; height: auto; border-radius:16px; object-fit: contain;"
            />
        </div>

        <div class="video-info" style="flex: 2; min-width: 300px; margin-left: 40px;">
            <div class="video-header" style="display: flex; align-items: center; min-width: 300px;">
                <h2 class="video-title" style="margin: 0;">{{ $video->title }}</h2>
            </div>
            @if($video->actor_name)
                <p><strong>{{ $video->actor_name }} - {{ $video->item_number }}</strong></p>
            @endif
            @if($video->release_date)
                <p><strong>{{ __('secretgarden.release_date') }}:</strong> {{ $video->release_date }}</p>
            @endif
            @if($video->video_rank)
                <p><strong>{{ __('secretgarden.rank') }}:</strong> {{ $video->video_rank }}</p>
            @endif
            @if($video->duration)
                <p><strong>{{ __('secretgarden.duration') }}:</strong> {{ $video->duration }}</p>
            @endif
            @if($video->director)
                <p><strong>{{ __('secretgarden.director') }}:</strong> {{ $video->director }}</p>
            @endif
            @if($video->series)
                <p><strong>{{ __('secretgarden.series') }}:</strong> {{ $video->series }}</p>
            @endif
            @if($video->maker)
                <p><strong>{{ __('secretgarden.maker') }}:</strong> {{ $video->maker }}</p>
            @endif
            @if($video->label)
                <p><strong>{{ __('secretgarden.label') }}:</strong> {{ $video->label }}</p>
            @endif
            @if($video->genres)
                <p><strong>{{ __('secretgarden.genres') }}:</strong> {{ $video->genres }}</p>
            @endif
            @if($video->tags)
                <p><strong>{{ __('secretgarden.tags') }}:</strong> {{ $video->tags }}</p>
            @endif
            @if($video->description)
                <div style="max-height: 200px; overflow-y: auto;">
                    <p><strong>{{ __('secretgarden.description') }}:</strong> {!! nl2br(e($video->description)) !!}</p>
                </div>
            @endif
        </div>
    </div>

    {{-- 视频截图展示 --}}
    @if (!empty($video->video_images))
        @php
            $image_urls = explode(';', $video->video_images);
            $image_count = count($image_urls);
        @endphp
        <section class="panelV2" style="margin-top: 20px">
            <div class="panel__heading-container" style="display: flex; align-items: center; justify-content: space-between;" x-data>
                <h2 class="panel__heading">
                    {{ __('secretgarden.video-images') }} ({{ $image_count }})
                </h2>
            </div>
            <div x-data>
                <ul class="featured-carousel" x-ref="featured" style="display: flex; overflow-x: auto; gap: 16px;">
                    @foreach ($image_urls as $image_url)
                        @php
                            $image_name = basename($image_url);
                        @endphp
                        <li class="featured-carousel__slide" style="flex: 0 0 auto;">
                            <figure class="video-screenshot" style="margin: 0;">
                                <img
                                    class="video-screenshot__image"
                                    src="{{ url('/secretgarden/images/' . $image_name) }}"
                                    alt="{{ $image_name }}"
                                    style="height: 300px; object-fit: contain;"
                                    data-fancybox="gallery"
                                />
                            </figure>
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
@endsection
