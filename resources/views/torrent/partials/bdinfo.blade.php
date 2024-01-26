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
        <section class="mediainfo">
            <section class="mediainfo__filename">
                <h3>文件名</h3>
                <dd>{{ $bdInfo['disc_title'] ?? __('common.unknown') }}</dd>
            </section>
            <section class="mediainfo__general">
                <h3>常规</h3>
                <dl>
                    <dt>体积</dt>
                    <dd>{!! nl2br(e($bdInfo['disc_size'] ?? __('common.unknown'))) !!}</dd>

                    @if (!empty($bdInfo['disc_label']))
                        <dt>标签</dt>
                        <dd>{!! nl2br(e($bdInfo['disc_label'])) !!}</dd>
                    @endif

                    <dt>整体码率</dt>
                    <dd>{!! nl2br(e($bdInfo['total_bitrate'] ?? __('common.unknown'))) !!}</dd>
                </dl>

            </section>

            @if(!empty($bdInfo['video']))
                <section class="bdinfo__video">
                    <h3>视频信息</h3>
                    <article>
                        <dl>
                            <dt>格式</dt>
                            <dd>{{ $bdInfo['video']['format'] ?? __('common.unknown') }}</dd>
                            <dt>码率</dt>
                            <dd>{{ $bdInfo['video']['bitrate'] ?? __('common.unknown') }}</dd>
                            <dt>分辨率</dt>
                            <dd>{{ $bdInfo['video']['resolution'] ?? __('common.unknown') }}</dd>
                            <dt>帧率</dt>
                            <dd>{{ $bdInfo['video']['frame_rate'] ?? __('common.unknown') }}</dd>
                            <dt>宽高比</dt>
                            <dd>{{ $bdInfo['video']['aspect_ratio'] ?? __('common.unknown') }}</dd>
                            <dt>编码级别</dt>
                            <dd>{{ $bdInfo['video']['profile_level'] ?? __('common.unknown') }}</dd>
                        </dl>
                    </article>
                </section>
            @endif


            @if(!empty($bdInfo['audio']) && is_array($bdInfo['audio']))
                <section class="bdinfo__audio">
                    <h3>音频信息</h3>
                    <ul>
                        @foreach($bdInfo['audio'] as $audioData)
                            <li>
                                @if(isset($audioData['country_code']) && $audioData['country_code'])
                                    <img src="/img/flags/{{ $audioData['country_code'] }}.png"
                                         alt="{{ $audioData['country_code'] }}"
                                         width="20"
                                         height="13"
                                         title="{{ $audioData['country_code'] }}"
                                    />
                                @endif
                                {{ $audioData['info'] }}
                            </li>
                        @endforeach
                    </ul>
                </section>
            @endif

            @if(!empty($bdInfo['subtitles']) && is_array($bdInfo['subtitles']))
                <section class="bdinfo__subtitles">
                    <h3>字幕信息</h3>
                    <dl>
                        @foreach($bdInfo['subtitles'] as $subtitleData)
                            <dd>
                                @if(isset($subtitleData['country_code']) && $subtitleData['country_code'])
                                    <img src="/img/flags/{{ $subtitleData['country_code'] }}.png"
                                         alt="{{ $subtitleData['country_code'] }}"
                                         width="20"
                                         height="13"
                                         title="{{ $subtitleData['country_code'] }}"
                                    />
                                @endif
                            </dd>
                        @endforeach
                    </dl>
                </section>
            @endif


        </section>
    </div>
</div>
