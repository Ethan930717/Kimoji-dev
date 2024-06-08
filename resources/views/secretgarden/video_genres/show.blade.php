@extends('layout.default')

@section('title')
    <title>{{ $genre->name }} - {{ config('other.title') }}</title>
@endsection

@section('meta')
    <meta name="description" content="{{ $genre->name }}" />
@endsection

@section('breadcrumbs')
    <li class="breadcrumb--active">
        <a class="breadcrumb__link" href="{{ route('secretgarden.home') }}">
            {{ __('secretgarden.secretgarden') }}
        </a>
    </li>
    <li class="breadcrumb--active">
        <a class="breadcrumb__link" href="{{ route('secretgarden.video-genres.index') }}">
            {{ __('secretgarden.genres') }}
        </a>
    </li>
    <li class="breadcrumb--active">
        {{ $genre->name }}
    </li>
@endsection

@section('content')
    <section class="panelV2">
        <header class="panel__header">
            <h2 class="panel__heading">{{ $genre->name }}</h2>
        </header>
        {{ $videos->links('partials.pagination') }}
        <div class="panel__body torrent-search--card__results">
            @if ($videos->isNotEmpty())
                <div class="panel__heading-container" style="display: flex; align-items: center; justify-content: space-between;" x-data>
                    <h2 class="panel__heading">
                        {{ __('mediahub.genre-videos') }} ({{ $videos->total() }})
                        <div class="sort-icons" style="display: inline-flex; align-items: center; margin-left: 10px;">
                            <a href="{{ route('secretgarden.video-genre.show', ['id' => $genre->id, 'sort' => 'release_date', 'direction' => $sortDirection === 'asc' ? 'desc' : 'asc']) }}" title="Sort by release date">
                                <i class="fa fa-calendar{{ $sortField === 'release_date' ? ($sortDirection === 'asc' ? ' up' : ' down') : '' }}"></i>
                            </a>
                            <a href="{{ route('secretgarden.video-genre.show', ['id' => $genre->id, 'sort' => 'video_rank', 'direction' => $sortDirection === 'asc' ? 'desc' : 'asc']) }}" title="Sort by rank" style="margin-left: 10px;">
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
            @else
                <p>{{ __('mediahub.no-videos-found') }}</p>
            @endif
        </div>
        {{ $videos->links('partials.pagination') }}
    </section>
@endsection
