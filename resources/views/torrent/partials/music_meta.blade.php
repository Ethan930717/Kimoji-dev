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
                    src="{{ file_exists(public_path().'/files/img/torrent-cover_'.$torrent->id.'.jpg') ? url('/files/img/torrent-cover_'.$torrent->id.'.jpg') : 'https://via.placeholder.com/500x500' }}"
                    class="meta__poster"
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
                <li>
                    <a href="{{ route('requests.create', ['title' => rawurlencode($meta?->title ?? '') ?? 'Unknown', 'imdb' => $torrent?->imdb ?? '', 'tmdb' => $meta?->id ?? '']) }}">
                        申请补档
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
                    {{ $torrent?->distributor->name ?? '未知风格' }}
                </a>
            </li>
        </ul>
        @php
            $description = $torrent->mediainfo;
            $pattern = '/\[专辑介绍\](.*?)\[\/spoiler\]/s';
            $matches = [];

            if (preg_match($pattern, $description, $matches)) {
                $spoilerContent = $matches[1]; // 获取[spoiler]标签内的内容
            } else {
                $spoilerContent = '';
            }
        @endphp
        <p class="meta__description">{{ $spoilerContent }}</p>

        @if ($torrent?->music_url)
            <div id="aplayer-container"
                 data-cover="{{ url('img/kimoji-music.webp') }}"
                 data-name="单曲试听"
                 data-artist="Kimoji"
                 data-url= "{{ $torrent?->music_url }}">
                <div id="aplayer" class="aplayer"></div>
            </div>
        @endif
</section>
