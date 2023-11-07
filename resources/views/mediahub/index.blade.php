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
            <div class="panel__body" style="text-align: center">
                <img class="" src="/img/tmdb_long.svg" style="width: 350px;margin-bottom:15px;margin-top:15px">
            </div>
        <div class="panel__body blocks" style="justify-content: center;">
            <a href="{{ route('mediahub.shows.index') }}" class="">
                <div class="movie media_blocks" style="text-shadow:0 0 5px #fff, 0 0 10px #fff, 0 0 15px #7eeeee;
background-color: rgba(255, 255, 255, 0);border-radius: 20px;    backdrop-filter: blur(3px);
    box-shadow: rgba(0, 0, 0, 0.3) 2px 8px 8px;border: 2px rgba(255,255,255,0.4) solid;border-bottom: 2px rgba(40,40,40,0.35) solid;border-right: 2px rgba(40,40,40,0.35) solid;">
                    <h2>{{ __('mediahub.shows') }} Hub</h2>
                    <span style="background-color: #01d277;"></span>
                    <h2 style="font-size: 12px;">{{ $tv }} {{ __('mediahub.shows') }}</h2>
                </div>
            </a>
            <a href="{{ route('torrents.index', ['view' => 'group', 'categories' => $movieCategoryIds]) }}" class="">
                <div class="movie media_blocks" style="text-shadow:0 0 5px #fff, 0 0 10px #fff, 0 0 15px #7eeeee;
background-color: rgba(255, 255, 255, 0);border-radius: 20px;    backdrop-filter: blur(3px);
    box-shadow: rgba(0, 0, 0, 0.3) 2px 8px 8px;border: 2px rgba(255,255,255,0.4) solid;border-bottom: 2px rgba(40,40,40,0.35) solid;border-right: 2px rgba(40,40,40,0.35) solid;">
                    <h2>{{ __('mediahub.movies') }} Hub</h2>
                    <span style="background-color: #01d277;"></span>
                    <h2 style="font-size: 12px;">{{ $movies }} {{ __('mediahub.movies') }}</h2>
                </div>
            </a>
            <a href="{{ route('mediahub.collections.index') }}" class="">
                <div class="movie media_blocks" style="text-shadow:0 0 5px #fff, 0 0 10px #fff, 0 0 15px #7eeeee;
background-color: rgba(255, 255, 255, 0);border-radius: 20px;    backdrop-filter: blur(3px);
    box-shadow: rgba(0, 0, 0, 0.3) 2px 8px 8px;border: 2px rgba(255,255,255,0.4) solid;border-bottom: 2px rgba(40,40,40,0.35) solid;border-right: 2px rgba(40,40,40,0.35) solid;">
                    <h2>{{ __('mediahub.collections') }} Hub</h2>
                    <span style="background-color: #01d277;"></span>
                    <h2 style="font-size: 12px;">{{ $collections }} {{ __('mediahub.collections') }}</h2>
                </div>
            </a>
            <a href="{{ route('mediahub.persons.index') }}" class="">
                <div class="movie media_blocks" style="text-shadow:0 0 5px #fff, 0 0 10px #fff, 0 0 15px #7eeeee;
background-color: rgba(255, 255, 255, 0);border-radius: 20px;    backdrop-filter: blur(3px);
    box-shadow: rgba(0, 0, 0, 0.3) 2px 8px 8px;border: 2px rgba(255,255,255,0.4) solid;border-bottom: 2px rgba(40,40,40,0.35) solid;border-right: 2px rgba(40,40,40,0.35) solid;">
                    <h2>{{ __('mediahub.persons') }} Hub</h2>
                    <span style="background-color: #01d277;"></span>
                    <h2 style="font-size: 12px;">{{ $persons }} {{ __('mediahub.persons') }}</h2>
                </div>
            </a>
            <a href="{{ route('mediahub.genres.index') }}" class="">
                <div class="movie media_blocks" style="text-shadow:0 0 5px #fff, 0 0 10px #fff, 0 0 15px #7eeeee;
background-color: rgba(255, 255, 255, 0);border-radius: 20px;    backdrop-filter: blur(3px);
    box-shadow: rgba(0, 0, 0, 0.3) 2px 8px 8px;border: 2px rgba(255,255,255,0.4) solid;border-bottom: 2px rgba(40,40,40,0.35) solid;border-right: 2px rgba(40,40,40,0.35) solid;">
                    <h2>{{ __('mediahub.genres') }} Hub</h2>
                    <span style="background-color: #01d277;"></span>
                    <h2 style="font-size: 12px;">{{ $genres }} {{ __('mediahub.genres') }}</h2>
                </div>
            </a>
            <a href="{{ route('mediahub.networks.index') }}" class="">
                <div class="movie media_blocks" style="text-shadow:0 0 5px #fff, 0 0 10px #fff, 0 0 15px #7eeeee;
background-color: rgba(255, 255, 255, 0);border-radius: 20px;    backdrop-filter: blur(3px);
    box-shadow: rgba(0, 0, 0, 0.3) 2px 8px 8px;border: 2px rgba(255,255,255,0.4) solid;border-bottom: 2px rgba(40,40,40,0.35) solid;border-right: 2px rgba(40,40,40,0.35) solid;">
                    <h2>{{ __('mediahub.networks') }} Hub</h2>
                    <span style="background-color: #01d277;"></span>
                    <h2 style="font-size: 12px;">{{ $networks }} {{ __('mediahub.networks') }}</h2>
                </div>
            </a>
            <a href="{{ route('mediahub.companies.index') }}" class="">
                <div class="movie media_blocks" style="text-shadow:0 0 5px #fff, 0 0 10px #fff, 0 0 15px #7eeeee;
background-color: rgba(255, 255, 255, 0);border-radius: 20px;    backdrop-filter: blur(3px);
    box-shadow: rgba(0, 0, 0, 0.3) 2px 8px 8px;border: 2px rgba(255,255,255,0.4) solid;border-bottom: 2px rgba(40,40,40,0.35) solid;border-right: 2px rgba(40,40,40,0.35) solid;">
                    <h2>{{ __('mediahub.companies') }} Hub</h2>
                    <span style="background-color: #01d277;"></span>
                    <h2 style="font-size: 12px;">{{ $companies }} {{ __('mediahub.companies') }}</h2>
                </div>
            </a>
        </div>
    </section>
@endsection


