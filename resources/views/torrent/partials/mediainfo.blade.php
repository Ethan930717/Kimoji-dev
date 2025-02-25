<div class="panelV2" x-data="{ show: false }">
    <header class="panel__header" style="cursor: pointer;" @click="show = !show">
        <h2 class="panel__heading">
            <i class="{{ config("other.font-awesome") }} fa-info-square"></i>
            <i class="{{ config("other.font-awesome") }} fa-plus-circle fa-pull-right" x-show="!show"></i>
            <i class="{{ config("other.font-awesome") }} fa-minus-circle fa-pull-right" x-show="show" x-cloak></i>
            MediaInfo
        </h2>
        <div class="panel__actions">
            <div class="panel__action">
                <button
                    class="form__button form__button--text"
                    x-data
                    x-on:click.stop="
                        navigator.clipboard.writeText($refs.mediainfo.textContent);
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
                    复制
                </button>
            </div>
        </div>
    </header>
    <div class="panel__body">
        <div class="torrent-mediainfo-dump bbcode-rendered" x-cloak x-show="show">
            <pre><code x-ref="mediainfo">{{ $torrent->mediainfo }}</code></pre>
        </div>
        <section class="mediainfo">
            <section class="mediainfo__filename">
                <h3>文件名</h3>
                {{ $mediaInfo['general']['file_name'] ?? __('common.unknown') }}
            </section>
            <section class="mediainfo__general">
                <h3>常规</h3>
                <dl>
                    <dt>格式</dt>
                    <dd>{{ $mediaInfo['general']['format'] ?? __('common.unknown') }}</dd>
                    <dt>时长</dt>
                    <dd>{{ $mediaInfo['general']['duration'] ?? __('common.unknown') }}</dd>
                    <dt>码率</dt>
                    <dd>{{ $mediaInfo['general']['bit_rate'] ?? __('common.unknown') }}</dd>
                    <dt>体积</dt>
                    <dd>{{ App\Helpers\StringHelper::formatBytes($mediaInfo['general']['file_size'] ?? 0, 2) }}</dd>
                </dl>
            </section>
            @if ($mediaInfo !== null)
                @isset($mediaInfo['video'])
                    <section class="mediainfo__video">
                        <h3>视频</h3>
                        @foreach ($mediaInfo['video'] as $key => $videoElement)
                            <article>
                                <h4>#{{ ++$key }}</h4>
                                <dl>
                                    <dt>格式</dt>
                                    <dd>{{ $videoElement['format'] ?? __('common.unknown') }} ({{ $videoElement['bit_depth'] ?? __('common.unknown') }})</dd>
                                    <dt>分辨率</dt>
                                    <dd>{{ $videoElement['width'] ?? __('common.unknown') }} &times; {{ $videoElement['height'] ?? __('common.unknown') }}</dd>
                                    <dt>宽高比</dt>
                                    <dd>{{ $videoElement['aspect_ratio'] ?? __('common.unknown') }}</dd>
                                    <dt>帧率</dt>
                                    <dd>
                                        @if(isset($videoElement['framerate_mode']) && $videoElement['framerate_mode'] === 'Variable')
                                            可变帧率
                                        @else
                                            {{ $videoElement['frame_rate'] ?? __('common.unknown') }}
                                        @endif
                                    </dd>
                                    <dt>码率</dt>
                                    <dd>{{ $videoElement['bit_rate'] ?? __('common.unknown') }}</dd>
                                    @if (isset($videoElement['format']) && $videoElement['format'] === 'HEVC')
                                        <dt>HDR格式</dt>
                                        <dd>{{ $videoElement['hdr_format'] ?? __('common.unknown') }}</dd>
                                        <dt>原色</dt>
                                        <dd>{{ $videoElement['color_primaries'] ?? __('common.unknown') }}</dd>
                                        <dt>转移特性</dt>
                                        <dd>{{ $videoElement['transfer_characteristics'] ?? __('common.unknown') }}</dd>
                                    @endisset
                                </dl>
                            </article>
                        @endforeach
                    </section>
                @endisset
                @isset($mediaInfo['audio'])
                    <section class="mediainfo__audio">
                        <h3>音频</h3>
                        <dl>
                            @foreach ($mediaInfo['audio'] as $key => $audioElement)
                                <dt>{{ $loop->iteration }}.</dt>
                                <dd>
                                    <img
                                        src="{{ language_flag($audioElement['language'] ?? __('common.unknown')) }}"
                                        alt="{{ $audioElement['language'] ?? __('common.unknown') }}"
                                        width="20"
                                        height="13"
                                        title="{{ $audioElement['language'] ?? __('common.unknown') }}"
                                    />
                                    {{ $audioElement['language'] ?? __('common.unknown') }}
                                    / {{ $audioElement['format'] ?? __('common.unknown') }}
                                    / {{ $audioElement['channels'] ?? __('common.unknown') }}
                                    / {{ $audioElement['bit_rate'] ?? __('common.unknown') }}
                                    / {{ $audioElement['title'] ?? __('common.unknown') }}
                                </dd>
                            @endforeach
                        </dl>
                    </section>
                @endisset
                @isset($mediaInfo['text'])
                    <section class="mediainfo__subtitles">
                        <h3>字幕</h3>
                        <ul>
                            @foreach ($mediaInfo['text'] as $key => $textElement)
                                <li>
                                    <img
                                        src="{{ language_flag($textElement['language'] ?? __('common.unknown')) }}"
                                        alt="{{ $textElement['language'] ?? __('common.unknown') }}"
                                        width="20"
                                        height="13"
                                        title="{{ $textElement['language'] ?? __('common.unknown') }} | {{ $textElement['format'] ?? __('common.unknown') }} | {{ $textElement['title'] ?? __('common.unknown') }}"
                                    />
                                </li>
                            @endforeach
                        </ul>
                    </section>
                @endisset
                @isset($mediaInfo['video'], array_merge(... $mediaInfo['video'])['encoding_settings'])
                    <section class="mediainfo__encode-settings">
                        <h3>编码参数</h3>
                        @foreach ($mediaInfo['video'] as $key => $videoElement)
                            @isset($videoElement['encoding_settings'])
                                <article>
                                    <h4>#{{ $key }}</h4>
                                    <div class="bbcode-rendered">
                                        <pre><code>{{ $videoElement['encoding_settings'] ?? __('common.unknown') }}</code></pre>
                                    </div>
                                </article>
                            @endisset
                        @endforeach
                    </section>
                @endisset
            @endif
        </section>
    </div>
</div>