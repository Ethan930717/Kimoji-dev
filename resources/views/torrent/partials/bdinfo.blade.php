<div class="panelV2" x-data="{ show: false }">
    <header class="panel__header" style="cursor: pointer;" @click="show = !show">
        <h2 class="panel__heading">
            <i class="{{ config("other.font-awesome") }} fa-compact-disc"></i>
            BDInfo
            <i class="{{ config("other.font-awesome") }} fa-plus-circle fa-pull-right" x-show="!show"></i>
            <i class="{{ config("other.font-awesome") }} fa-minus-circle fa-pull-right" x-show="show" x-cloak></i>
        </h2>
        <div class="panel__actions">
            <div class="panel__action">
                <button
                    class="form__button form__button--text"
                    x-data
                    x-on:click.stop="
                        navigator.clipboard.writeText($refs.bdinfo.textContent);
                        Swal.fire({
                              toast: true,
                              position: 'top-end',
                              showConfirmButton: false,
                              timer: 3000,
                              icon: 'success',
                              title: '复制成功'
                        })
                    "
                >
                    Copy
                </button>
            </div>
        </div>
    </header>
    <div class="panel__body">
        <div class="torrent-bdinfo-dump bbcode-rendered" x-cloak x-show="show">
            <pre><code x-ref="bdinfo">{{ $torrent->bdinfo }}</code></pre>
        </div>
        <section class="bdinfo">
            <!-- BDInfo参数概览 -->
            <section class="bdinfo__general">
                <h3>常规信息</h3>
                <dl>
                    <dt>文件名</dt>
                    <dd>{{ $bdInfo['general']['file_name'] ?? __('common.unknown') }}</dd>
                    <dt>总体积</dt>
                    <dd>{{ App\Helpers\StringHelper::formatBytes($bdInfo['general']['file_size'] ?? 0, 2) }}</dd>
                    <dt>总时长</dt>
                    <dd>{{ $bdInfo['general']['duration'] ?? __('common.unknown') }}</dd>
                    <!-- 其他常规信息 -->
                </dl>
            </section>
            @isset($bdInfo['video'])
                <section class="bdinfo__video">
                    <h3>视频信息</h3>
                    <!-- 循环处理视频轨道 -->
                    @foreach ($bdInfo['video'] as $video)
                        <article>
                            <h4>视频轨道 #{{ $loop->iteration }}</h4>
                            <dl>
                                <dt>格式</dt>
                                <dd>{{ $video['format'] ?? __('common.unknown') }}</dd>
                                <dt>分辨率</dt>
                                <dd>{{ $video['resolution'] ?? __('common.unknown') }}</dd>
                                <dt>帧率</dt>
                                <dd>{{ $video['frame_rate'] ?? __('common.unknown') }}</dd>
                                <dt>视频编码</dt>
                                <dd>{{ $video['codec'] ?? __('common.unknown') }}</dd>
                                <!-- 其他视频信息 -->
                            </dl>
                        </article>
                    @endforeach
                </section>
            @endisset
            @isset($bdInfo['audio'])
                <section class="bdinfo__audio">
                    <h3>音频信息</h3>
                    <!-- 循环处理音频轨道 -->
                    @foreach ($bdInfo['audio'] as $audio)
                        <article>
                            <h4>音频轨道 #{{ $loop->iteration }}</h4>
                            <dl>
                                <dt>格式</dt>
                                <dd>{{ $audio['format'] ?? __('common.unknown') }}</dd>
                                <dt>频道</dt>
                                <dd>{{ $audio['channels'] ?? __('common.unknown') }}</dd>
                                <dt>语言</dt>
                                <dd>{{ $audio['language'] ?? __('common.unknown') }}</dd>
                                <!-- 其他音频信息 -->
                            </dl>
                        </article>
                    @endforeach
                </section>
            @endisset
            <!-- 根据需要添加其他部分，如字幕信息等 -->
        </section>
    </div>
</div>
