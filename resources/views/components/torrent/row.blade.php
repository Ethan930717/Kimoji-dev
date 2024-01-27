
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
                            @case('乡村（Country）')
                                <i class="fas fa-hat-cowboy torrent-icon" @style(['padding-top: 12px'])> </i>
                                @break
                            @case('儿童（Children）')
                                <i class="fas fa-child torrent-icon" @style(['padding-top: 12px'])> </i>
                                @break
                            @case('原声（OST）')
                                <i class="fas fa-film torrent-icon" @style(['padding-top: 12px'])> </i>
                                @break
                            @case('古典（Classic）')
                                <i class="fas fa-violin torrent-icon" @style(['padding-top: 12px'])> </i>
                                @break
                            @case('另类（Alternative）')
                                <i class="fas fa-optin-monster torrent-icon" @style(['padding-top: 12px'])> </i>
                                @break
                            @case('嘻哈（Hip Hop）')
                                <i class="fas fa-hat-cowboy-side torrent-icon" @style(['padding-top: 12px'])> </i>
                                @break
                            @case('器乐（Instrumental）')
                                <i class="fas fa-piano torrent-icon" @style(['padding-top: 12px'])> </i>
                                @break
                            @case('布鲁斯（Blues）')
                                <i class="fas fa-guitar-electric torrent-icon" @style(['padding-top: 12px'])> </i>
                                @break
                            @case('拉丁（Latino）')
                                <i class="fas fa-guitar torrent-icon" @style(['padding-top: 12px'])> </i>
                                @break
                            @case('摇滚（Rock）')
                                <i class="fas fa-drum torrent-icon" @style(['padding-top: 12px'])> </i>
                                @break
                            @case('新世纪（New Age）')
                                <i class="fas fa-spa torrent-icon" @style(['padding-top: 12px'])> </i>
                                @break
                            @case('民谣（Folk）')
                                <i class="fas fa-guitars torrent-icon" @style(['padding-top: 12px'])> </i>
                                @break
                            @case('流行（Pop）')
                                <i class="fas fa-music torrent-icon" @style(['padding-top: 12px'])> </i>
                                @break
                            @case('爵士（Jazz）')
                                <i class="fas fa-saxophone torrent-icon" @style(['padding-top: 12px'])> </i>
                                @break
                            @case('独立（Indie）')
                                <i class="fas fa-indian-rupee torrent-icon" @style(['padding-top: 12px'])> </i>
                                @break
                            @case('电子（Electronic）')
                                <i class="fas fa-headphones torrent-icon" @style(['padding-top: 12px'])> </i>
                                @break
                            @case('福音（Christian）')
                                <i class="fas fa-cross torrent-icon" @style(['padding-top: 12px'])> </i>
                                @break
                            @case('舞曲（Dance）')
                                <i class="fas fa-theater-masks torrent-icon" @style(['padding-top: 12px'])> </i>
                                @break
                            @case('节奏布鲁斯 (R&B)')
                                <i class="fas fa-heart torrent-icon" @style(['padding-top: 12px'])> </i>
                                @break
                            @case('雷鬼（Reggae）')
                                <i class="fas fa-leaf torrent-icon" @style(['padding-top: 12px'])> </i>
                                @break
                            @case('音乐剧（Musical）')
                                <i class="fas fa-masks-theater torrent-icon" @style(['padding-top: 12px'])> </i>
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
    @if ($torrent->category->game_meta)
        <td class="torrent-search--list__rating {{ rating_color($meta->rating) ?? 'text-white' }}">
            <span>{{ round($meta->rating ?? 0) }}%</span>
        </td>
    @elseif ($torrent->category->movie_meta || $torrent->category->tv_meta)
        <td class="torrent-search--list__rating" title="{{ $meta->vote_count ?? 0 }} Votes">
        <span class="{{ rating_color($meta->vote_average ?? 0) ?? 'text-white' }}">
            {{ round(($meta->vote_average ?? 0) * 10) }}%
        </span>
        </td>
    @else
        <td class="torrent-search--list__rating">N/A</td>
    @endif
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
