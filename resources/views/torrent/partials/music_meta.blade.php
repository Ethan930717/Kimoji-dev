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
        <div class="meta__actions">
            <div id="aplayer-container" data-cover="{{ url('img/kimoji-music.webp') }}">
                <div id="aplayer" class="aplayer"></div>
            </div>
        </div>
        <ul class="meta__ids">
            <li class="meta__imdb">
                <a
                        class="meta-id-tag"
                        title="Internet Movie Database"
                        target="_blank"
                >
                    蓝调爵士
                </a>
            </li>
            @if ($meta->id ?? 0 > 0)
                <li class="meta__tmdb">
                    <a
                            class="meta-id-tag"
                            href="https://www.themoviedb.org/movie/{{ $meta->id }}"
                            title="The Movie Database"
                            target="_blank"
                    >
                        TMDB: {{ $meta->id }}
                    </a>
                </li>
            @endif
        </ul>
        <p class="meta__description">王朝是一个新的具有里程碑意义的系列。这个系列将记录世界上最具标志性的动物的故事，在世界最知名的地点，他们为了维系自己的王朝而努力奋斗着。在每一集里面，他们每个个体的故事都会通过激烈的、引人入胜的戏剧情节展开。</p>

</section>
