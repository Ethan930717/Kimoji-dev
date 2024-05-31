<section class="panelV2">
    <header class="panel__header">
        <h2 class="panel__heading">Search</h2>
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
                </div>
            </div>
        </div>
    </header>
    <div
        class="panel__body"
        style="
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(140px, 1fr));
            gap: 2rem;
        "
    >
        @forelse ($videos as $video)
            <figure style="display: flex; flex-direction: column; align-items: center">
                <a href="{{ route('secretgarden.video.show', ['id' => $video->id]) }}">
                    <img
                        alt="{{ $video->item_number }}"
                        src="{{ url('secretgarden/images/' . $video->poster_url) }}"
                        style="
                            width: 140px;
                            height: 200px;
                            object-fit: cover;
                            border-radius: 8px;
                            position: relative;
                        "
                    />
                    <figcaption
                        style="
                            position: absolute;
                            top: 8px;
                            right: 8px;
                            background: #161a42;
                            border: 2px solid #73904b;
                            border-radius: 9999px;
                            box-shadow: 0 8px 10px 1px rgba(0, 0, 0, .14),
                                        0 3px 14px 2px rgba(0, 0, 0, .12),
                                        0 5px 5px -3px rgba(0, 0, 0, .2);
                            font-size: 11px;
                            grid-area: 2 / 3 / 1 / 2;
                            height: 36px;
                            line-height: 32px;
                            margin: 6px;
                            text-align: center;
                            width: 36px;
                        "
                    >
                        {{ $video->video_rank }}
                    </figcaption>
                </a>
                <figcaption>{{ $video->item_number }}</figcaption>
            </figure>
        @empty
            <p>No Result</p>
        @endforelse
    </div>
</section>
