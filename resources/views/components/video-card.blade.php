@props([
    'video',
])

<article class="torrent-card">
    <header class="torrent-card__header">
        <div class="torrent-card__left-header">
            @php
                $durationMinutes = intval(preg_replace('/\D/', '', $video->duration));
                $hours = floor($durationMinutes / 60);
                $minutes = $durationMinutes % 60;
                $formattedDuration = sprintf('%02d:%02d', $hours, $minutes);
            @endphp
            <span class="torrent-card__title">
                <i class="fas fa-film"></i> {{ $video->item_number }}
            </span>
            <span class="torrent-card__duration">
                <i class="fas fa-clock"></i> {{ $formattedDuration }}
            </span>
            @if {{ $video->video_rank }}
                <span class="torrent-card__rating">
                    <i class="fas fa-star"></i> {{ $video->video_rank }}
                </span>
            @endif
        </div>
        <div class="torrent-card__right-header">

        </div>
    </header>
    <aside class="torrent-card__aside">
        <figure class="torrent-card__figure">
            <img
                class="torrent-card__image"
                src="{{ url('secretgarden/poster/' . $video->poster_url) }}"
                alt="{{ __('torrent.poster') }}"
            />
        </figure>
    </aside>
    <footer class="torrent-card__footer">
        <div class="torrent-card__left-footer">
        </div>
        <div class="torrent-card__right-footer">
        </div>
    </footer>
</article>
