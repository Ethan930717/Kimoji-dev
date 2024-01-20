<section class="meta">
    @if (file_exists(public_path().'/files/img/torrent-banner_'.$torrent->id.'.jpg'))
        <img class="meta__backdrop" src="{{ url('/files/img/torrent-banner_'.$torrent->id.'.jpg') }}" alt="Backdrop">
    @else
        <img class="meta__backdrop" src="{{ url('/files/img/torrent-cover_'.$torrent->id.'.jpg') }}" alt="Backdrop">
    @endif
        @php
            $parts = explode('-', $torrent->name);
            if (count($parts) > 2) {
                array_pop($parts); // 移除最后一部分
                array_pop($parts); // 再次移除，这次是倒数第二部分
                $title = implode('-', $parts);
            } else {
                $title = $torrent->name;
            }
            $singerName = count($parts) > 1 ? trim($parts[0]) : '';
        @endphp
        <a class="meta__title-link">
            <h1 class="meta__title">{{ $title }}</h1>
        </a>
        <a class="meta__poster-link">
            <img
                    src="{{ file_exists(public_path().'/files/img/torrent-cover_'.$torrent->id.'.jpg') ? url('/files/img/torrent-cover_'.$torrent->id.'.jpg') : 'https://via.placeholder.com/500x500' }}"
                    class="meta__poster"
                    @click="openImageModal('{{ file_exists(public_path().'/files/img/torrent-cover_'.$torrent->id.'.jpg') ? url('/files/img/torrent-cover_'.$torrent->id.'.jpg') : 'https://via.placeholder.com/500x500' }}')"
            >

        </a>
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
            </ul>
        </div>
        <ul class="meta__ids">
            <li class="meta__imdb">
                @if ($singerName)
                    <a class="meta-id-tag" href="/torrents?perPage=25&name={{ urlencode($singerName) }}" target="_blank">
                        {{ $singerName }}
                    </a>
                @endif
                <a class="meta-id-tag" title="Internet Movie Database" target="_blank"
                   href="{{ route('torrents.index', ['distributors' => [$torrent->distributor->id]]) }}">
                    {{ $torrent?->distributor->name ?? '未知风格' }}
                </a>
            </li>
        </ul>        @php
            $description = $torrent->description;
            $pattern = '/专辑介绍\]\[size=16\]\[color=white\]\[center\](.*?)\[\/center\]/s';
            $matches = [];

            if (preg_match($pattern, $description, $matches)) {
                $spoilerContent = html_entity_decode($matches[1]);
            } else {
                $spoilerContent = '';
            }
        @endphp
        <p class="meta__description">
            {{ mb_strlen($spoilerContent) > 200 ? mb_substr($spoilerContent, 0, 200) . '...' : $spoilerContent }}
        </p>
        @php
            $musicUrl = $torrent?->music_url;
            $lrcUrl = null;
        @endphp

        @if ($musicUrl)
            @php
                $lrcUrl = preg_replace('/\.[^.]+$/', '.lrc', $musicUrl);
            @endphp
        @endif

        <div id="aplayer-container"
             data-cover="{{ url('img/kimoji-music.webp') }}"
             data-name="单曲试听"
             data-artist="Kimoji"
             data-url="{{ $musicUrl }}"
             data-lrc="{{ $lrcUrl }}">
            <div id="aplayer" class="aplayer"></div>
        </div>
</section>
