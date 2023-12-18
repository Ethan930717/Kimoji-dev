<section class="meta">
    @if (file_exists(public_path().'/files/img/torrent-banner_'.$torrent->id.'.jpg'))
        <img class="meta__backdrop" src="{{ url('/files/img/torrent-banner_'.$torrent->id.'.jpg') }}" alt="Backdrop">
    @endif
    <span class="meta__poster-link">
        <img
            src="{{ file_exists(public_path().'/files/img/torrent-cover_'.$torrent->id.'.jpg') ? url('/files/img/torrent-cover_'.$torrent->id.'.jpg') : 'https://via.placeholder.com/500x500' }}"
            class="meta__poster"
        >
    </span>
        <section class="audio-player">
            <audio controls>
                <source src="{{ asset('sounds/alert.mp3') }}" type="audio/mpeg">
                您的浏览器不支持 audio 元素。
            </audio>
        </section>
        <style>
            .audio-player audio {
                /* 在这里添加您的自定义样式 */
                width: 100%; /* 例如，设置播放器宽度 */
            }
        </style>

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
</section>
