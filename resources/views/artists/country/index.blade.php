@extends('layout.default')

@section('title')
    <title>{{ __('artists.country') }}</title>
@endsection

@section('breadcrumbs')
    <li class="breadcrumb--active">
    {{ __('artists.country') }}
    </li>
@endsection

@section('content')
    @livewire('country-search')
@endsection
