<div>
    <input type="text" wire:model="search" placeholder="{{ __('搜索歌手') }}" class="form__text" />

    <div
        class="panel__body"
        style="display: grid; grid-template-columns: repeat(auto-fit, minmax(140px, 1fr)); gap: 2rem;">
        @forelse ($artists as $artist)
            <figure style="display: flex; flex-direction: column; align-items: center">
                <a href="{{ route('artists.show', ['id' => $artist->id]) }}">
                    <img
                        alt="{{ $artist->name }}"
                        src="{{ $artist->image_url ? $artist->image_url : 'https://via.placeholder.com/160x240' }}"
                        style="width: 140px; height: 140px; object-fit: cover; border-radius: 50%"
                    />
                </a>
                <figcaption>{{ $artist->name }}</figcaption>
            </figure>
        @empty
            <p>{{ __('未找到歌手信息') }}</p>
        @endforelse
    </div>
</div>
