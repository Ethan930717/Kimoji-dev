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
    <div class="artist-detail-container" style="display: flex; flex-direction: column; align-items: flex-start; flex-wrap: wrap;">
        <div class="artist-header" style="display: flex; align-items: center; width: 100%;">
            <img
                alt="{{ $actor->name }}"
                src="{{ $actor->image_url ? $actor->image_url : 'https://via.placeholder.com/160x240' }}"
                class="artist-image"
                style="width: 150px; height: 150px; border-radius:16px; object-fit: cover; margin-right: 20px;"
            />
            <h2 class="artist-name" style="margin: 0;">{{ $actor->name }}</h2>
        </div>

        <div class="artist-info" style="width: 100%; margin-top: 20px;">
            @if($actor->english_name)
                <p><strong>{{ __('actors.english_name') }}:</strong> {{ str_replace('_', ' ', $actor->english_name) }}</p>
            @endif
            @if($actor->birth_date)
                <p><strong>{{ __('actors.birth_date') }}:</strong> {{ $actor->birth_date }}</p>
            @endif
            @if($actor->zodiac)
                <p><strong>{{ __('actors.zodiac') }}:</strong> {{ $actor->zodiac }}</p>
            @endif
            @if($actor->blood_type)
                <p><strong>{{ __('actors.blood_type') }}:</strong> {{ $actor->blood_type }}</p>
            @endif
            @if($actor->measurements)
                <p><strong>{{ __('actors.measurements') }}:</strong> {{ $actor->measurements }}</p>
            @endif
            @if($actor->birth_place)
                <p><strong>{{ __('actors.birth_place') }}:</strong> {{ $actor->birth_place }}</p>
            @endif
            @if($actor->hobbies_skills)
                <p><strong>{{ __('actors.hobbies_skills') }}:</strong> {{ $actor->hobbies_skills }}</p>
            @endif
            @if($actor->description)
                <div style="max-height: 200px; overflow-y: auto;">
                    <p><strong>{{ __('actors.description') }}:</strong> {!! nl2br(e($actor->description)) !!}</p>
                </div>
            @endif
            @if($actor->nationality)
                <p><strong>{{ __('actors.nationality') }}:</strong> {{ $actor->nationality }}</p>
            @endif
        </div>
    </div>

    {{-- 艺术家资源展示 --}}
    @php
        $videos = \App\Models\Video::where('actor_id', $actor->id)->get();
    @endphp
    @if ($videos->isNotEmpty())
        <section class="panelV2" style="margin-top: 20px">
            <div class="panel__heading-container" style="display: flex; align-items: center; justify-content: space-between;" x-data>
                <h2 class="panel__heading">
                    {{ __('actors.artist-videos') }} ({{ $videos->count() }})
                </h2>
            </div>
            <div x-data>
                <ul class="featured-carousel" x-ref="featured">
                    @foreach ($videos as $video)
                        <li class="featured-carousel__slide">
                            <div class="video-card" style="text-align: center;">
                                <img
                                    alt="{{ $video->item_code }}"
                                    src="{{ $video->poster_url ? $video->poster_url : 'https://via.placeholder.com/160x240' }}"
                                    style="width: 140px; height: 200px; object-fit: cover; border-radius: 10px; margin-bottom: 10px;"
                                />
                                <div>{{ $video->item_code }}</div>
                            </div>
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
