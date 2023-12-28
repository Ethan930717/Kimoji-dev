<div x-data="{ showModal: @entangle('showModal') }">
    <button class="form__button form__button--outlined" x-on:click="showModal = true">
        <i class="{{ config('other.font-awesome') }} fa-pause"></i> {{ __('common.moderation-postpone') }}
    </button>

    <template x-if="showModal">
        <dialog class="dialog" x-ref="dialog" open>
            <h4 class="dialog__heading">{{ __('common.moderation-postpone') }}: {{ $torrent->name }}</h4>
            <form class="dialog__form" wire:submit.prevent="postpone" x-on:click.outside="showModal = false">
                @csrf
                <input type="hidden" wire:model="torrent.id">
                <input type="hidden" name="old_status" value="{{ $torrent->status }}">
                <input type="hidden" name="status" value="{{ \App\Models\Torrent::POSTPONED }}">

                <p class="form__group">
                    <textarea id="message" class="form__textarea" wire:model="message" required></textarea>
                    <label for="report_reason" class="form__label form__label--floating">
                        {{ __('common.reason') }}
                    </label>
                </p>

                <p class="form__group">
                    <button class="form__button form__button--filled">{{ __('common.moderation-postpone') }}</button>
                    <button type="button" class="form__button form__button--outlined" x-on:click="showModal = false">
                        {{ __('common.cancel') }}
                    </button>
                </p>
            </form>
        </dialog>
    </template>
</div>