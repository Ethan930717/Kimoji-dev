@props([
    'torrent',
    'meta',
])

<article class="torrent-card">
    <header class="torrent-card__header">
        <div class="torrent-card__left-header">
            <span class="torrent-card__category">{{ $torrent->category->name }}</span>
            <span class="torrent-card__meta-seperator">&bull;</span>
            <span class="torrent-card__resolution">
                {{ $torrent->resolution->name ?? '' }}
            </span>
            <span class="torrent-card__meta-seperator"></span>
            <span class="torrent-card__type">{{ $torrent->type->name }}</span>
            <span class="torrent-card__meta-seperator">&bull;</span>
            <span class="torrent-card__size">{{ $torrent->getSize() }}</span>
        </div>
        <div class="torrent-card__right-header">
            <a class="torrent-card__seeds" href="{{ route('peers', ['id' => $torrent->id]) }}">
                <i class="fas fa-arrow-up"></i>
                {{ $torrent->seeders }}
            </a>
            <span class="torrent-card__meta-seperator">&bull;</span>
            <a class="torrent-card__leeches" href="{{ route('peers', ['id' => $torrent->id]) }}">
                <i class="fas fa-arrow-down"></i>
                {{ $torrent->leechers }}
            </a>
            <span class="torrent-card__meta-seperator">&bull;</span>
            <a
                class="torrent-card__completed"
                href="{{ route('history', ['id' => $torrent->id]) }}"
            >
                <i class="fas fa-check"></i>
                {{ $torrent->times_completed }}
            </a>
        </div>
    </header>
    <aside class="torrent-card__aside">
            <figure class="torrent-card__figure">
                <img
                    class="torrent-card__image"
                                @switch(true)
                        @case($torrent->category->movie_meta || $torrent->category->tv_meta)
                            src="{{ isset($meta->poster) ? tmdb_image('poster_mid', $meta->poster) : 'https://via.placeholder.com/160x240' }}"

                            @break
                        @case($torrent->category->game_meta && isset($torrent->meta) && $meta->cover->image_id && $meta->name)
                            src="https://images.igdb.com/igdb/image/upload/t_cover_big/{{ $torrent->meta->cover->image_id }}.jpg"

                            @break
                    @case ($torrent->category->music_meta && file_exists(public_path().'/files/img/torrent-cover_'.$torrent->id.'.jpg'))
                        src="{{ url('files/img/torrent-cover_'.$torrent->id.'.jpg') }}"
                    @break
                        @case ($torrent->category->no_meta && file_exists(public_path().'/files/img/torrent-cover_'.$torrent->id.'.jpg'))
                            src="{{ url('files/img/torrent-cover_'.$torrent->id.'.jpg') }}"
                            @break
                    @endswitch
                    alt="{{ __('torrent.poster') }}"
                />
            </figure>
    </aside>
    <div class="torrent-card__body">
        <h2 class="torrent-card__title">
            <a
                class="torrent-card__link"
                href="{{ route('torrents.show', ['id' => $torrent->id]) }}"
            >
                {{ $torrent->name }}
            </a>
        </h2>
        <div class="torrent-card__rating-and-genres">
            @if (in_array($torrent->category_id, [1, 2]))
                <span
                        class="torrent-card__rating"
                        title="{{ $meta?->vote_average ?? 0 }}/10 ({{ $meta?->vote_count ?? 0 }} {{ __('torrent.votes') }})"
                >
            <i class="{{ config('other.font-awesome') }} fa-star"></i>
            {{ $meta?->vote_average ?? 0 }}
        </span>
                <span class="torrent-card__meta-seperator"> &bull; </span>
                <ul class="torrent-card__genres">
                    @foreach($meta?->genres ?? [] as $genre)
                        <li class="torrent-card__genre-item">
                            <a
                                class="torrent-card__genre"
                                href="{{ route('torrents.index', ['view' => 'group', 'genres' => [$genre->id]]) }}"
                            >
                                {{ $genre->name }}
                            </a>
                        </li>
                    @endforeach
                </ul>
            @elseif ($torrent->category_id == 3)
                <ul class="torrent-card__genres">
                    <li class="torrent-card__genre-item">
                        {{ $torrent->distributor->name ?? '' }}
                        </a>
                    </li>
                </ul>
                @if (!empty($torrent->music_url))
                    @if ($torrent->is_lrc)
                    <li class="torrent-card__genre-item">
                        <a class="torrent-card__genre">
                            <i class="{{ config('other.font-awesome') }} fa-comment-music" title="{{ __('歌词试听') }}"></i>
                        </a>
                    </li>
                    @else
                    <li class="torrent-card__genre-item">
                        <a class="torrent-card__genre">
                            <i class="{{ config('other.font-awesome') }} fa-headphones-alt" title="{{ __('提供试听') }}"></i>
                        </a>
                    </li>
                    @endif
                @endif

     </span>
            @elseif ($torrent->category_id == 4)
                <ul class="torrent-card__genres">
                    <li class="torrent-card__genre-item">
                        {{ $torrent->region->name ?? '' }}
                        </a>
                    </li>
                </ul>
                @if (!empty($torrent->music_url))
                    @if ($torrent->is_lrc)
                        <li class="torrent-card__genre-item">
                            <a class="torrent-card__genre">
                                <i class="{{ config('other.font-awesome') }} fa-comment-music" title="{{ __('歌词试听') }}"></i>
                            </a>
                        </li>
                    @else
                        <li class="torrent-card__genre-item">
                            <a class="torrent-card__genre">
                                <i class="{{ config('other.font-awesome') }} fa-headphones-alt" title="{{ __('提供试听') }}"></i>
                            </a>
                        </li>
                    @endif
                @endif
            @endif
        </div>
        <p class="torrent-card__plot">
            @if (in_array($torrent->category_id, [1, 2]))
                {{ Str::limit(strip_tags($meta?->overview ?: $meta?->summary), 350, '...') }}
            @elseif (in_array($torrent->category_id, [3, 4]))
                @php
                    $description = $torrent->description;
                    $pattern = '/专辑介绍\]\[size=16\]\[color=white\]\[center\](.*?)\[\/center\]/s';
                    $matches = [];

                    if (preg_match($pattern, $description, $matches)) {
                        $spoilerContent = html_entity_decode($matches[1]);
                    } else {
                        $spoilerContent = '';
                    }
                @endphp
                {{ Str::limit(strip_tags($spoilerContent), 350, '...') }}
            @endif

        </p>
    </div>
    <footer class="torrent-card__footer">
        <div class="torrent-card__left-footer">
            <address class="torrent-card__uploader">
                <x-user_tag :anon="$torrent->anon" :user="$torrent->user" />
            </address>
            <span class="torrent-card__meta-seperator">&bull;</span>
            <time title="{{ $torrent->created_at }}" datetime="{{ $torrent->created_at }}">
                {{ $torrent->created_at->diffForHumans() }}
            </time>
        </div>
        <div class="torrent-card__right-footer">
            @if (config('torrent.download_check_page'))
                <a
                    class="form__standard-icon-button"
                    href="{{ route('download_check', ['id' => $torrent->id]) }}"
                >
                    <i class="{{ \config('other.font-awesome') }} fa-download"></i>
                </a>
            @else
                <a
                    class="form__standard-icon-button"
                    href="{{ route('download', ['id' => $torrent->id]) }}"
                >
                    <i class="{{ \config('other.font-awesome') }} fa-download"></i>
                </a>
            @endif
        </div>
    </footer>
</article>
