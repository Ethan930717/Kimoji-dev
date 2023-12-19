<section class="meta">
    @if (file_exists(public_path().'/files/img/torrent-banner_'.$torrent->id.'.jpg'))
        <img class="meta__backdrop" src="{{ url('/files/img/torrent-banner_'.$torrent->id.'.jpg') }}" alt="Backdrop">
    @endif
        <a class="meta__title-link">
            <h1 class="meta__title">
                {{ $torrent?->name}}
            </h1>
        </a>
        <a class="meta__poster-link">
            <img
                    src="{{ file_exists(public_path().'/files/img/torrent-cover_'.$torrent->id.'.jpg') ? url('/files/img/torrent-cover_'.$torrent->id.'.jpg') : 'https://via.placeholder.com/500x500' }}"
                    class="meta__poster"
            >
        </a>
        <div class="meta__chips">
            <section class="meta__chip-container">
                @if (isset($torrent->keywords) && $torrent->keywords->isNotEmpty())
                    <article class="meta__keywords">
                        <a class="meta-chip" href="{{ route('torrents.index', ['view' => 'group', 'keywords' => $torrent->keywords->pluck('name')->join(', ')]) }}">
                            <i class="{{ config('other.font-awesome') }} fa-tag meta-chip__icon"></i>
                            <h2 class="meta-chip__name">关键词</h2>
                            <h3 class="meta-chip__value">{{ $torrent->keywords->pluck('name')->join(', ') }}</h3>
                        </a>
                    </article>
                @endif
            </section>
        </div>
        <div id="audio-container" data-sound-src="/sounds/daodai.mp3">
            <input type="range" id="volume-slider" min="0" max="1" step="0.01" value="1">
            <button id="play-button" class="fa fas fa-play"></button> <!-- 使用播放符号 -->
            <button id="pause-button" class="fa fas fa-pause"></button> <!-- 使用暂停符号 -->
            <!-- 音量控制按钮 -->
            <!-- 音量减小 -->
            <button id="volume-decrease" class="fa fas fa-minus"></button>
            <!-- 音量增加 -->
            <button id="volume-increase" class="fa fas fa-plus"></button>
            <!-- 静音 -->
            <button id="mute" class="fa fas fa-volume-mute"></button>
            <!-- 进度条和时间显示 -->
            <div id="progress-container">
                <div id="progress-bar"></div>
            </div>
            <div id="time-display">
                <span id="current-time">0:00</span> / <span id="total-time">0:00</span>
            </div>
            <canvas id="audioCanvas"></canvas>
        </div>
        <div class="meta__actions">
        <a class="meta__dropdown-button" href="#">
            <i class="{{ config('other.font-awesome') }} fa-ellipsis-v"></i>
        </a>
        <ul class="meta__dropdown">
            <li>
                <a href="{{ route('torrents.create', ['category_id' => $category->id, 'title' => rawurlencode($meta->title ?? '') ?? 'Unknown', 'imdb' => $torrent?->imdb ?? '', 'tmdb' => $meta?->id ?? '']) }}">
                    {{ __('common.upload') }}
                </a>
            </li>
            <li>
                <a href="{{ route('requests.create', ['title' => rawurlencode($meta?->title ?? '') ?? 'Unknown', 'imdb' => $torrent?->imdb ?? '', 'tmdb' => $meta?->id ?? '']) }}">
                    申请补档
                </a>
            </li>
        </ul>
    </div>


</section>
