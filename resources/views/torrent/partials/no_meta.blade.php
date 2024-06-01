<section class="meta">
    @if (file_exists(public_path().'/files/img/torrent-banner_'.$torrent->id.'.jpg'))
        <img class="meta__backdrop" src="{{ url('/files/img/torrent-banner_'.$torrent->id.'.jpg') }}" alt="Backdrop">
    @else
        <img class="meta__backdrop" src="{{ url('/files/img/torrent-cover_'.$torrent->id.'.jpg') }}" alt="Backdrop">
    @endif
    <a class="meta__title-link">
        <h1 class="meta__title">
            {{ count($parts = explode('-', $torrent->name)) > 2 ? implode('-', array_slice($parts, 0, 2)) : $torrent->name }}
        </h1>
    </a>
    <a class="meta__poster-link">
        <img
                src="{{ file_exists(public_path().'/files/img/torrent-cover_'.$torrent->id.'.jpg') ? url('/files/img/torrent-cover_'.$torrent->id.'.jpg') : 'https://via.placeholder.com/600x400' }}"
                class="meta__poster"
                data-fancybox="gallery"
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
            <a
                    class="meta-id-tag"
                    title="Internet Movie Database"
                    target="_blank"
            >
                {{ $torrent?->region->name ?? '未知类型' }}
            </a>
        </li>
    </ul>
    @php
        $description = $torrent->description;
        $pattern = '/内容简介\]\[size=16\]\[color=white\]\[center\](.*?)\[\/center\]/s';
        $matches = [];

        if (preg_match($pattern, $description, $matches)) {
            $spoilerContent = html_entity_decode($matches[1]);
        } else {
            $spoilerContent = '';
        }
    @endphp
    <p class="meta__description">{{ $spoilerContent }}</p>

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
