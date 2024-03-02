<section class="panelV2">
    <header class="panel__header">
        <h2 class="panel__heading">Top 10 Music</h2>
        <div class="panel__actions">
            <div class="panel__action">
                <div class="form__group">
                    <select id="interval" class="form__select" type="date" name="interval" wire:model="interval">
                        <option value="day">一天内</option>
                        <option value="week">一周内</option>
                        <option value="month">一月内</option>
                        <option value="year">一年内</option>
                        <option value="all">所有时间</option>
                    </select>
                    <label class="form__label form__label--floating" for="interval">
                        时间间隔
                    </label>
                </div>
            </div>
        </div>
    </header>
    <div class="panel__body torrent-search--poster__results">
        <div wire:loading.delay>计算中...</div>
        @foreach($works as $work)
            <figure class="top10-poster">
                <x-music.poster :torrent="$work" />
                <figcaption class="top10-poster__download-count" title="{{ __('torrent.completed-times') }}">
                    {{ $work->download_count }}
                </figcaption>
            </figure>
        @endforeach
    </div>
</section>
