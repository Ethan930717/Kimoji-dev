@extends('layout.default')

@section('breadcrumbs')
@endsection

@section('page', 'page__page--chatroom')

@section('main')
    <div id="vue">
        <script src="{{ mix('js/chat.js') }}" crossorigin="anonymous"></script>
        @include('blocks.chat')
    </div>
@endsection
