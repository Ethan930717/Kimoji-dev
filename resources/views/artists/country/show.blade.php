@extends('layout.default')

@section('title')
    <title>{{ $country_name }} - {{ __('artists.title') }} - {{ config('other.title') }}</title>
@endsection

@section('meta')
    <meta name="description" content="{{ $country_name }} />
@endsection

@section('breadcrumbs')
    <li class="breadcrumbV2">
    <a href="{{ route('artists.country.index') }}" class="breadcrumb__link">
        {{ __('artists.country_title') }}
    </a>
    </li>
    <li class="breadcrumb--active">
        {{ $country_name }}
    </li>
@endsection

@section('content')
    @livewire('country-artist-search', ['countryName' => $country_name])
@endsection
