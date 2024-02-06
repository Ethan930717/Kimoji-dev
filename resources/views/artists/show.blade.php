@extends('layout.default')

@section('title')
    <title>{{ $artist->name }} - {{ config('other.title') }}</title>
@endsection

@section('meta')
    <meta name="description" content="Artist biography" />
@endsection

@section('breadcrumbs')
    <li class="breadcrumb-item"><a href="{{ route('home.index') }}">{{ __('home.title') }}</a></li>
    <li class="breadcrumb-item active">{{ __('artists.title') }}</li>
@endsection

@section('content')
    <section class="artist-detail">
        <div class="artist-header">
            <h1 class="artist-name">{{ $artist->name }}</h1>
            <img
                alt="{{ $artist->name }}"
                src="{{ $artist->image_url ? $artist->image_url : 'https://via.placeholder.com/160x240' }}"
                class="artist-image"
            />
        </div>

        <div class="artist-info">
            <h2>{{ __('artists.info') }}</h2>
            <p><strong>{{ __('artists.born') }}:</strong> {{ $artist->birthday ?? __('artists.unknown') }}</p>
            <p><strong>{{ __('artists.died') }}:</strong> {{ $artist->deathday ?? __('artists.unknown') }}</p>
            <p><strong>{{ __('artists.country') }}:</strong> {{ $artist->country ?? __('artists.unknown') }}</p>
            <p><strong>{{ __('artists.label') }}:</strong> {{ $artist->label ?? __('artists.unknown') }}</p>
            <p><strong>{{ __('artists.genre') }}:</strong> {{ $artist->genre ?? __('artists.unknown') }}</p>
            <p><strong>{{ __('artists.biography') }}:</strong> {{ $artist->biography ?? __('artists.nobiography') }}</p>
        </div>
    </section>
@endsection
