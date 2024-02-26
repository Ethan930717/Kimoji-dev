@props([
    'torrent',
])

<article class="torrent-search--poster__result">
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
    <figure>
        <a
            href="{{ route('torrents.show', ['id' => $torrent->id]) }}"
            class="torrent-search--poster__poster"
        >
            <img
                src="{{ url('/files/img/torrent-banner_'.$torrent->id.'.jpg') }}"
                alt="{{ __('torrent.poster') }}"
                loading="lazy"
            />
        </a>
        <figcaption class="torrent-search--poster__caption">
            <h2 class="torrent-search--poster__title">
                {{ $albumName }}
            </h2>
            <h3 class="torrent-search--poster__release-date">
                {{ $singerNameWithoutBrackets }}
            </h3>
        </figcaption>
    </figure>
</article>
