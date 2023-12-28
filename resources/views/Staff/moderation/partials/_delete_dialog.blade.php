<li class="data-table__action" x-data>
    <button class="form__button form__button--filled" x-on:click.stop="$refs.dialog.showModal()">
        <i class="{{ config('other.font-awesome') }} fa-thumbs-down"></i>
        {{ __('common.delete') }}
    </button>
    <dialog class="dialog" x-ref="dialog">
        <h4 class="dialog__heading">
            {{ __('common.delete') }} {{ __('torrent.torrent') }}: {{ $torrent->name }}
        </h4>
        <form
            class="dialog__form"
            method="POST"
            action="{{ route('torrents.destroy', ['id' => $torrent->id]) }}"
            x-on:click.outside="$refs.dialog.close()"
            x-data="{
                    message: '',
                    appendMessage(newMessage) {
                        this.message += newMessage.replace(/\\n/g, '\n');
                    }
                }"
        >
            @csrf
            @method('DELETE')
            <input id="type" type="hidden" name="type" value="{{ __('torrent.torrent') }}">
            <input id="id" type="hidden" name="id" value="{{ $torrent->id }}">
            <p class="form__group">
                <button type="button" class="form__button--mod" @click="appendMessage('不接受任何分辨率在720p以下的资源！请仔细阅读[url=https://kimoji.club/pages/3]发布规则[/url]\\n')">720P</button>
                <button type="button" class="form__button--mod" @click="appendMessage('不接受任何除官组外的分集资源！请仔细阅读[url=https://kimoji.club/pages/3]发布规则[/url]\\n')">分集</button>
                <button type="button" class="form__button--mod" @click="appendMessage('不接受带台标的资源！请仔细阅读[url=https://kimoji.club/pages/3]发布规则[/url]\\n')">台标</button>
                <button type="button" class="form__button--mod" @click="appendMessage('除特许发布的极优资源外，不接受任何形式的打包资源！请仔细阅读[url=https://kimoji.club/pages/3]发布规则[/url]\\n')">打包</button>
            </p>
            <p class="form__group">
                <textarea class="form__textarea" name="message" id="message" required x-model="message"></textarea>
                <label class="form__label form__label--floating" for="message">删除原因</label>
            </p>
            <p class="form__group">
                <button class="form__button form__button--filled">
                    {{ __('common.delete') }}
                </button>
                <button formmethod="dialog" formnovalidate class="form__button form__button--outlined">
                    {{ __('common.cancel') }}
                </button>
            </p>
        </form>
    </dialog>
</li>
