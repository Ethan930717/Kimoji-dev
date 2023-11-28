@extends('layout.default')

@section('title')
	<title>Missing Media</title>
@endsection

@section('breadcrumbs')
    <li class="breadcrumb--active">
        补档列表
    </li>
@endsection

@section('main')
    @livewire('missing-media-search')
@endsection
