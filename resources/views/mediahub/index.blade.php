@extends('layout.default')

@section('title')
    <title>{{ __('mediahub.title') }} - {{ config('other.title') }}</title>
@endsection

@section('meta')
    <meta name="description" content="MediaHub">
@endsection

@section('breadcrumbs')
    <li class="breadcrumb--active">
        {{ __('mediahub.title') }}
    </li>
@endsection

@section('main')
    <section class="panelV2">
        <div class="panel__body">
            <img class="" src="{{ url('/img/tmdb_long.svg') }}" style="width: 500px; display: block; margin: 5px auto 20px auto; ">
            <ul class="mediahub-card__list">
                <li class="mediahub-card__list-item">
                    <a href="{{ route('mediahub.country.index') }}" class="mediahub-card">
                        <h2 class="mediahub-card__heading">{{ __('artists.country') }} Hub</h2>
                        <h3 class="mediahub-card__subheading">
                            {{ $countriesCount }} {{ __('artists.country') }}
                        </h3>
                    </a>
                </li>
                <li class="mediahub-card__list-item">
                    <a href="{{ route('artists.index') }}" class="mediahub-card">
                        <h2 class="mediahub-card__heading">{{ __('artists.title') }} Hub</h2>
                        <h3 class="mediahub-card__subheading">
                            {{ $artistsCount }} {{ __('artists.title') }}
                        </h3>
                    </a>
                </li>
            </ul>
        </div>
    </section>
@endsection
