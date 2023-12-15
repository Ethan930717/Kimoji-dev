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

    {{-- Salutation --}}
    @if (! empty($salutation))
        {{ $salutation }}
    @else
    KIMOJI 敬上
    @endif

    {{-- Subcopy --}}
    @isset($actionText)
        @slot('subcopy')
            {{ __(
                "如果您无法点击按钮, 请尝试访问以下链接:\n\n",
                [
                    'actionText' => $actionText,
                ]
            ) }} <span class="break-all">[{{ $displayableActionUrl }}]({{ $actionUrl }})</span>
        @endslot
    @endisset
@endcomponent
