@extends('layout.default')

@section('title')
    <title>{{ __('secretgarden.secretgarden') }} - {{ config('other.title') }}</title>
@endsection

@section('meta')
    <meta name="description" content="MediaHub">
@endsection

@section('breadcrumbs')
    <li class="breadcrumb--active">
        {{ __('secretgarden.secretgarden') }}
    </li>
@endsection

@section('main')
    <section class="panelV2">
        <div class="panel__body">
            <img class="" src="{{ url('/img/secretgarden.png') }}" style="width: 500px; display: block; margin: 5px auto 20px auto; ">
            <ul class="mediahub-card__list">
                <li class="mediahub-card__list-item">
                    <a href="{{ route('secretgarden.actor.index') }}" class="mediahub-card">
                        <h2 class="mediahub-card__heading">{{ __('secretgarden.actors') }} Hub</h2>
                        <h3 class="mediahub-card__subheading">
                            {{ $actorsCount }} {{ __('secretgarden.actors') }}
                        </h3>
                    </a>
                </li>
                <li class="mediahub-card__list-item">
                    <a href="{{ route('secretgarden.video.index') }}" class="mediahub-card">
                        <h2 class="mediahub-card__heading">{{ __('secretgarden.videos') }} Hub</h2>
                        <h3 class="mediahub-card__subheading">
                            {{ $videosCount }} {{ __('secretgarden.videos') }}
                        </h3>
                    </a>
                </li>
                <li class="mediahub-card__list-item">
                    <a href="{{ route('secretgarden.video_genres.index') }}" class="mediahub-card">
                        <h2 class="mediahub-card__heading">{{ __('secretgarden.genres') }} Hub</h2>
                        <h3 class="mediahub-card__subheading">
                            {{ $genresCount }} {{ __('secretgarden.genres') }}
                        </h3>
                    </a>
                </li>
                <li class="mediahub-card__list-item">
                    <a href="{{ route('secretgarden.video_tags.index') }}" class="mediahub-card">
                        <h2 class="mediahub-card__heading">{{ __('secretgarden.tags') }} Hub</h2>
                        <h3 class="mediahub-card__subheading">
                            {{ $tagsCount }} {{ __('secretgarden.tags') }}
                        </h3>
                    </a>
                </li>
                <li class="mediahub-card__list-item">
                    <a href="{{ route('secretgarden.video_makers.index') }}" class="mediahub-card">
                        <h2 class="mediahub-card__heading">{{ __('secretgarden.makers') }} Hub</h2>
                        <h3 class="mediahub-card__subheading">
                            {{ $makersCount }} {{ __('secretgarden.makers') }}
                        </h3>
                    </a>
                </li>
                <li class="mediahub-card__list-item">
                    <a href="{{ route('secretgarden.video_labels.index') }}" class="mediahub-card">
                        <h2 class="mediahub-card__heading">{{ __('secretgarden.labels') }} Hub</h2>
                        <h3 class="mediahub-card__subheading">
                            {{ $labelsCount }} {{ __('secretgarden.labels') }}
                        </h3>
                    </a>
                </li>
                <li class="mediahub-card__list-item">
                    <a href="{{ route('secretgarden.video_series.index') }}" class="mediahub-card">
                        <h2 class="mediahub-card__heading">{{ __('secretgarden.series') }} Hub</h2>
                        <h3 class="mediahub-card__subheading">
                            {{ $seriesCount }} {{ __('secretgarden.series') }}
                        </h3>
                    </a>
                </li>
            </ul>
            <div style="text-align: center; margin-top: 20px; text-shadow: 0 0 2px #40b78e; font-weight: bold; font-size: 18px;">
                <i class="{{ config('other.font-awesome') }} fa-star"></i>
                Last Update: {{ $latestUpdate }}
                <i class="{{ config('other.font-awesome') }} fa-star"></i>
            </div>
        </div>
    </section>
@endsection
