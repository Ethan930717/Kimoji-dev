@extends('layout.default')

@section('page', 'page__home')

@section('main')
    @include('blocks.news')
    @include('blocks.featured')
    @include('blocks.poll')
    @include('blocks.top_torrents')
    @include('blocks.online')
@endsection
