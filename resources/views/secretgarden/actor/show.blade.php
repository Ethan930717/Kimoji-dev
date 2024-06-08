@extends('layout.default')

@section('title')
    <title>{{ $actor->name }} - {{ config('other.title') }}</title>
@endsection

@section('meta')
    <meta name="description" content="Artist biography" />
@endsection

@section('breadcrumbs')
    <li class="breadcrumb--active">
        <a class="breadcrumb__link" href="{{ route('secretgarden.home') }}">
            {{ __('secretgarden.secretgarden') }}
        </a>
    </li>
    <li class="breadcrumb--active">
        <a class="breadcrumb__link" href="{{ route('secretgarden.actor.index') }}">
            {{ __('secretgarden.actors') }}
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
                    <p><strong>{{ __('actors.zodiac') }}:</strong>
                        {{ $actor->zodiac }}
                        @switch($actor->zodiac)
                            @case('Aries')
                                <i class="fa fa-ram"></i>
                                @break
                 ho'me           @case('Taurus')
                                <i class="fa fa-cow"></i>
                                @break
                            @case('Gemini')
                                <i class="fa fa-twins"></i>
                                @break
                            @case('Cancer')
                                <i class="fa fa-crab"></i>
                                @break
                            @case('Leo')
                                <i class="fa fa-lion"></i>
                                @break
                            @case('Virgo')
                                <i class="fa fa-maiden"></i>
                                @break
                            @case('Libra')
                                <i class="fa fa-balance-scale"></i>
                                @break
                            @case('Scorpio')
                                <i class="fa fa-scorpion"></i>
                                @break
                            @case('Sagittarius')
                                <i class="fa fa-archer"></i>
                                @break
                            @case('Capricorn')
                                <i class="fa fa-goat"></i>
                                @break
                            @case('Aquarius')
                                <i class="fa fa-water"></i>
                                @break
                            @case('Pisces')
                                <i class="fa fa-fish"></i>
                                @break
                        @endswitch
                    </p>
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
    @if ($videos->isNotEmpty())
        <section class="panelV2" style="margin-top: 20px">
            <div class="panel__heading-container" style="display: flex; align-items: center; justify-content: space-between;" x-data>
                <h2 class="panel__heading">
                    {{ __('actors.artist-videos') }} ({{ $videos->count() }})
                    <div class="sort-icons" style="display: inline-flex; align-items: center; margin-left: 10px;">
                        <a href="{{ route('secretgarden.actor.show', ['id' => $actor->id, 'sort' => 'release_date', 'direction' => $sortDirection === 'asc' ? 'desc' : 'asc']) }}" title="Sort by release date">
                            <i class="fa fa-calendar{{ $sortField === 'release_date' ? ($sortDirection === 'asc' ? ' up' : ' down') : '' }}"></i>
                        </a>
                        <a href="{{ route('secretgarden.actor.show', ['id' => $actor->id, 'sort' => 'video_rank', 'direction' => $sortDirection === 'asc' ? 'desc' : 'asc']) }}" title="Sort by rank" style="margin-left: 10px;">
                            <i class="fa fa-star{{ $sortField === 'video_rank' ? ($sortDirection === 'asc' ? ' up' : ' down') : '' }}"></i>
                        </a>
                    </div>
                </h2>
            </div>
            <div class="panel__body torrent-search--card__results">
                @foreach ($videos as $video)
                    <x-video-card :video="$video" />
                @endforeach
            </div>
        </section>
    @endif
@endsection
