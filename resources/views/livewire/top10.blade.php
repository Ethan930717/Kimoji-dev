<section class="panelV2">
    <header class="panel__header">
        <h2 class="panel__heading">排行榜</h2>
        <div class="panel__actions">
            <div class="panel__action">
                <div class="form__group">
                    <select id="interval" class="select2" type="date" name="interval" wire:model="interval">
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
            <div class="panel__action">
                <div class="form__group">
                    <select id="metaType" class="select2" type="date" name="metaType" wire:model="metaType">
                        @foreach ($metaTypes as $name => $type)
                            <option value="{{ $type }}">{{ $name  }}</option>
                        @endforeach
                    </select>
                    <label class="form__label form__label--floating" for="metaType">
                        类别
                    </label>
                </div>
            </div>
        </div>
    </header>
    <div class="panel__body torrent-search--poster__results">
        <div wire:loading.delay>Computing...</div>
        @switch ($this->metaType)
            @case ('movie_meta')
                @foreach($works as $work)
                    <figure class="top10-poster">
                        <x-movie.poster :movie="$work->movie" :categoryId="$work->category_id" :tmdb="$work->tmdb" />
                        <figcaption class="top10-poster__download-count" title="{{ __('torrent.completed-times') }}">
                            {{ $work->download_count }}
                        </figcaption>
                    </figure>
                @endforeach

                @break
            @case ('tv_meta')
                @foreach($works as $work)
                    <figure class="top10-poster">
                        <x-tv.poster :tv="$work->tv" :categoryId="$work->category_id" :tmdb="$work->tmdb" />
                        <figcaption class="top10-poster__download-count" title="{{ __('torrent.completed-times') }}">
                            {{ $work->download_count }}
                        </figcaption>
                    </figure>
                @endforeach

                @break
        @endswitch
    </div>
</section>
