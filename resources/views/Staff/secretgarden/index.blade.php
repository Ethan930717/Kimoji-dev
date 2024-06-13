@extends('layout.default')

@section('title')
    <title>Secret Garden Logs - {{ __('staff.staff-dashboard') }} - {{ config('other.title') }}</title>
@endsection

@section('meta')
    <meta name="description" content="Secret Garden Logs - {{ __('staff.staff-dashboard') }}" />
@endsection

@section('breadcrumbs')
    <li class="breadcrumbV2">
        <a href="{{ route('staff.dashboard.index') }}" class="breadcrumb__link">
            {{ __('staff.staff-dashboard') }}
        </a>
    </li>
    <li class="breadcrumb--active">
        {{ __('secret_garden.logs') }}
    </li>
@endsection

@section('page', 'page__secret-garden-log--index')

@section('main')
    @livewire('secret-garden-log-search')
@endsection
