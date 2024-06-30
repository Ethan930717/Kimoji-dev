@extends('layout.default')

@section('page', 'page__home')

@section('main')
    @include('blocks.news')
    @include('blocks.poll')
    <div id="vue">
        <script src="{{ mix('js/chat.js') }}" crossorigin="anonymous"></script>
        @include('blocks.chat')
    </div>
    @include('blocks.country', ['countries' => $countries])
    @include('blocks.featured')
    @include('blocks.top_torrents')
{{--    @include('blocks.latest_topics')--}}
{{--
    @include('blocks.latest_posts')
--}}
    @include('blocks.online')
@endsection
