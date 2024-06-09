@extends('layout.default')

@section('title')
    <title>{{ $series->name }} - {{ config('other.title') }}</title>
@endsection

@section('meta')
    <meta name="description" content="{{ $series->name }}" />
@endsection

@section('breadcrumbs')
    <li class="breadcrumb--active">
        <a class="breadcrumb__link" href="{{ route('secretgarden.home') }}">
            {{ __('secretgarden.secretgarden') }}
        </a>
    </li>
    <li class="breadcrumb--active">
        <a class="breadcrumb__link" href="{{ route('secretgarden.video_series.index') }}">
            {{ __('secretgarden.series') }}
        </a>
    </li>
    <li class="breadcrumb--active">
        {{ $series->name }}
    </li>
@endsection

@section('content')
    <section class="panelV2">
        <header class="panel__header">
            <div style="display: flex; align-items: center; margin-top: 10px;" >
                <h2 class="panel__heading">{{ $series->name }} ({{ $videos->total() }})</h2>
                <div style="display: inline-flex; align-items: center; margin-left: 10px;">
                    <a href="{{ route('secretgarden.video_series.show', ['id' => $series->id, 'sort' => 'release_date', 'direction' => $sortDirection === 'asc' ? 'desc' : 'asc']) }}" title="Sort by release date" style="margin-right: 10px;">
                        <i class="fa fa-calendar{{ $sortField === 'release_date' ? ($sortDirection === 'asc' ? ' up' : ' down') : '' }}"></i>
                    </a>
                    <a href="{{ route('secretgarden.video_series.show', ['id' => $series->id, 'sort' => 'video_rank', 'direction' => $sortDirection === 'asc' ? 'desc' : 'asc']) }}" title="Sort by rank">
                        <i class="fa fa-star{{ $sortField === 'video_rank' ? ($sortDirection === 'asc' ? ' up' : ' down') : '' }}"></i>
                    </a>
                </div>
            </div>
        </header>
        {{ $videos->links('partials.pagination') }}
        <div class="panel__body torrent-search--card__results">
            @if ($videos->isNotEmpty())
                @foreach ($videos as $video)
                    <x-video-card :video="$video" />
                @endforeach
            @else
                <p>{{ __('mediahub.no-videos-found') }}</p>
            @endif
        </div>
        {{ $videos->links('partials.pagination') }}
    </section>
@endsection
