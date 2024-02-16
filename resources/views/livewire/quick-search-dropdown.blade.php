<div class="quick-search" x-data="{ ...quickSearchKeyboardNavigation() }"
     x-on:keydown.escape.window="$refs.movieSearch.blur(); $refs.seriesSearch.blur(); $refs.personSearch.blur()">
    <div class="quick-search__inputs">
        <div class="quick-search__radios">
            @foreach([
                'albums' => ['label' => '专辑', 'icon' => 'fa-album-collection', 'title' => __('artists.albums')],
                'songs' => ['label' => '歌曲', 'icon' => 'fa-music', 'title' => __('artists.songs')],
                'artists' => ['label' => '歌手', 'icon' => 'fa-user', 'title' => __('artists.title')]
            ] as $value => $info)
                <label class="quick-search__radio-label">
                    <input
                        type="radio"
                        class="quick-search__radio"
                        name="quicksearchRadio"
                        value="{{ $value }}"
                        wire:model.debounce.0="quicksearchRadio"
                        x-on:click="$nextTick(() => $refs.quickSearch.focus());"
                    />
                    <i
                        class="quick-search__radio-icon {{ \config('other.font-awesome') }} {{ $info['icon'] }}"
                        title="{{ $info['title'] }}"
                    ></i>
                </label>
            @endforeach
        </div>
        <input
            class="quick-search__input"
            wire:model.debounce.250ms="quicksearchText"
            type="text"
            placeholder="{{ __('请输入搜索内容') }}"
            x-ref="quickSearch"
            x-on:keydown.down.prevent="$refs.searchResults.firstElementChild?.firstElementChild?.focus()"
            x-on:keydown.up.prevent="$refs.searchResults.lastElementChild?.firstElementChild?.focus()"
        />
    </div>
    @if (strlen($quicksearchText) > 0)
        <div class="quick-search__results" x-ref="searchResults">
            @forelse ($search_results as $result)
                <article class="quick-search__result" x-on:keydown.down.prevent="quickSearchArrowDown($el)"
                         x-on:keydown.up.prevent="quickSearchArrowUp($el)">
                    @switch($quicksearchRadio)
                        @case('albums')
                        @case('songs')
                            <a class="quick-search__result-link" href="{{ route('torrents.show', ['id' => $result->torrent_id]) }}">
                                <img class="quick-search__image" src="{{ '/files/img/torrent-cover_'.$result->torrent_id.'.jpg' }}" alt="封面">
                                <span class="quick-search__result-text">
                                    {{ $result->artist_name }} - {{ $result->song_name }} [{{ $result->duration }}]
                                </span>
                            </a>
                            @break
                        @case('artists')
                            <a class="quick-search__result-link" href="{{ route('artists.show', ['id' => $result->id]) }}">
                                <img src="{{ $result->image_url }}" class="quick-search__image" alt="">
                                <span class="quick-search__result-text">{{ $result->name }}</span>
                            </a>
                            @break
                    @endswitch
                </article>
            @empty
                <article class="quick-search__result--empty">
                    <p class="quick-search__result-text">暂无内容</p>
                </article>
            @endforelse
        </div>
    @endif
</div>

<script nonce="{{ HDVinnie\SecureHeaders\SecureHeaders::nonce('script') }}">
    function quickSearchKeyboardNavigation() {
        return {
            quickSearchArrowDown(el) {
                if (el.nextElementSibling == null) {
                    el.parentNode?.firstElementChild?.firstElementChild?.focus()
                } else {
                    el.nextElementSibling?.firstElementChild?.focus()
                }
            },
            quickSearchArrowUp(el) {
                if (el.previousElementSibling == null) {
                    document.querySelector(`.quick-search__input:not([style='display: none;'])`)?.focus()
                } else {
                    el.previousElementSibling?.firstElementChild?.focus()
                }
            }
        }
    }
</script>
