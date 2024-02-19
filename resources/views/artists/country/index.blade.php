@extends('layout.default')

@section('title')
    <title>{{ __('artists.country_title') }} - {{ config('other.title') }}</title>
@endsection

@section('meta')
    <meta name="description" content="{{ __('artists.country_description') }}" />
@endsection

@section('breadcrumbs')
    <li class="breadcrumb--active">
        {{ __('artists.country_title') }}
    </li>
@endsection

@section('content')
    <div class="panelV2">
        <div class="panel__heading">
            <h2>{{ __('artists.country_title') }}</h2>
        </div>
        <div class="panel__body">
            <ul class="list">
                @foreach ($countries as $country)
                    <li>
                        <a>
                            {{ $country->country }}
                        </a>
                    </li>
                @endforeach
            </ul>
        </div>
    </div>
@endsection
