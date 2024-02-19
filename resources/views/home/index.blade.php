@extends('layout.default')

@section('page', 'page__home')

@section('main')
    @include('blocks.news')
    @include('artists.country.list')
    @include('blocks.featured')
    @include('blocks.poll')
    @include('blocks.top_torrents')
{{--    @include('blocks.latest_topics')--}}
    @include('blocks.latest_posts')
    @include('blocks.online')
@endsection
