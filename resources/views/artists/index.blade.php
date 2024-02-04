@extends('layout.default')

@section('title')
    <title>{{ __('artists.title') }} - {{ config('other.title') }}</title>
@endsection

@section('meta')
    <meta name="description" content="{{ __('artists.description') }}" />
@endsection

@section('breadcrumbs')
    <li class="breadcrumbV2">
        <a href="{{ route('musichub.index') }}" class="breadcrumb__link">
            {{ __('musichub.title') }}
        </a>
    </li>
    <li class="breadcrumb--active">
        {{ __('artists.title') }}
    </li>
@endsection

@section('content')
    @livewire('artist-search')
@endsection
