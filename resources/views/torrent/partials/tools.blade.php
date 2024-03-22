<section class="panelV2">
    <h2 class="panel__heading">
        <i class="{{ config("other.font-awesome") }} fa-hammer-war"></i> {{ __('torrent.moderation') }}
    </h2>
    <div class="panel__body">
        <menu style="display: flex; justify-content: space-between; padding: 0; margin: 0; list-style-type: none; flex-wrap: wrap">
            @if (auth()->user()->group->is_modo || auth()->id() === $torrent->user_id)
                <li>
                    <menu style="display: flex; list-style-type: none; margin: 0; padding: 0; flex-wrap: wrap;">
                        <li>
                            <a class="form__button form__button--outlined" href="{{ route('torrents.edit', ['id' => $torrent->id]) }}"
                                role="button" style="display:flex;">
                                <i class="{{ config('other.font-awesome') }} fa-pencil-alt"></i> {{ __('common.edit') }}
                            </a>
                        </li>
                        @if (auth()->user()->group->is_modo || ( auth()->id() === $torrent->user_id && Illuminate\Support\Carbon::now()->lt($torrent->created_at->addDay())))
                            <li x-data>
                                <button class="form__button form__button--outlined" x-on:click.stop="$refs.dialog.showModal()">
                                    <i class="{{ config('other.font-awesome') }} fa-times"></i> {{ __('common.delete') }}
                                </button>
                                <dialog class="dialog" x-ref="dialog">
                                    <h4 class="dialog__heading">
                                        {{ __('common.delete') }}: {{ $torrent->name }}
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
                                        <input id="type" name="type" type="hidden" value="Torrent">
                                        <input id="id" name="id" type="hidden" value="{{ $torrent->id }}">
                                        <input id="title" name="title" type="hidden" value="{{ $torrent->name }}">
                                        <div class="form__group">
                                            <button type="button" class="form__button--mod" @click="appendMessage('不接受任何分辨率在720p以下的资源！请仔细阅读[url=https://kimoji.club/pages/3]发布规则[/url]\\n')">720P</button>
                                            <button type="button" class="form__button--mod" @click="appendMessage('不接受任何除官组外的分集资源！请仔细阅读[url=https://kimoji.club/pages/3]发布规则[/url]\\n')">分集</button>
                                            <button type="button" class="form__button--mod" @click="appendMessage('不接受带台标的资源！请仔细阅读[url=https://kimoji.club/pages/3]发布规则[/url]\\n')">台标</button>
                                            <button type="button" class="form__button--mod" @click="appendMessage('除特许发布的极优资源外，不接受任何形式的打包资源！请仔细阅读[url=https://kimoji.club/pages/3]发布规则[/url]\\n')">打包</button>
                                            <button type="button" class="form__button--mod" @click="appendMessage('当前资源已丢种或有更优质的同类资源，需要重发或补档\\n')">补档重发</button>
                                        </div>
                                        <p class="form__group">
                                            <textarea
                                                id="message"
                                                class="form__textarea"
                                                name="message"
                                                required
                                                x-model="message"
                                            ></textarea>
                                            <label for="message" class="form__label form__label--floating">
                                                {{ __('common.reason') }}
                                            </label>
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
                        @endif
                    </menu>
                </li>
            @endif
            @if (auth()->user()->group->is_modo || auth()->user()->group->is_internal)
                <menu style="display: flex; list-style-type: none; margin: 0; padding: 0; flex-wrap: wrap;">
                    <li x-data>
                        <button class="form__button form__button--outlined" x-on:click.stop="$refs.dialog.showModal()">
                            <i class="{{ config('other.font-awesome') }} fa-star"></i> Free
                        </button>
                        <dialog class="dialog" x-ref="dialog">
                            <h4 class="dialog__heading">
                                Edit
                            </h4>
                            <div x-on:click.outside="$refs.dialog.close()">
                                <form
                                    class="dialog__form"
                                    action="{{ route('torrent_fl', ['id' => $torrent->id]) }}"
                                    method="POST"
                                >
                                    @csrf
                                    <p class="form__group">
                                        <select id="freeleech" name="freeleech" class="form__select">
                                            <option value="0" @selected($torrent->free === 0)>No</option>
                                            <option value="25" @selected($torrent->free === 25)>25%</option>
                                            <option value="50" @selected($torrent->free === 50)>50%</option>
                                            <option value="75" @selected($torrent->free === 75)>75%</option>
                                            <option value="100" @selected($torrent->free === 100)>100%</option>
                                        </select>
                                        <label class="form__label form__label--floating" for="freeleech">
                                            设置免费
                                        </label>
                                    </p>
                                    <p class="form__group">
                                        <select id="fl_until" class="form__select" name="fl_until">
                                            <option value="">永久</option>
                                            <option value="1">1 Day</option>
                                            <option value="2">2 Days</option>
                                            <option value="3">3 Days</option>
                                            <option value="4">4 Days</option>
                                            <option value="5">5 Days</option>
                                            <option value="6">6 Days</option>
                                            <option value="7">7 Days</option>
                                        </select>
                                        <label for="fl_until" class="form__label form__label--floating" for="fl_until">
                                            持续时间
                                        </label>
                                    </p>
                                    <p class="form__group">
                                        <button class="form__button form__button--filled">
                                            {{ __('common.save') }}
                                        </button>
                                        <button formmethod="dialog" formnovalidate class="form__button form__button--outlined">
                                            {{ __('common.cancel') }}
                                        </button>
                                    </p>
                                </form>
                            </div>
                        </dialog>
                    </li>
                    <li x-data>
                        <button class="form__button form__button--outlined" x-on:click.stop="$refs.dialog.showModal()">
                            <i class="{{ config('other.font-awesome') }} fa-chevron-double-up"></i> Double
                        </button>
                        <dialog class="dialog" x-ref="dialog">
                            <h4 class="dialog__heading">
                                Edit
                            </h4>
                            <div x-on:click.outside="$refs.dialog.close()">
                                <form
                                    class="dialog__form"
                                    action="{{ route('torrent_doubleup', ['id' => $torrent->id]) }}"
                                    method="POST"
                                >
                                    @csrf
                                    <p class="form__group">
                                        <select id="du_until" class="form__select" name="du_until">
                                            <option value="">永久</option>
                                            <option value="1">1 Day</option>
                                            <option value="2">2 Days</option>
                                            <option value="3">3 Days</option>
                                            <option value="4">4 Days</option>
                                            <option value="5">5 Days</option>
                                            <option value="6">6 Days</option>
                                            <option value="7">7 Days</option>
                                        </select>
                                        <label class="form__label form__label--floating" for="du_until">
                                            持续时间
                                        </label>
                                    </p>
                                    <p class="form__group">
                                        <button class="form__button form__button--filled">
                                            {{ __('common.save') }}
                                        </button>
                                        <button formmethod="dialog" formnovalidate class="form__button form__button--outlined">
                                            {{ __('common.cancel') }}
                                        </button>
                                    </p>
                                </form>
                            </div>
                        </dialog>
                    </li>
                    <li>
                        @if ($torrent->refundable == 0)
                            <form action="{{ route('refundable', ['id' => $torrent->id]) }}"
                                  method="POST"
                                  style="display: inline;">
                                @csrf
                                <button type="submit" class="form__button form__button--outlined">
                                    <i class="{{ config('other.font-awesome') }} fa-repeat"></i> {{ __('torrent.refundable') }}
                                </button>
                            </form>
                        @else
                            <form action="{{ route('refundable', ['id' => $torrent->id]) }}"
                                  method="POST"
                                  style="display: inline;">
                                @csrf
                                <button type="submit" class="form__button form__button--outlined">
                                    <i class="{{ config('other.font-awesome') }} fa-repeat"></i> {{ __('torrent.revoke') }} {{ __('torrent.refundable') }}
                                </button>
                            </form>
                        @endif
                    </li>
                    <li>
                        @if ($torrent->sticky == 0)
                            <form
                                action="{{ route('torrent_sticky', ['id' => $torrent->id]) }}"
                                method="POST"
                                style="display: inline;"
                            >
                                @csrf
                                <button class="form__button form__button--outlined">
                                    <i class="{{ config('other.font-awesome') }} fa-thumbtack"></i> {{ __('torrent.sticky') }}
                                </button>
                            </form>
                        @else
                            <form
                                action="{{ route('torrent_sticky', ['id' => $torrent->id]) }}"
                                method="POST"
                                style="display: inline;"
                            >
                                @csrf
                                <button class="form__button form__button--outlined">
                                    <i class="{{ config('other.font-awesome') }} fa-thumbtack"></i> {{ __('torrent.unsticky') }}
                                </button>
                            </form>
                        @endif
                    </li>
                    <li>
                        <form
                            action="{{ route('bumpTorrent', ['id' => $torrent->id]) }}"
                            method="POST"
                            style="display: inline;"
                        >
                            @csrf
                            <button class="form__button form__button--outlined">
                                <i class="{{ config('other.font-awesome') }} fa-arrow-to-top"></i> {{ __('torrent.bump') }}
                            </button>
                        </form>
                    </li>
                    <li>
                        @if ($torrent->featured == 0)
                            <form
                                method="POST"
                                action="{{ route('torrent_feature', ['id' => $torrent->id]) }}"
                                style="display: inline-block;"
                            >
                                @csrf
                                <button class="form__button form__button--outlined">
                                    <i class='{{ config('other.font-awesome') }} fa-certificate'></i> {{ __('torrent.feature') }}
                                </button>
                            </form>
                        @else
                            <form
                                method="POST"
                                action="{{ route('torrent_revokefeature', ['id' => $torrent->id]) }}"
                                style="display: inline-block;"
                            >
                                @csrf
                                <button class="form__button form__button--outlined">
                                    <i class='{{ config('other.font-awesome') }} fa-certificate'></i> {{ __('torrent.revokefeatured') }}
                                </button>
                            </form>
                        @endif
                    </li>
                </menu>
            @endif
            @if (auth()->user()->group->is_modo)
                <menu style="display: flex; list-style-type: none; margin: 0; padding: 0; align-items: center; flex-wrap: wrap;">
                    @if ($torrent->status !== \App\Models\Torrent::APPROVED)
                        <li>
                            <form role="form" method="POST"
                                    action="{{ route('staff.moderation.update', ['id' => $torrent->id]) }}"
                                    style="display: inline-block;">
                                @csrf
                                <input type="hidden" name="old_status" value="{{ $torrent->status }}">
                                <input type="hidden" name="status" value="{{ \App\Models\Torrent::APPROVED }}">
                                <button class="form__button form__button--outlined">
                                    <i class="{{ config('other.font-awesome') }} fa-thumbs-up"></i> {{ __('common.moderation-approve') }}
                                </button>
                            </form>
                        </li>
                    @endif
                    @if ($torrent->status !== \App\Models\Torrent::POSTPONED)
                        <li x-data>
                            <button class="form__button form__button--outlined" x-on:click.stop="$refs.dialog.showModal()">
                                <i class="{{ config('other.font-awesome') }} fa-pause"></i> {{ __('common.moderation-postpone') }}
                            </button>
                            <dialog class="dialog" x-ref="dialog">
                                <h4 class="dialog__heading">
                                    {{ __('common.moderation-postpone') }}: {{ $torrent->name }}
                                </h4>
                                <form
                                    class="dialog__form"
                                    method="POST"
                                    action="{{ route('staff.moderation.update', ['id' => $torrent->id]) }}"
                                    x-on:click.outside="$refs.dialog.close()"
                                    x-data="{
                                            message: '',
                                            appendMessage(newMessage) {
                                                this.message += newMessage.replace(/\\n/g, '\n');
                                            }
                                        }"
                                >
                                    @csrf
                                    <input id="type" name="type" type="hidden" value="{{ __('torrent.torrent') }}">
                                    <input id="id" name="id" type="hidden" value="{{ $torrent->id }}">
                                    <input type="hidden" name="old_status" value="{{ $torrent->status }}">
                                    <input type="hidden" name="status" value="{{ \App\Models\Torrent::POSTPONED }}">
                                    <div class="form__group">
                                        <button type="button" class="form__button--mod" @click="appendMessage('规范主标题命名，详见 [url=https://kimoji.club/pages/3]发布规则[/url]\\n')">标题命名</button>
                                        <button type="button" class="form__button--mod" @click="appendMessage('请提交完整的 Mediainfo 扫描信息\\n')">Mediainfo</button>
                                        <button type="button" class="form__button--mod" @click="appendMessage('原盘请提供BDinfo，详见[url=https://kimoji.club/pages/4]原盘发布规则[/url]\\n')">BDinfo</button>
                                        <button type="button" class="form__button--mod" @click="appendMessage('请检查基本信息填写：类别/媒介等\\n')">基本信息</button>
                                        <button type="button" class="form__button--mod" @click="appendMessage('请补充TMDb/IMDb信息\\n')">T/IMDb</button>
                                        <button type="button" class="form__button--mod" @click="appendMessage('提供至少三张 BBCODE 格式的截图（非缩略图），原盘则需提供PNG原图\\n')">提供截图</button>
                                        <button type="button" class="form__button--mod" @click="appendMessage('视频截图无法正常显示，请更换图床\\n')">更换图床</button>
                                        <button type="button" class="form__button--mod" @click="appendMessage('请按固定的格式编辑描述信息，详见[url=https://kimoji.club/pages/3]发布规则[/url]或参考已发布的资源\\n')">描述格式</button>
                                    </div>
                                    <p class="form__group">
                                        <textarea
                                            id="message"
                                            class="form__textarea"
                                            name="message"
                                            required
                                            x-model="message"
                                        ></textarea>
                                        <label for="report_reason" class="form__label form__label--floating">
                                            {{ __('common.reason') }}
                                        </label>
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
                    @endif
                    @if ($torrent->status !== \App\Models\Torrent::REJECTED)
                        <li x-data>
                            <button class="form__button form__button--outlined" x-on:click.stop="$refs.dialog.showModal()">
                                <i class="{{ config('other.font-awesome') }} fa-fw fa-thumbs-down"></i> {{ __('common.moderation-reject') }}
                            </button>
                            <dialog class="dialog" x-ref="dialog">
                                <h4 class="dialog__heading">
                                    {{ __('common.moderation-reject') }}: {{ $torrent->name }}
                                </h4>
                                <form
                                    class="dialog__form"
                                    method="POST"
                                    action="{{ route('staff.moderation.update', ['id' => $torrent->id]) }}"
                                    x-on:click.outside="$refs.dialog.close()"
                                    x-data="{
                                                message: '',
                                                appendMessage(newMessage) {
                                                    this.message += newMessage.replace(/\\n/g, '\n');
                                                }
                                            }"
                                >
                                    @csrf
                                    <input id="type" name="type" type="hidden" value="{{ __('torrent.torrent') }}">
                                    <input id="id" name="id" type="hidden" value="{{ $torrent->id }}">
                                    <input type="hidden" name="old_status" value="{{ $torrent->status }}">
                                    <input type="hidden" name="status" value="{{ \App\Models\Torrent::REJECTED }}">
                                    <div class="form__group">
                                        <button type="button" class="form__button--mod" @click="appendMessage('规范主标题命名，详见 [url=https://kimoji.club/pages/3]发布规则[/url]\\n')">标题命名</button>
                                        <button type="button" class="form__button--mod" @click="appendMessage('请提交完整的 Mediainfo 扫描信息\\n')">Mediainfo</button>
                                        <button type="button" class="form__button--mod" @click="appendMessage('原盘请提供BDinfo，详见[url=https://kimoji.club/pages/4]原盘发布规则[/url]\\n')">BDinfo</button>
                                        <button type="button" class="form__button--mod" @click="appendMessage('请检查基本信息填写：类别/媒介等\\n')">基本信息</button>
                                        <button type="button" class="form__button--mod" @click="appendMessage('请补充TMDb/IMDb信息\\n')">T/IMDb</button>
                                        <button type="button" class="form__button--mod" @click="appendMessage('提供至少三张 BBCODE 格式的截图（非缩略图），原盘则需提供PNG原图\\n')">提供截图</button>
                                        <button type="button" class="form__button--mod" @click="appendMessage('视频截图无法正常显示，请更换图床\\n')">更换图床</button>
                                        <button type="button" class="form__button--mod" @click="appendMessage('请按固定的格式编辑描述信息，详见[url=https://kimoji.club/pages/3]发布规则[/url]或参考已发布的资源\\n')">描述格式</button>
                                    </div>
                                    <p class="form__group">
                                        <textarea
                                            id="message"
                                            class="form__textarea"
                                            name="message"
                                            required
                                            x-model="message"
                                        ></textarea>
                                        <label for="report_reason" class="form__label form__label--floating">
                                            {{ __('common.reason') }}
                                        </label>
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
                    @endif
                    <li>
                        @switch ($torrent->status)
                            @case(\App\Models\Torrent::APPROVED)
                                批准人: <x-user_tag :user="$torrent->moderated" :anon="false" />
                                @break
                            @case(\App\Models\Torrent::POSTPONED)
                                延期人: <x-user_tag :user="$torrent->moderated" :anon="false" />
                                @break
                            @case(\App\Models\Torrent::REJECTED)
                                拒收人: <x-user_tag :user="$torrent->moderated" :anon="false" />
                                @break
                            @default
                                未处理
                        @endswitch
                    </li>
                </menu>
            @endif
        </menu>
    </div>
</section>
