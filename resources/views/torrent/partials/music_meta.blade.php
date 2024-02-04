<section class="meta">
    @if (file_exists(public_path().'/files/img/torrent-banner_'.$torrent->id.'.jpg'))
        <img class="meta__backdrop" src="{{ url('/files/img/torrent-banner_'.$torrent->id.'.jpg') }}" alt="Backdrop">
    @else
        <img class="meta__backdrop" src="{{ url('/files/img/torrent-cover_'.$torrent->id.'.jpg') }}" alt="Backdrop">
    @endif
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
                @if(in_array($user->group_id, [App\Enums\UserGroup::USER->value, App\Enums\UserGroup::LEECH->value]))
                <a class="meta-id-tag" title="Internet Movie Database" target="_blank" style="text-shadow: 0 0 2px red">
                   壮士及以上等级可享受试听功能，官人请多多努力
                </a>
                @else
                <button id="loadPlayerBtn" class="meta-id-tag">加载试听曲目</button>
            </li>

            @endif
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
            {!! $spoilerContent !!}
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

        @php
            $description = $torrent->description;
            $spectrogramPattern = '/\[spoiler=截图赏析\].*?\[img\](.*?)\[\/img\]/s';
            $spectrogramUrl = '';

            if (preg_match($spectrogramPattern, $description, $matches)) {
                $spectrogramUrl = $matches[1];
            }
        @endphp

        <div class="meta__chips">
            @if (!empty($songs))
                <section class="meta__chip-container">
                    <h2 class="meta__heading">歌曲列表</h2>
                    @foreach ($songs as $song)
                        <article class="meta-chip-wrapper">
                            <a class="meta-chip">
                                <h2 class="meta-chip__name">{{ trim($song) }}</h2>
                            </a>
                        </article>
                    @endforeach
                </section>
            @endif            @if ($spectrogramUrl)
                <section class="meta__chip-container">
                    <h2 class="meta__heading">频谱分析</h2>
                            <img
                                src="{{ $spectrogramUrl }}"
                                class="spectrogram-image"
                                alt="频谱分析"
                                style="cursor: pointer; max-width: 100%;"
                            />
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
