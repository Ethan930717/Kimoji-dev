@extends('layout.default')

@section('title')
    <title>{{ __('secretgarden.tags') }} - {{ config('other.title') }}</title>
@endsection

@section('meta')
    <meta name="description" content="{{ __('secretgarden.tags') }}" />
@endsection

@section('breadcrumbs')
    <li class="breadcrumb--active">
        <a class="breadcrumb__link" href="{{ route('secretgarden.home') }}">
            {{ __('secretgarden.secretgarden') }}
        </a>
    </li>
    <li class="breadcrumb--active">
        {{ __('secretgarden.tags') }}
    </li>
@endsection

@section('content')
    @livewire('video-tag-search')
@endsection
