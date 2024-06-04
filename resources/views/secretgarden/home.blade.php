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
            <img class="" src="{{ url('/img/secretgarden.png') }}" style="width: 500px; display: block; margin: 5px auto 20px auto; ">
            <ul class="mediahub-card__list">
                <li class="mediahub-card__list-item">
                    <a href="{{ route('mediahub.country.index') }}" class="mediahub-card">
                        <h2 class="mediahub-card__heading">{{ __('secretgarden.actor') }} Hub</h2>
                        <h3 class="mediahub-card__subheading">
                            {{ $actorsCount }} {{ __('ecretgarden.actors') }}
                        </h3>
                    </a>
                </li>
                <li class="mediahub-card__list-item">
                    <a href="{{ route('artists.index') }}" class="mediahub-card">
                        <h2 class="mediahub-card__heading">{{ __('secretgarden.videos') }} Hub</h2>
                        <h3 class="mediahub-card__subheading">
                            {{ $videosCount }} {{ __('secretgarden.videos') }}
                        </h3>
                    </a>
                </li>
            </ul>
        </div>
    </section>
@endsection
