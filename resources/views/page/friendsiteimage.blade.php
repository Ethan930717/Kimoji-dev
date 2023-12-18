@extends('layout.default')

@section('title')
    <title>{{ __('stat.stats') }} - {{ config('other.title') }}</title>
@endsection


@section('nav-tabs')
    <li class="nav-tabV2">
        <a class="nav-tab__link" href="{{ route('clients') }}">
            {{ __('page.blacklist-clients') }}
        </a>
    </li>
    <li class="nav-tabV2">
        <a class="nav-tab__link" href="{{ route('uploaded') }}">
            {{ __('common.users') }}
        </a>
    </li>
    <li class="nav-tabV2">
        <a class="nav-tab__link" href="{{ route('seeded') }}">
            {{ __('torrent.torrents') }}
        </a>
    </li>
    <li class="nav-tabV2">
        <a class="nav-tab__link" href="{{ route('bountied') }}">
            {{ __('request.requests') }}
        </a>
    </li>
    <li class="nav-tabV2">
        <a class="nav-tab__link" href="{{ route('groups') }}">
            {{ __('common.groups') }}
        </a>
    </li>
    <li class="nav-tabV2">
        <a class="nav-tab__link" href="{{ route('languages') }}">
            {{ __('common.languages') }}
        </a>
    </li>
    <li class="nav-tabV2">
        <a class="nav-tab__link" href="{{ route('themes') }}">
            主题
        </a>
    </li>
@endsection

@section('main')

    <section class="panelV2">
        <h2 class="panel__heading center-text">
            KIMOJI 画廊今日共计展出作品: {{ count($images) }} 幅
        </h2>
    </section>
    <div x-data="imageGallery({{$images}})">
        <div class="stats__panels" >
            @foreach ($images as $index => $image)
                <div class="image-container">
                    <section class="panelV2 panel--grid-item">
                        <img class="thumbnail" src="{{ asset($image->url) }}" alt="缩略图" @click="openModal({{ $index }})">
                    </section>
                    <div class="image-title">{{ pathinfo($image->name, PATHINFO_FILENAME) }}</div>
                </div>
            @endforeach
        </div>
        <div id="myModal" class="modal"
             x-show="showModal"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition ease-in duration-300"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0">
            <img :src="images[currentSlide]" class="modal-content" id="img01">
            <span @click="closeModal()" class="close">&times;</span>
            <span @click="changeSlide(-1)" class="prev">&#10094;</span>
            <span @click="changeSlide(1)" class="next">&#10095;</span>
        </div>
    </div>

    <style>
        .panel--grid-item {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            text-align: center;
            position: relative;
        }
        .thumbnail {
            width: 100%; /* 缩略图宽度，等比例缩小 */
            cursor: pointer;
            transition: 0.3s;
        }
        .thumbnail:hover {
            cursor: pointer;
            opacity: 0.8;
        }
        .panelV2 {
            border-radius: 12px;
        }
        .center-text {
            text-align: center;
            font-size: 24px;
            margin-bottom: 0.75em;
        }
        .image-title {
            text-align: center;
            font-size: 16px;
            text-shadow: 0 0 2px #24eb4d;
            z-index: 10;
            position: relative;
            margin-top: 3px;
            font-size: 16px;
        }
        .image-container{
            margin-bottom: 15px;
        }


    </style>

@endsection
