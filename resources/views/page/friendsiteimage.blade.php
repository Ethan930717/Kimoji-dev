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
    <section class="panelV2" x-data="{ modalOpen: false, currentImage: '', currentSlide: 0, images: @json($images) }">
        <h2 class="panel__heading center-text">
            KIMOJI 画廊今日共计展出作品: {{ count($images) }} 幅
        </h2>

        <div class="stats__panels">
            @foreach ($images as $index => $image)
                <div class="image-container">
                    <section class="panelV2 panel--grid-item">
                        <img class="thumbnail" src="{{ asset($image->url) }}" alt="缩略图" @click="modalOpen = true; currentImage = '{{ asset($image->url) }}'; currentSlide = {{ $index }}">
                    </section>
                    <div class="image-title">{{ pathinfo($image->name, PATHINFO_FILENAME) }}</div>
                </div>
            @endforeach
        </div>

        <div x-data="imageViewer()" class="image-viewer">
            <div class="stats__panels">
                <template x-for="(image, index) in images" :key="index">
                    <div class="image-container">
                        <img :src="image.url" :alt="'Image ' + index" @click="openModal(image.url, index)" class="thumbnail">
                        <div class="image-title" x-text="image.name"></div>
                    </div>
                </template>
            </div>

            <div x-show="modalOpen" class="modal" @click.away="closeModal()">
                <span class="close" @click="closeModal()">&times;</span>
                <img :src="currentImage" class="modal-content">
                <a class="prev" @click="changeSlide(-1)">&#10094;</a>
                <a class="next" @click="changeSlide(1)">&#10095;</a>
            </div>
        </div>

        <script>
            function imageViewer() {
                return {
                    images: <?php echo json_encode($images); ?>,
                    modalOpen: false,
                    currentImage: '',
                    currentSlide: 0,

                    openModal(image, index) {
                        this.currentImage = image;
                        this.currentSlide = index;
                        this.modalOpen = true;
                    },

                    closeModal() {
                        this.modalOpen = false;
                    },

                    changeSlide(direction) {
                        this.currentSlide += direction;
                        if (this.currentSlide >= this.images.length) {
                            this.currentSlide = 0;
                        } else if (this.currentSlide < 0) {
                            this.currentSlide = this.images.length - 1;
                        }
                        this.currentImage = this.images[this.currentSlide].url;
                    }
                }
            }
        </script>

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