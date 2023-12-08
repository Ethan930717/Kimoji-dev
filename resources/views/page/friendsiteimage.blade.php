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
        <h2 class="panel__heading center-text">KIMOJI 画廊</h2>
    </section>
    <div class="stats__panels">
        @foreach ($images as $index => $image)
            <div class="image-container">
                <section class="panelV2 panel--grid-item">
                    <img class="thumbnail" src="{{ asset($image->url) }}" alt="缩略图" onclick="openModal(this, {{ $index }})">
                </section>
                <div class="image-title">{{ pathinfo($image->name, PATHINFO_FILENAME) }}</div>
            </div>
        @endforeach
    </div>

    <div id="myModal" class="modal">
        <span class="close" onclick="closeModal()">&times;</span>
        <img class="modal-content" id="img01">
        <a class="prev" onclick="plusSlides(-1)">&#10094;</a>
        <a class="next" onclick="plusSlides(1)">&#10095;</a>
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
            opacity: 0.7;
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