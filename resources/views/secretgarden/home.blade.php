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
                Latest Update: {{ $latestUpdate }}
                <i class="{{ config('other.font-awesome') }} fa-star"></i>
            </div>
            <div style="text-align: center; margin-top: 20px;">
                <button id="show-latest-100" class="glass-button" style="font-size: 20px;">Show Latest 100</button>
            </div>
            <div id="loading-message" style="text-align: center; display: none; margin-top: 20px;">
                Loading...
            </div>
            <div id="latest-100-videos" class="panel__body torrent-search--card__results" style="display: none;">
                <!-- Latest 100 videos will be loaded here -->
            </div>
        </div>
    </section>

    <style>
        .glass-button {
            font-size: 20px;
            padding: 10px 20px;
            background: rgba(255, 255, 255, 0.2);
            border: 1px solid rgba(255, 255, 255, 0.3);
            border-radius: 8px;
            color: #fff;
            backdrop-filter: blur(10px);
            cursor: pointer;
            transition: background 0.3s ease, border 0.3s ease;
        }

        .glass-button:hover {
            background: rgba(255, 255, 255, 0.3);
            border: 1px solid rgba(255, 255, 255, 0.5);
        }
    </style>

    <script>
        document.getElementById('show-latest-100').addEventListener('click', function() {
            const button = this;
            const loadingMessage = document.getElementById('loading-message');
            const latest100VideosContainer = document.getElementById('latest-100-videos');

            button.style.display = 'none';
            loadingMessage.style.display = 'block';

            fetch('{{ route('secretgarden.latest100') }}')
                .then(response => response.json())
                .then(data => {
                    loadingMessage.style.display = 'none';
                    latest100VideosContainer.style.display = 'block';
                    latest100VideosContainer.innerHTML = data.html;
                })
                .catch(error => {
                    loadingMessage.style.display = 'none';
                    button.style.display = 'block';
                    console.error('Error:', error);
                });
        });
    </script>
@endsection
