<section class="panelV2">
    <header class="panel__header">
        <h2 class="panel__heading">{{ __('mediahub.tags') }}</h2>
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
                        {{ __('torrent.search-by-name') }}
                    </label>
                </div>
            </div>
        </div>
    </header>
    {{ $tags->links('partials.pagination') }}
    <div class="panel__body">
        <ul class="mediahub-card__list">
            @forelse ($tags as $tag)
                <li class="mediahub-card__list-item">
                    <a
                        href="{{ route('secretgarden.video_tags.show', ['id' => $tag->id]) }}"
                        class="mediahub-card"
                    >
                        <h2 class="mediahub-card__heading">
                            @isset($tag->poster)
                                <img
                                    class="mediahub-card__image"
                                    src="{{ url('secretgarden/poster/' . $tag->poster) }}"
                                    alt="{{ $tag->name }}"
                                />
                            @else
                                {{ $tag->name }}
                            @endisset
                        </h2>
                        <h3 class="mediahub-card__subheading">
                            <i class="{{ config('other.font-awesome') }} fa-tag"></i>
                            {{ $tag->name }} | {{ $tag->videos_count }} Videos
                        </h3>
                    </a>
                </li>
            @empty
                No {{ __('mediahub.tags') }}
            @endforelse
        </ul>
    </div>
    {{ $tags->links('partials.pagination') }}
</section>
