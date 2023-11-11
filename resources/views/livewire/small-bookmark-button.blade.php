@if($this->isBookmarked)
    <button wire:click="destroy({{ $torrent->id }})" class="form__standard-icon-button" title="取消收藏">
        <i class="{{ config('other.font-awesome') }} fa-bookmark-slash"></i>
    </button>
@else
    <button wire:click="store({{ $torrent->id }})" class="form__standard-icon-button" title="收藏">
        <i class="{{ config('other.font-awesome') }} fa-bookmark"></i>
    </button>
@endif
