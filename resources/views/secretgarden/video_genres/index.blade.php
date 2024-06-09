@extends('layout.default')

@section('title')
    <title>{{ __('secretgarden.genres') }} - {{ config('other.title') }}</title>
@endsection

@section('meta')
    <meta name="description" content="{{ __('secretgarden.genres') }}" />
@endsection

@section('breadcrumbs')
    <li class="breadcrumb--active">
        <a class="breadcrumb__link" href="{{ route('secretgarden.home') }}">
            {{ __('secretgarden.secretgarden') }}
        </a>
    </li>
    <li class="breadcrumb--active">
        {{ __('secretgarden.genres') }}
    </li>
@endsection

@section('breadcrumbs')
    <li class="breadcrumbV2">
        <a class="breadcrumb__link" href="{{ route('home.index') }}">
            <i class="{{ config('other.font-awesome') }} fa-home"></i>
        </a>
    </li>
    <li class="breadcrumb--active">
        {{ __('secretgarden.genres') }}
    </li>
@endsection

@section('content')
    @livewire('video-genre-search')
@endsection
