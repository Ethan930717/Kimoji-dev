@extends('layout.default')

@section('page', 'page__home')

@section('main')
    @include('blocks.news')
    @include('blocks.poll')
    @include('blocks.featured')
    @include('blocks.top_torrents')
    @include('blocks.online')
@endsection
