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
                src="{{ $actor->image_url ? $actor->image_url : 'https://via.placeholder.com/150x150' }}"
                class="artist-image"
                style="width: 150px; height: 150px; border-radius:16px; object-fit: cover; margin-right: 20px;"
            />
            <div style="flex: 1;">
                <h2 class="artist-name" style="margin: 0;">
                    {{ $actor->name }}
                    ({{ str_replace('_', ' ', ucwords($actor->english_name, '_')) }})
                    @if($actor->nationality)
                        <img
                            src="{{ url('/img/flags/' . strtolower($actor->nationality) . '.png') }}"
                            alt="{{ $actor->nationality }}"
                            style="width: 24px; height: 16px; margin-left: 10px;"
                        />
                    @endif
                </h2>
                @if($actor->birth_date)
                    <p><strong>{{ __('actors.born') }}:</strong> {{ $actor->birth_date }}</p>
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
            </div>
        </div>

        <div class="artist-info" style="width: 100%; margin-top: 20px;">
            @if($actor->description)
                <div style="max-height: 200px; overflow-y: auto;">
                    <p><strong>{{ __('actors.description') }}:</strong> {!! nl2br(e($actor->description)) !!}</p>
                </div>
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
            <div class="panel__body torrent-search--card__results">
                    @foreach ($videos as $video)
                        <li class="featured-carousel__slide">
                            <x-video-card :video="$video" />
                        </li>
                    @endforeach
            </div>
        </section>
    @endif
@endsection
