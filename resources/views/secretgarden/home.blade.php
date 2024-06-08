<!-- resources/views/secretgarden.blade.php -->
@extends('layout.default')

@section('main')
    <section class="panelV2">
        <div class="panel__body">
            <img src="{{ url('/img/secretgarden.png') }}" style="width: 500px; display: block; margin: 5px auto 20px auto; ">
            <ul class="mediahub-card__list">
                <li class="mediahub-card__list-item">
                    <a href="{{ route('secretgarden.actor.index') }}" class="mediahub-card">
                        <h2 class="mediahub-card__heading">{{ __('secretgarden.actors') }} Hub</h2>
                        <h3>{{ $actorsCount }} {{ __('secretgarden.actors') }}</h3>
                    </a>
                </li>
                <li class="mediahub-card__list-item">
                    <a href="{{ route('secretgarden.video.index') }}" class="mediahub-card">
                        <h2 class="mediahub-card__heading">{{ __('secretgarden.videos') }} Hub</h2>
                        <h3>{{ $videosCount }} {{ __('secretgarden.videos') }}</h3>
                    </a>
                </li>
                <li class="mediahub-card__list-item">
                    <a href="{{ route('secretgarden.video_genres.index') }}" class="mediahub-card">
                        <h2 class="mediahub-card__heading">{{ __('secretgarden.genres') }} Hub</h2>
                        <h3>{{ $genresCount }} {{ __('secretgarden.genres') }}</h3>
                    </a>
                </li>
            </ul>
        </div>
    </section>
@endsection
