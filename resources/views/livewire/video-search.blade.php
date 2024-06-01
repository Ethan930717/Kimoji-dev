<section class="panelV2">
    <header class="panel__header">
        <h2 class="panel__heading">Videos</h2>
        <div class="panel__actions">
            <div class="panel__action">
                <div class="form__group">
                    <input
                        id="name"
                        class="form__text"
                        placeholder=" "
                        type="text"
                        wire:model.debounce.250ms="search"
                    />
                    <label class="form__label form__label--floating" for="name">
                        Item Number or Actor Name
                    </label>
                    <div wire:loading wire:target="search">
                        <span style="font-size: 14px">Searching...</span>
                    </div>
                </div>
            </div>
        </div>
    </header>
    {{ $videos->links('partials.pagination') }}
    <div class="panel__body" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(140px, 1fr)); gap: 2rem;">
        <table class="data-table">
            <thead>
            <tr>
                <th>{{ __('secretgarden.poster') }}</th>
                <th style="white-space: nowrap;">{{ __('secretgarden.actor') }}</th>
                <th style="white-space: nowrap;">{{ __('secretgarden.item_number') }}</th>
                <th>{{ __('secretgarden.title') }}</th>
                <th>
                    <a href="#" wire:click.prevent="sortBy('duration')">
                        {{ __('secretgarden.duration') }}
                        @if ($sortField == 'duration')
                            @if ($sortDirection == 'asc')
                                &uarr;
                        @else
                            &darr;
                        @endif
                        @endif
                    </a>
                </th>
                <th>
                    <a href="#" wire:click.prevent="sortBy('video_rank')">
                        {{ __('secretgarden.rank') }}
                        @if ($sortField == 'video_rank')
                            @if ($sortDirection == 'asc')
                                &uarr;
                        @else
                            &darr;
                        @endif
                        @endif
                    </a>
                </th>
            </tr>
            </thead>
            <tbody>
            @forelse ($videos as $video)
                <tr>
                    <td>
                        <img
                            class="video-poster"
                            alt="{{ $video->item_number }}"
                            src="{{ url('secretgarden/poster/' . $video->poster_url) }}"
                            style="width: 100%; max-width: 200px; height: auto; cursor: pointer;"
                            data-fancybox="gallery"
                        />
                    </td>
                    <td style="white-space: nowrap;">{{ $video->actor_name }}</td>
                    <td style="white-space: nowrap;">
                        <a href="{{ route('secretgarden.video.show', ['id' => $video->id]) }}">
                            {{ $video->item_number }}
                        </a>
                    </td>
                    <td>
                        <a href="{{ route('secretgarden.video.show', ['id' => $video->id]) }}">
                            {{ $video->title }}
                        </a>
                    </td>
                    <td>{{ $video->duration }}</td>
                    <td>{{ $video->video_rank }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="6">{{ __('No Result') }}</td>
                </tr>
            @endforelse
            </tbody>
        </table>
    </div>
    {{ $videos->links('partials.pagination') }}
</section>
