@component('mail::message')
    {{-- Greeting --}}
    @if (! empty($greeting))
        # {{ $greeting }}
    @else
        @if ($level === 'error')
            # {{ __('哎呀') }}
        @else
            # {{ __('您好') }}
        @endif
    @endif

    {{-- Intro Lines --}}
    欢迎您加入KIMOJI，请点击下面的按钮来验证您的电子邮件地址。

    @endforeach

    {{-- Action Button --}}
    @isset($actionText)
            <?php
            switch ($level) {
                case 'success':
                case 'error':
                    $color = $level;
                    break;
                default:
                    $color = 'primary';
            }
            ?>
        @component('mail::button', ['url' => $actionUrl, 'color' => $color])
            {{ $actionText }}
        @endcomponent
    @endisset

    {{-- Outro Lines --}}
    如果您不知晓本条邮件所述内容，请您无需理会

    @endforeach

    {{-- Salutation --}}
    @if (! empty($salutation))
        {{ $salutation }}
    @else
        {{ config('app.name') }}
    @endif

    {{-- Subcopy --}}
    @isset($actionText)
        @slot('subcopy')
            {{ __(
                "如果您无法点击 \":actionText\" 按钮, 请手动访问链接:\n\n",
                [
                    'actionText' => $actionText,
                ]
            ) }} <span class="break-all">[{{ $displayableActionUrl }}]({{ $actionUrl }})</span>
        @endslot
    @endisset
@endcomponent