<div x-data="{ showModal: @entangle('showModal') }">
    <button class="form__button form__button--outlined" x-on:click="showModal = true">
        <i class="{{ config('other.font-awesome') }} fa-pause"></i> {{ __('common.moderation-postpone') }}
    </button>

    <template x-if="showModal">
        <dialog class="dialog" open>
            <h4 class="dialog__heading">{{ __('common.moderation-postpone') }}: {{ $torrent->name }}</h4>
            <form class="dialog__form" wire:submit.prevent="postpone">
                @csrf
                <textarea id="message" class="form__textarea" wire:model="message" required></textarea>
                <button class="form__button form__button--filled">{{ __('common.moderation-postpone') }}</button>
                <button class="form__button form__button--outlined" x-on:click="showModal = false">{{ __('common.cancel') }}</button>
            </form>
        </dialog>
    </template>
</div>
