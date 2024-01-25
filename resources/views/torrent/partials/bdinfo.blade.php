<div class="panelV2" x-data="{ show: false }">
    <header class="panel__header" style="cursor: pointer;" @click="show = !show">
        <h2 class="panel__heading">
            <i class="{{ config('other.font-awesome') }} fa-compact-disc"></i> BDInfo
            <i class="{{ config('other.font-awesome') }} fa-plus-circle fa-pull-right" x-show="!show"></i>
            <i class="{{ config('other.font-awesome') }} fa-minus-circle fa-pull-right" x-show="show" x-cloak></i>
        </h2>
        <div class="panel__actions">
            <div class="panel__action">
                <button class="form__button form__button--text" x-data x-on:click.stop="navigator.clipboard.writeText($refs.bdinfo.textContent); Swal.fire({toast: true, position: 'top-end', showConfirmButton: false, timer: 3000, icon: 'success', title: '复制成功'})">
                    复制
                </button>
            </div>
        </div>
    </header>
    <div class="panel__body">
        <div class="torrent-bdinfo-dump bbcode-rendered" x-cloak x-show="show">
            <pre><code x-ref="bdinfo">{{ $torrent->bdinfo }}</code></pre>
        </div>
        <section class="bdinfo">
            <section class="bdinfo__filename">
                <h3>文件名</h3>
                <dd>{{ $bdInfo['disc_info'] ?? __('common.unknown') }}</dd>
            </section>
            <section class="bdinfo__general">
                <h3>常规</h3>
                <dl>
                    <dt>体积</dt>
                    <dd>{!! nl2br(e($bdInfo['disc_size'] ?? __('common.unknown'))) !!}</dd>
                    <dt>标签</dt>
                    <dd>{!! nl2br(e($bdInfo['disc_label'] ?? __('common.unknown'))) !!}</dd>
                    <dt>总码率</dt>
                    <dd>{!! nl2br(e($bdInfo['total_bitrate'] ?? __('common.unknown'))) !!}</dd>
                </dl>
            </section>

            @if(isset($bdInfo['video']) && is_string($bdInfo['video']))
                <section class="bdinfo__video">
                    <h3>视频信息</h3>
                    <p>{!! nl2br(e($bdInfo['video'])) !!}</p>
                </section>
            @endif

            @if(isset($bdInfo['audio']) && is_string($bdInfo['audio']))
                <section class="bdinfo__audio">
                    <h3>音频信息</h3>
                    <p>{!! nl2br(e($bdInfo['audio'])) !!}</p>
                </section>
            @endif

            @if(isset($bdInfo['subtitles']) && is_string($bdInfo['subtitles']))
                <section class="bdinfo__subtitles">
                    <h3>字幕信息</h3>
                    <p>{!! nl2br(e($bdInfo['subtitles'])) !!}</p>
                </section>
            @endif

        </section>
    </div>
</div>
