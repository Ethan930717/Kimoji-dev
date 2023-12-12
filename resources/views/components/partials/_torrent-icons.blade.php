<span class="torrent-icons">
    @if ($torrent->seeding)
        <i class="{{ config('other.font-awesome') }} fa-arrow-circle-up text-success torrent-icons" title="{{ __('torrent.currently-seeding') }}"></i>
    @endif
    @if ($torrent->leeching)
        <i class="{{ config('other.font-awesome') }} fa-arrow-circle-down text-danger torrent-icons" title="{{ __('torrent.currently-leeching') }}"></i>
    @endif
    @if ($torrent->not_completed)
        <i class="{{ config('other.font-awesome') }} fa-do-not-enter text-info torrent-icons" title="{{ __('torrent.not-completed') }}"></i>
    @endif
    @if ($torrent->not_seeding)
        <i class="{{ config('other.font-awesome') }} fa-thumbs-down text-warning torrent-icons" title="{{ __('torrent.completed-not-seeding') }}"></i>
    @endif
    @isset($torrent->thanks_count)
        <i class="{{ config('other.font-awesome') }} fa-heartbeat torrent-icons__thanks">{{ $torrent->thanks_count }}</i>
    @endisset
    @isset($torrent->comments_count)
        <a href="{{ route('torrents.show', ['id' => $torrent->id]) }}#comments">
            <i class="{{ config('other.font-awesome') }} fa-comment-alt-lines torrent-icons__comments">{{ $torrent->comments_count }}</i>
        </a>
    @endisset
    @if ($torrent->internal)
        <i
            class="{{ config('other.font-awesome') }} fa-magic torrent-icons__internal"
            title="{{ __('torrent.internal-release') }}"
        ></i>
    @endif
    @if ($torrent->personal_release)
        <i
            class="{{ config('other.font-awesome') }} fa-user-plus torrent-icons__personal-release"
            title="{{ __('torrent.personal-release') }}"
        ></i>
    @endif
    @if ($torrent->stream)
        <i
            class="{{ config('other.font-awesome') }} fa-play torrent-icons__stream-optimized"
            title="{{ __('torrent.stream-optimized') }}"
        ></i>
    @endif
    @if ($torrent->featured)
        <i
            class="{{ config('other.font-awesome') }} fa-certificate torrent-icons__featured"
            title="{{ __('torrent.featured') }}"
        ></i>
    @endif
        @php
            $alwaysFreeleech = $personalFreeleech || $torrent->freeleechTokens_exists || auth()->user()->group->is_freeleech || config('other.freeleech');
            $titleText = "";
            if ($personalFreeleech) {
                $titleText .= __('torrent.personal-freeleech') . "\n";
            }
            if ($torrent->freeleechTokens_exists) {
                $titleText .= __('torrent.freeleech-token') . "\n";
            }
            if (auth()->user()->group->is_freeleech) {
                $titleText .= __('torrent.special-freeleech') . "\n";
            }
            if (config('other.freeleech')) {
                $titleText .= __('torrent.global-freeleech') . "\n";
            }
            if ($torrent->free > 0) {
                $titleText .= $torrent->free . '% FREE ';
                if ($torrent->fl_until !== null) {
                    // 计算剩余时间
                    $expiresIn = $torrent->fl_until->diffForHumans(null, true);
                    $expiresIn = str_replace(['天', '小时', '分钟'], ['d', 'h', 'm'], $expiresIn);
                    // 添加剩余时间到titleText
                    $titleText .= " in " . $expiresIn;
                }
            }
        @endphp

            @if ($alwaysFreeleech || $torrent->free)
                 {{--   <i
                            @class([
                                'torrent-icons__freeleech '.config('other.font-awesome'),
                                'fa-star' => $alwaysFreeleech || (90 <= $torrent->free && $torrent->fl_until === null),
                                'fa-star-half' => ! $alwaysFreeleech && $torrent->free < 90 && $torrent->fl_until === null,
                                'fa-calendar-star' => ! $alwaysFreeleech && $torrent->fl_until !== null,
                            ])
                            title="{{ $titleText }}"
                    ></i>--}}
                    <span class="freeleech-title">{{ Str::limit($titleText, 150, '...') }}</span>
            @endif
        @if (config('other.doubleup') || auth()->user()->group->is_double_upload || $torrent->doubleup)
            <span class="doubleup-title">
                DoubleUp
            @if($torrent->doubleup > 0 && $torrent->du_until !== null)
                        <?php
                        // 获取剩余时间
                        $duExpiresIn = $torrent->du_until->diffForHumans(null, true);
                        // 将中文单位转换为英文简写
                        $duExpiresIn = str_replace(['天', '小时', '分钟'], ['d', 'h', 'm'], $duExpiresIn);
                        ?>
                    in {{ $duExpiresIn }}
          @endif
            </span>
        @endif
    @if ($torrent->refundable || auth()->user()->group->is_refundable)
<!--        <i class="{{ config('other.font-awesome') }} fa-percentage"
           title='{{ __('torrent.refundable') }}'>
        </i>-->
            <span class="refundable-title">{{ __('torrent.refundable') }}</span>

    @endif
    @if ($torrent->sticky)
        <i
            class="{{ config('other.font-awesome') }} fa-thumbtack torrent-icons__sticky"
            title="{{ __('torrent.sticky') }}"
        ></i>
    @endif
    @if ($torrent->highspeed)
        <i
            class="{{ config('other.font-awesome') }} fa-tachometer torrent-icons__highspeed"
            title="{{ __('common.high-speeds') }}"
        ></i>
    @endif
    @if ($torrent->sd)
        <i
            class="{{ config('other.font-awesome') }} fa-solod fa-child"
            title="{{ __('torrent.sd-content') }}"
        ></i>
    @endif
    @if ($torrent->bumped_at != $torrent->created_at && $torrent->bumped_at < Illuminate\Support\Carbon::now()->addDay(2))
        <i
            class="{{ config('other.font-awesome') }} fa-level-up-alt torrent-icons__bumped"
            title="{{ __('torrent.recent-bumped') }}: {{ $torrent->bumped_at }}"
        ></i>
    @endif
</span>
