<section class="meta">
    @if (file_exists(public_path().'/files/img/torrent-banner_'.$torrent->id.'.jpg'))
        <img class="meta__backdrop" src="{{ url('/files/img/torrent-banner_'.$torrent->id.'.jpg') }}" alt="Backdrop">
    @else
        <img class="meta__backdrop" src="{{ url('/files/img/torrent-cover_'.$torrent->id.'.jpg') }}" alt="Backdrop">
    @endif
        @php
            $parts = explode('-', $torrent->name);
            $singerName = array_shift($parts); // 移除并获取第一个部分（歌手名称）
            array_pop($parts); // 移除最后一部分
            array_pop($parts); // 再次移除，这次是倒数第二部分
            $title = implode('-', $parts); // 重新组合中间部分
        @endphp
        <a class="meta__title-link">
            <h1 class="meta__title">{{ $title }}</h1>
        </a>
        <a class="meta__poster-link">
            <img
                    src="{{ file_exists(public_path().'/files/img/torrent-cover_'.$torrent->id.'.jpg') ? url('/files/img/torrent-cover_'.$torrent->id.'.jpg') : 'https://via.placeholder.com/500x500' }}"
                    class="meta__poster"
                    onclick="openImageModal('{{ file_exists(public_path().'/files/img/torrent-cover_'.$torrent->id.'.jpg') ? url('/files/img/torrent-cover_'.$torrent->id.'.jpg') : 'https://via.placeholder.com/500x500' }}')"
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
        </ul>
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
        <p class="meta__description">
            {{ mb_strlen($spoilerContent) > 200 ? mb_substr($spoilerContent, 0, 200) . '...' : $spoilerContent }}
        </p>
        @php
            $musicUrl = $torrent?->music_url;
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
            $description = $torrent->description;
            $pattern = '/歌曲列表\]\[size=16\]\[color=white\]\[center\](.*?)\[\/center\]/s';
            $matches = [];

            if (preg_match($pattern, $description, $matches)) {
                $songListContent = html_entity_decode($matches[1]);
                // 分割成单独的行
                $songs = preg_split('/\r\n|\r|\n/', $songListContent);
                // 提取每一行中的歌曲名称
                $songNames = array_map(function($line) {
                    if (preg_match('/^\d+\.\s+(.*)\s+-\s+\[.+\]$/', $line, $matches)) {
                        return $matches[1]; // 返回歌曲名称
                    }
                    return null;
                }, $songs);
                $songNames = array_filter($songNames); // 移除空值
            } else {
                $songNames = [];
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
            <section class="meta__chip-container">
                <h2 class="meta__heading">歌曲列表</h2>
                @foreach($songNames as $songName)
                    <article class="meta-chip-wrapper">
                        <div class="meta-chip">
                            <h2 class="meta-chip__name">{{ $songName }}</h2>
                        </div>
                    </article>
                @endforeach
            </section>
            @if ($spectrogramUrl)
                <section class="meta__chip-container">
                    <h2 class="meta__heading">频谱分析</h2>
                    <article class="meta-chip-wrapper">
                        <div class="meta-chip">
                            <img
                                src="{{ $spectrogramUrl }}"
                                class="meta-chip__image"
                                alt="频谱分析"
                                onclick="openImageModal('{{ $spectrogramUrl }}')"
                                style="cursor: pointer;"
                            />
                        </div>
                    </article>
                </section>
            @endif
        </div>

        <div id="aplayer-container"
             data-cover="{{ url('img/kimoji-music.webp') }}"
             data-name="{{ $musicName }}"
             data-url="{{ $musicUrl }}"
             data-artist="{{ $singerName }}"
             data-lrc="{{ $lrcUrl }}">
            <div id="aplayer" class="aplayer"></div>
        </div>
</section>
