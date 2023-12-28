<li class="data-table__action" x-data>
    <button class="form__button form__button--filled" x-on:click.stop="$refs.dialog.showModal()">
        <i class="{{ config('other.font-awesome') }} fa-pause"></i>
        {{ __('common.moderation-postpone') }}
    </button>
    <dialog class="dialog" x-ref="dialog">
        <h4 class="dialog__heading">
            {{ __('common.moderation-postpone') }} {{ __('torrent.torrent') }}: {{ $torrent->name }}
        </h4>
        <form
            class="dialog__form"
            method="POST"
            action="{{ route('staff.moderation.update', ['id' => $torrent->id]) }}"
            x-on:click.outside="$refs.dialog.close()"
        >
            @csrf
            <input type="hidden" name="type" value="{{ __('torrent.torrent') }}">
            <input type="hidden" name="id" value="{{ $torrent->id }}">
            <input type="hidden" name="old_status" value="{{ $torrent->status }}">
            <input type="hidden" name="status" value="{{ \App\Models\Torrent::POSTPONED }}">
            <div class="form__group">
                <button type="button" class="form__button--mod" @click="message = '规范主标题命名，详见 [url=https://kimoji.club/pages/3]发布规则[/url]\\n'.replace(/\\n/g, '\n')">标题命名</button>
                <button type="button" class="form__button--mod" @click="message = '请提交完整的 Mediainfo 扫描信息\n'">Mediainfo</button>
                <button type="button" class="form__button--mod" @click="message = '原盘请提供BDinfo，详见[url=https://kimoji.club/pages/4]原盘发布规则[/url]\n'.replace(/\\n/g, '\n')">BDinfo</button>
                <button type="button" class="form__button--mod" @click="message = '请检查基本信息填写：类别/媒介等\n'.replace(/\\n/g, '\n')">基本信息</button>
                <button type="button" class="form__button--mod" @click="message = '请补充TMDb/IMDb信息\n'.replace(/\\n/g, '\n')">T/IMDb</button>
                <button type="button" class="form__button--mod" @click="message = '提供至少三张 BBCODE 格式的截图（非缩略图），原盘则需提供PNG原图\n'.replace(/\\n/g, '\n')">截图</button>
                <button type="button" class="form__button--mod" @click="message = '请按固定的格式编辑描述信息，详见[url=https://kimoji.club/pages/3]发布规则[/url]或参考已发布的资源\n'.replace(/\\n/g, '\n')">描述格式</button>
            </div>
            <p class="form__group">
                <textarea class="form__textarea" name="message" id="message">{{ old('message') }}</textarea>
                <label class="form__label form__label--floating" for="message">Postpone Message</label>
            </p>
            <p class="form__group">
                <button class="form__button form__button--filled">
                    {{ __('common.moderation-postpone') }}
                </button>
                <button formmethod="dialog" formnovalidate class="form__button form__button--outlined">
                    {{ __('common.cancel') }}
                </button>
            </p>
        </form>
    </dialog>
</li>
