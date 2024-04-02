@extends('layout.default')

@section('breadcrumbs')
    <li class="breadcrumbV2">
        <a>
            社群
        </a>
    </li>
    <li class="breadcrumb--active">
        茶室
    </li>
@endsection

@section('page', 'page__page--chatroom')

@section('main')
    <div id="vue">
        <script src="{{ mix('js/chat.js') }}" crossorigin="anonymous"></script>
        @include('blocks.chat')
    </div>
@endsection
