@extends('layout.default')

@section('title')
    <title>{{ __('secret.actors') }} - {{ config('other.title') }}</title>
@endsection

@section('meta')
    <meta name="description" content="{{ __('actors.description') }}" />
@endsection

@section('breadcrumbs')
    <li class="breadcrumb--active">
        {{ __('secret.actors') }}
    </li>
@endsection

@section('breadcrumbs')
    <li class="breadcrumbV2">
        <a class="breadcrumb__link" href="{{ route('home.index') }}">
            <i class="{{ config('other.font-awesome') }} fa-home"></i>
        </a>
    </li>
    <li class="breadcrumb--active">
        {{ __('secret.actors') }}
    </li>
@endsection

@section('content')
    @livewire('actor-search')
@endsection
