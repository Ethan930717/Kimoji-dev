<section class="meta">
    @php
        // 首先以 " - " 为分隔符进行分割，这样避免了错误地分割专辑名中可能包含的 "-"
        $parts = explode(' - ', $torrent->name);

        // 移除并获取歌手名称，同时清理歌手名称中的括号
        $singerName = array_shift($parts);
        $singerNameWithoutBrackets = preg_replace('/[\(\）].*?[\)\（]/u', '', $singerName);

        // 移除最后一个元素（包含元数据和"kimoji"）
        array_pop($parts);

        // 重新组合剩余部分作为专辑名称，并检查是否包含年份
        $albumName = implode(' - ', $parts);
        $year = '';
        if (preg_match('/(\d{4})/', $albumName, $matches)) {
            $year = $matches[1];
            $albumName = preg_replace('/\d{4}/', '', $albumName); // 移除年份
            $albumName = trim($albumName, ' -'); // 移除尾部多余的 " - "
            $albumName .= " ($year)"; // 在专辑名称后加上年份
        }
    @endphp

    @php
        $description = $torrent->description;
        $spectrogramPattern = '/\[spoiler=截图赏析\].*?\[img\](.*?)\[\/img\]/s';
        $spectrogramUrl = '';

        if (preg_match($spectrogramPattern, $description, $matches)) {
            $spectrogramUrl = $matches[1];
        }
    @endphp

    @if ($singerName)
        @php
            $artist = \App\Models\Artist::where('name', 'like', "%{$singerName}%")->first();
        @endphp
    @endif
    @if (file_exists(public_path().'/files/img/torrent-banner_'.$torrent->id.'.jpg'))
        <img class="meta__backdrop" src="{{ url('/files/img/torrent-banner_'.$torrent->id.'.jpg') }}" alt="Backdrop">
    @else
        <img class="meta__backdrop" src="{{ url('/files/img/torrent-cover_'.$torrent->id.'.jpg') }}" alt="Backdrop">
    @endif
        <a class="meta__title-link">
            <h1 class="meta__title">{{ $albumName }}</h1>
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
                    <a href="{{ route('artists.edit', $artist->id) }}">
                        {{ __('artists.edit') }}
                    </a>
                </li>
            </ul>
        </div>
        <ul class="meta__ids">
            <li class="meta__imdb">
                    @if ($artist)
                        <a class="meta-id-tag" href="{{ route('artists.show', $artist->id) }}">
                            <i class="{{ config('other.font-awesome') }} fa-album"></i> {{ __('artists.all') }}
                        </a>
                    @else
                        <a class="meta-id-tag" href="/torrents?perPage=25&name={{ urlencode($singerName) }}" target="_blank">
                            <i class="{{ config('other.font-awesome') }} fa-album"></i> {{ __('artists.all') }}
                        </a>
                    @endif
                <a class="meta-id-tag" title="Internet Movie Database" target="_blank"
                   href="{{ route('torrents.index', ['distributors' => [$torrent->distributor->id]]) }}">
                    <i class="{{ config('other.font-awesome') }} fa-music"></i> {{ $torrent?->distributor->name ?? '未知风格' }}
                </a>
                    @php
                        use App\Enums\UserGroup;
                        $userGroup = auth()->user()->group_id;
                        $listenLimits = [
                            UserGroup::USER->value => 1,
                            UserGroup::POWERUSER->value => 6,
                            UserGroup::SUPERUSER->value => 12,
                            UserGroup::EXTREMEUSER->value => 20,
                            UserGroup::INSANEUSER->value => 30,
                            UserGroup::VETERAN->value => 42,
                            UserGroup::SEEDER->value => 55,
                            UserGroup::ARCHIVIST->value => 70,
                            // 任何未列出的等级都没有限制，可以无限试听
                        ];
                        $unlimitedGroups = [UserGroup::VIP->value, UserGroup::KEEPER->value, UserGroup::OWNER->value, UserGroup::INTERNAL->value];
                    @endphp

                    @if($userGroup === UserGroup::LEECH->value || $userGroup === UserGroup::DISABLED->value)
                        <span class="meta-id-tag">{{ __('artists.insufficient') }}</span>
                    @elseif(in_array($userGroup, $unlimitedGroups))
                        <button id="loadPlayerBtn" class="meta-id-tag" data-username="{{ auth()->user()->username }}">
                            <i class="{{ config('other.font-awesome') }} fa-headphones"></i> {{ __('artists.load') }}
                        </button>
                    @else
                        @php
                            $limit = $listenLimits[$userGroup] ?? PHP_INT_MAX; // 默认无限制
                        @endphp
                        @if(auth()->user()->daily_listen_count < $limit)
                            <button id="loadPlayerBtn" class="meta-id-tag" data-username="{{ auth()->user()->username }}">{{ __('artists.load') }}</button>
                        @else
                            <span class="meta-id-tag">今日试听次数已用尽 {{ auth()->user()->daily_listen_count }}/{{ $limit }}</span>
                        @endif
                    @endif
                        @if ($spectrogramUrl)
                            <button class="meta-id-tag" data-spectrogram-button  style="border:1px solid hsla(0,0%,100%,.161); border-radius: 16px; box-shadow:2px 4px 2px rgba(0,0,0,.2); cursor:pointer; transition:background-color .3s,color .3s; ">
                                <img
                                    src="{{ $spectrogramUrl }}"
                                    class="spectrogram-image"
                                    alt="spectrogram"
                                />
                                <span style="display: inline; margin-left: 8px;">{{ __('artists.spectrogram') }}</span>
                            </button>
                        @endif


            </li>

        </ul>
        @php
            $description = $torrent->description;
            $pattern = '/专辑介绍\]\[size=16\]\[color=white\]\[center\](.*?)\[\/center\]/s';
            $matches = [];

            if (preg_match($pattern, $description, $matches)) {
                $spoilerContent = html_entity_decode($matches[1]);
                // 将换行符转换为<br>标签
                $spoilerContent = nl2br($spoilerContent);
            } else {
                $spoilerContent = '';
            }
        @endphp

        <p class="meta__description">
            @if(!empty($spoilerContent))
                {!! $spoilerContent !!}
            @elseif(!empty($artist->biography))
                {!! $artist->biography !!}
            @else
                暂无歌手简介，欢迎您补充元数据
            @endif
        </p>

        @php
            $musicUrl = $torrent?->music_url;
            $is_lrc =  $torrent?->is_lrc;
            $lrcUrl = null;
            $musicName = "单曲试听"; // 默认歌曲名称

            if ($musicUrl) {
                $lrcUrl = preg_replace('/\.[^.]+$/', '.lrc', $musicUrl);

                // 提取文件名（不含后缀）
                $pathInfo = pathinfo($musicUrl);
                if (isset($pathInfo['basename'])) {
                    // 解码 URL 编码的字符串
                    $decodedName = urldecode($pathInfo['basename']);

                    // 移除文件后缀
                    $musicName = preg_replace('/\.[^.]+$/', '', $decodedName);
                }
            }
        @endphp
        @php
            $pattern = '/歌曲列表\]\[size=16\]\[color=white\]\[center\](.*?)\[\/center\]/s';
            $songList = '';
            $playTime = ''; // 初始化播放时长变量

            if (preg_match($pattern, $description, $matches)) {
                $songList = html_entity_decode($matches[1]);
            }

            // 将歌曲列表分割成数组
            $songs = explode("\n", $songList);

            // 检查是否有歌曲列表数据
            if (!empty($songs)) {
                // 提取播放时长（第一行）
                $playTime = $songs[0];

                // 移除数组中的第一行（即跳过时长信息行）
                $songs = array_slice($songs, 1);
            }
        @endphp


        <div class="meta__chips">
            @if (!empty($songs))
                <section class="meta__chip-container">
                    <h2 class="meta__heading">{{ __('artists.playlist') }}</h2>
                 @foreach ($songs as $song)
                        <article class="meta-chip-wrapper">
                            <a class="meta-chip__name">
                                {{ trim($song) }}
                            </a>
                        </article>
                    @endforeach
                </section>
            @endif
                @if ($artist)
                <section class="meta__chip-container">
                    <h2 class="meta__heading">{{ __('artists.information') }}</h2>
                    <article class="meta-chip-wrapper meta-chip">
                        @if ($artist->image_url)
                            <img
                                class="meta-chip__image"
                                src="{{ tmdb_image('cast_face', $artist->image_url) }}"
                                alt=""
                            />
                        @else
                            <i class="{{ config('other.font-awesome') }} fa-mask meta-chip__icon"></i>
                        @endif
                        <h2 class="meta-chip__name" style="white-space: nowrap; display: inline-block;">{{ __('artists.artname') }}</h2>
                        <h3 class="meta-chip__value" style="white-space: nowrap; display: inline-block;">{{ $singerName }}</h3>

                    </article>
                    @if($artist->birthday)
                    <article class="meta-chip-wrapper meta-chip">
                        <a class="meta-chip">
                            <i class="{{ config('other.font-awesome') }} fa-birthday-cake meta-chip__icon"></i>
                            <h2 class="meta-chip__name" style="white-space: nowrap; display: inline-block;">{{ __('artists.born') }}</h2>
                            <h3 class="meta-chip__value" style="white-space: nowrap; display: inline-block;">{{ $artist->birthday }}</h3>
                        </a>
                    </article>
                    @endif
                    @if($artist->deathday)
                        <article class="meta-chip-wrapper meta-chip">
                            <a class="meta-chip">
                                <i class="{{ config('other.font-awesome') }} fa-ribbon meta-chip__icon"></i>
                                <h2 class="meta-chip__name" style="white-space: nowrap; display: inline-block;">{{ __('artists.died') }}</h2>
                                <h3 class="meta-chip__value" style="white-space: nowrap; display: inline-block;">{{ $artist->deathday }}</h3>
                            </a>
                        </article>
                    @endif
                    @if($artist->country)
                        <article class="meta-chip-wrapper meta-chip">
                            <a class="meta-chip">
                                <i class="{{ config('other.font-awesome') }} fa-globe-americas meta-chip__icon"></i>
                                <h2 class="meta-chip__name" style="white-space: nowrap; display: inline-block;">{{ __('artists.country') }}</h2>
                                <h3 class="meta-chip__value" style="white-space: nowrap; display: inline-block;">{{ $artist->country }}</h3>
                            </a>
                        </article>
                    @endif
                    @if($artist->member)
                        <article class="meta-chip-wrapper meta-chip">
                            <a class="meta-chip">
                                <i class="{{ config('other.font-awesome') }} fa-users meta-chip__icon"></i>
                                <h2 class="meta-chip__name" style="white-space: nowrap; display: inline-block;">{{ __('artists.member') }}</h2>
                                <h3 class="meta-chip__value" style="white-space: nowrap; display: inline-block;">{!! str_replace('/', '<br>', $artist->member) !!}</h3>
                            </a>
                        </article>
                    @endif
                    @if($artist->label)
                        <article class="meta-chip-wrapper meta-chip">
                            <a class="meta-chip">
                                <i class="{{ config('other.font-awesome') }} fa-compact-disc meta-chip__icon"></i>
                                <h2 class="meta-chip__name" style="white-space: nowrap; display: inline-block;">{{ __('artists.label') }}</h2>
                                <h3 class="meta-chip__value" style="white-space: nowrap; display: inline-block;">{!! str_replace('/', '<br>', $artist->label) !!}</h3>
                            </a>
                        </article>
                    @endif
                    @if($artist->genre)
                        <article class="meta-chip-wrapper meta-chip">
                            <a class="meta-chip">
                                <i class="{{ config('other.font-awesome') }} fa-music meta-chip__icon"></i>
                                <h2 class="meta-chip__name" style="white-space: nowrap; display: inline-block;">{{ __('artists.genre') }}</h2>
                                <h3 class="meta-chip__value" style="white-space: nowrap; display: inline-block;">{!! str_replace('/', '<br>', $artist->genre) !!}</h3>
                            </a>
                        </article>
                    @endif
                </section>
                @endif

        </div>
        @if(!in_array($user->group_id, [App\Enums\UserGroup::USER->value, App\Enums\UserGroup::LEECH->value]))
            @if($musicUrl)
                @if ($is_lrc == 1)
                    <div id="aplayer"
                         data-cover="{{ url('img/kimoji-music.webp') }}"
                         data-name="{{ $musicName }}"
                         data-url="{{ $musicUrl }}"
                         data-artist="{{ $singerNameWithoutBrackets }}"
                         data-lrc="{{ $lrcUrl }}">
                    </div>
                @endif
                    <div id="aplayer"
                         data-cover="{{ url('img/kimoji-music.webp') }}"
                         data-name="{{ $musicName }}"
                         data-url="{{ $musicUrl }}"
                         data-artist="{{ $singerNameWithoutBrackets }}">
                    </div>
            @endif
        @endif
</section>
