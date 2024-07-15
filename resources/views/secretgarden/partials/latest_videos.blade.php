<div class="panel__body torrent-search--card__results">
    @foreach ($videos as $video)
        <x-video-card :video="$video" />
    @endforeach
</div>
