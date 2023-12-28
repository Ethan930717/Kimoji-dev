@extends('layout.default')

@section('title')
    <title>{{ __('task.tasks') }} - {{ config('other.title') }}</title>
@endsection

@section('breadcrumbs')
    <li class="breadcrumb--active">
        {{ __('task.tasks') }}
    </li>
@endsection

@section('content')
    @livewire('task-search')
@endsection
