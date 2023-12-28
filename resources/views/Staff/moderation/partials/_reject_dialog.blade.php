<li class="data-table__action" x-data>
    <button class="form__button form__button--filled" x-on:click.stop="$refs.dialog.showModal()">
        <i class="{{ config('other.font-awesome') }} fa-thumbs-down"></i>
        {{ __('common.moderation-reject') }}
    </button>
    <dialog class="dialog" x-ref="dialog">
        <h3 class="dialog__heading">
            {{ __('common.moderation-reject') }} {{ __('torrent.torrent') }}: {{ $torrent->name }}
        </h3>
        <form
            class="dialog__form"
            method="POST"
            action="{{ route("staff.moderation.update", ['id' => $torrent->id]) }}"
            x-on:click.outside="$refs.dialog.close()"
            x-data="{
                        message: '',
                        appendMessage(newMessage) {
                            this.message += newMessage.replace(/\\n/g, '\n');
                        }
                    }"
        >
            @csrf
            <input id="type" type="hidden" name="type" value="{{ __('torrent.torrent') }}">
            <input id="id" type="hidden" name="id" value="{{ $torrent->id }}">
            <input type="hidden" name="old_status" value="{{ $torrent->status }}">
            <input type="hidden" name="status" value="{{ \App\Models\Torrent::REJECTED }}">
            <p class="form__group">
                <button type="button" class="form__button--mod" @click="appendMessage('规范主标题命名，详见 [url=https://kimoji.club/pages/3]发布规则[/url]\\n')">标题命名</button>
                <button type="button" class="form__button--mod" @click="appendMessage('请提交完整的 Mediainfo 扫描信息\\n')">Mediainfo</button>
                <button type="button" class="form__button--mod" @click="appendMessage('原盘请提供BDinfo，详见[url=https://kimoji.club/pages/4]原盘发布规则[/url]\\n')">BDinfo</button>
                <button type="button" class="form__button--mod" @click="appendMessage('请检查基本信息填写：类别/媒介等\\n')">基本信息</button>
                <button type="button" class="form__button--mod" @click="appendMessage('请补充TMDb/IMDb信息\\n')">T/IMDb</button>
                <button type="button" class="form__button--mod" @click="appendMessage('提供至少三张 BBCODE 格式的截图（非缩略图），原盘则需提供PNG原图\\n')">截图</button>
                <button type="button" class="form__button--mod" @click="appendMessage('请按固定的格式编辑描述信息，详见[url=https://kimoji.club/pages/3]发布规则[/url]或参考已发布的资源\\n')">描述格式</button>
            </p>
            <p class="form__group">
                <textarea id="message" class="form__textarea" name="message" required x-model="message">{{ old('message') }}</textarea>
                <label for="message" class="form__label form__label__floating">Rejection Message</label>
            </p>
            <p class="form__group">
                <button class="form__button form__button--filled">
                    {{ __('common.moderation-reject') }}
                </button>
                <button formmethod="dialog" formnovalidate class="form__button form__button--outlined">
                    {{ __('common.cancel') }}
                </button>
            </p>
        </form>
    </dialog>
</li>
