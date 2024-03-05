
<tr
    @class([
        'torrent-search--list__row' => auth()->user()->show_poster,
        'torrent-search--list__no-poster-row' => ! auth()->user()->show_poster,
        'torrent-search--list__sticky-row' => $torrent->sticky
    ])
    data-torrent-id="{{ $torrent->id }}"
    data-igdb-id="{{ $torrent->igdb }}"
    data-imdb-id="{{ $torrent->imdb }}"
    data-tmdb-id="{{ $torrent->tmdb }}"
    data-tvdb-id="{{ $torrent->tvdb }}"
    data-mal-id="{{ $torrent->mal }}"
    data-category-id="{{ $torrent->category_id }}"
    data-type-id="{{ $torrent->type_id }}"
    data-type-id="{{ $torrent->resolution_id }}"
>
    @if (auth()->user()->show_poster == 1)
        <td class="torrent-search--list__poster">
            @if ($torrent->category->movie_meta || $torrent->category->tv_meta)
                <a href="{{ route('torrents.similar', ['category_id' => $torrent->category_id, 'tmdb' => $torrent->tmdb]) }}">
                <img
                        src="{{ isset($meta->poster) ? tmdb_image('poster_small', $meta->poster) : 'https://via.placeholder.com/90x135' }}"
                        class="torrent-search--list__poster-img"
                        loading="lazy"
                        alt="{{ __('torrent.poster') }}"
                    >
            @endif
                @if ($torrent->category->game_meta)
                    <img
                        style="height: 80px;"
                        src="{{ isset($meta->cover) ? 'https://images.igdb.com/igdb/image/upload/t_cover_small_2x/'.$meta->cover['image_id'].'.png' : 'https://via.placeholder.com/90x135' }}"
                        class="torrent-search--list__poster-img"
                        loading="lazy"
                        alt="{{ __('torrent.poster') }}"
                    >
                @endif
                    @if ($torrent->category->music_meta)
                        @if(file_exists(public_path().'/files/img/torrent-cover_'.$torrent->id.'.jpg'))
                            <img
                                    src="{{ url('files/img/torrent-cover_'.$torrent->id.'.jpg') }}"
                                    class="torrent-search--list__music_poster-img"
                                    loading="lazy"
                                    alt="{{ __('torrent.poster') }}"
                            >
                        @else
                            <img
                                    src="https://via.placeholder.com/500x500"
                                    class="torrent-search--list__music_poster-img"
                                    loading="lazy"
                                    alt="{{ __('torrent.poster') }}"
                            >
                        @endif
                    @endif
                @if ($torrent->category->no_meta)
                    @if(file_exists(public_path().'/files/img/torrent-cover_'.$torrent->id.'.jpg'))
                        <img
                            src="{{ url('files/img/torrent-cover_'.$torrent->id.'.jpg') }}"
                            class="torrent-search--list__music_poster-img"
                            loading="lazy"
                            alt="{{ __('torrent.poster') }}"
                        >
                    @else
                        <img
                            src="https://via.placeholder.com/400x600"
                            class="torrent-search--list__music_poster-img"
                            loading="lazy"
                            alt="{{ __('torrent.poster') }}"
                        >
                    @endif
                @endif
            </a>
        </td>
    @endif
    <td class="torrent-search--list__format">
        <div>
            <div class="torrent-search--list__category">
                @if ($torrent->category->image !== null)
                    <img
                        src="{{ url('files/img/' . $torrent->category->image) }}"
                        title="{{ $torrent->category->name }} {{ strtolower(__('torrent.torrent')) }}"
                        alt="{{ $torrent->category->name }}"
                        loading="lazy"
                        @style([
                            'height: 32px',
                            'padding-top: 1px' => $torrent->category->movie_meta || $torrent->category->tv_meta,
                            'padding-top: 12px' => ! ($torrent->category->movie_meta || $torrent->category->tv_meta),
                        ])
                    >
                @else
                    @if ($torrent->category->music_meta)
                        @switch($torrent->distributor->name)
                            @case('Country')
                                <i class="fas fa-hat-cowboy torrent-icon" @style(['padding-top: 12px'])> </i>
                                @break
                            @case('Children')
                                <i class="fas fa-child torrent-icon" @style(['padding-top: 12px'])> </i>
                                @break
                            @case('OST')
                                <i class="fas fa-film torrent-icon" @style(['padding-top: 12px'])> </i>
                                @break
                            @case('Classical')
                                <i class="fas fa-violin torrent-icon" @style(['padding-top: 12px'])> </i>
                                @break
                            @case('Alternative')
                                <i class="fas fa-alien-8bit torrent-icon" @style(['padding-top: 12px'])> </i>
                                @break
                            @case('Hip-Hop')
                                <i class="fas fa-hat-cowboy-side torrent-icon" @style(['padding-top: 12px'])> </i>
                                @break
                            @case('Instrumental')
                                <i class="fas fa-piano torrent-icon" @style(['padding-top: 12px'])> </i>
                                @break
                            @case('Blues')
                                <i class="fas fa-guitar-electric torrent-icon" @style(['padding-top: 12px'])> </i>
                                @break
                            @case('Latino')
                                <i class="fas fa-guitar torrent-icon" @style(['padding-top: 12px'])> </i>
                                @break
                            @case('Rock')
                                <i class="fas fa-drum torrent-icon" @style(['padding-top: 12px'])> </i>
                                @break
                            @case('Folk')
                                <i class="fas fa-guitars torrent-icon" @style(['padding-top: 12px'])> </i>
                                @break
                            @case('Pop')
                                <i class="fas fa-microphone-stand torrent-icon" @style(['padding-top: 12px'])> </i>
                                @break
                            @case('Jazz')
                                <i class="fas fa-saxophone torrent-icon" @style(['padding-top: 12px'])> </i>
                                @break
                            @case('Indie')
                                <i class="fas fa-indian-rupee torrent-icon" @style(['padding-top: 12px'])> </i>
                                @break
                            @case('Electronic')
                                <i class="fas fa-headphones torrent-icon" @style(['padding-top: 12px'])> </i>
                                @break
                            @case('Christian')
                                <i class="fas fa-cross torrent-icon" @style(['padding-top: 12px'])> </i>
                                @break
                            @case('Dance')
                                <i class="fas fa-theater-masks torrent-icon" @style(['padding-top: 12px'])> </i>
                                @break
                            @case('R&B')
                                <i class="fas fa-heart-crack torrent-icon" @style(['padding-top: 12px'])> </i>
                                @break
                            @case('Reggae')
                                <i class="fas fa-leaf-heart torrent-icon" @style(['padding-top: 12px'])> </i>
                                @break
                            @case('Musical')
                                <i class="fas fa-masks-theater torrent-icon" @style(['padding-top: 12px'])> </i>
                                @break
                            @case('New Age')
                                <i class="fas fa-spa torrent-icon" @style(['padding-top: 12px'])> </i>
                                @break
                            @case('Audiobook')
                                <i class="fas fa-book-open torrent-icon" @style(['padding-top: 12px'])> </i>
                                @break
                            @case('Anime')
                                <i class="fas fa-laugh-beam torrent-icon" @style(['padding-top: 12px'])> </i>
                                @break
                            @case('Gospel')
                                <i class="fas fa-cross torrent-icon" @style(['padding-top: 12px'])> </i>
                                @break
                            @case('Holiday')
                                <i class="fas fa-sun torrent-icon" @style(['padding-top: 12px'])> </i>
                                @break
                            @case('Religious')
                                <i class="fas fa-pray torrent-icon" @style(['padding-top: 12px'])> </i>
                                @break
                            @case('World')
                                <i class="fas fa-globe-americas torrent-icon" @style(['padding-top: 12px'])> </i>
                                @break
                            @default
                                <i class="{{ $torrent->category->icon }} torrent-icon" @style(['padding-top: 12px'])> </i>
                        @endswitch
                    @else
                        <i class="{{ $torrent->category->icon }} torrent-icon"
                            @style([
                                'font-size: 24px',
                                'padding-top: 1px' => $torrent->category->movie_meta || $torrent->category->tv_meta,
                                'padding-top: 12px' => ! ($torrent->category->movie_meta || $torrent->category->tv_meta),
                            ])
                        ></i>
                    @endif
                @endif



            </div>
            <div class="torrent-search--list__resolution-and-type">
                @if ($torrent->category->movie_meta || $torrent->category->tv_meta)
                    <span class="torrent-search--list__resolution">
                        {{ $torrent->resolution->name ?? '' }}
                    </span>
                @elseif ($torrent->category->music_meta)
                    <span class="torrent-search--list__resolution">
                        {{ $torrent->distributor->name ?? '' }}
                    </span>
                @elseif ($torrent->category->no_meta)
                    <span class="torrent-search--list__resolution">
                        {{ $torrent->region->name ?? '' }}
                    </span>
                @endif
                <span class="torrent-search--list__type">
                    {{ $torrent->type->name ?? '' }}
                </span>
            </div>
        </div>
    </td>
    <td class="torrent-search--list__overview">
        <div>
            <a class="torrent-search--list__name" href="{{ route('torrents.show', ['id' => $torrent->id]) }}">
                {{ $torrent->name }}
            </a>
            <x-user_tag class="torrent-search--list__uploader" :user="$torrent->user" :anon="$torrent->anon" />
            @include('components.partials._torrent-icons')
        </div>
    </td>
    <td class="torrent-search--list__buttons">
        <div>
            @if(auth()->user()->group->is_modo || auth()->id() === $torrent->user_id)
                <a
                    class="torrent-search--list__edit form__standard-icon-button"
                    href="{{ route('torrents.edit', ['id' => $torrent->id]) }}"
                    title="{{ __('common.edit') }}"
                >
                    <i class="{{ config('other.font-awesome') }} fa-pencil-alt"></i>
                </a>
            @endif
            @livewire('small-bookmark-button', ['torrent' => $torrent, 'isBookmarked' => $torrent->bookmarks_exists, 'user' => auth()->user()], key('torrent-'.$torrent->id))
            @if (config('torrent.download_check_page'))
                <a
                    class="torrent-search--list__file form__standard-icon-button"
                    href="{{ route('download_check', ['id' => $torrent->id]) }}"
                    title="{{ __('common.download') }}"
                >
                    <i class="{{ config('other.font-awesome') }} fa-download"></i>
                </a>
            @else
                <a
                    class="torrent-search--list__file form__standard-icon-button"
                    href="{{ route('download', ['id' => $torrent->id]) }}"
                    title="{{ __('common.download') }}"
                >
                    <i class="{{ config('other.font-awesome') }} fa-download"></i>
                </a>
            @endif
            @if (config('torrent.magnet'))
                <a
                    class="torrent-search--list__maget form__contained-icon-button form__contained-icon-button--filled"
                    href="magnet:?dn={{ $torrent->name }}&xt=urn:btih:{{ bin2hex($torrent->info_hash) }}&as={{ route('torrent.download.rsskey', ['id' => $torrent->id, 'rsskey' => auth()->user()->rsskey ]) }}&tr={{ route('announce', ['passkey' => auth()->user()->passkey]) }}&xl={{ $torrent->size }}"
                    download
                    title="{{ __('common.magnet') }}"
                >
                    <i class="{{ config('other.font-awesome') }} fa-magnet"></i>
                </a>
            @endif
        </div>
    </td>
    <td class="torrent-search--list__size">
        <span>{{ $torrent->getSize() }}</span>
    </td>
    <td class="torrent-search--list__seeders">
        <a href="{{ route('peers', ['id' => $torrent->id]) }}">
            <span>{{ $torrent->seeders }}</span>
        </a>
    </td>
    <td class="torrent-search--list__leechers">
        <a href="{{ route('peers', ['id' => $torrent->id]) }}">
            <span>{{ $torrent->leechers }}</span>
        </a>
    </td>
    <td class="torrent-search--list__completed">
        <a href="{{ route('history', ['id' => $torrent->id]) }}">
            <span>{{ $torrent->times_completed }}</span>
        </a>
    </td>
    <td class="torrent-search--list__age">
        <time datetime="{{ $torrent->created_at }}" title="{{ $torrent->created_at }}">
            {{ $torrent->created_at->diffForHumans() }}
        </time>
    </td>
</tr>
