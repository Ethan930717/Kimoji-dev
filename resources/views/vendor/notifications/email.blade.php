@component('mail::message')
    {{-- Greeting --}}
    @if (! empty($greeting))
        # {{ $greeting }}
    @else
        @if ($level === 'error')
            # {{ __('哎呀') }}
        @else
            # {{ __('喔唷') }}
        @endif
    @endif

    {{-- Intro Lines --}}
    @foreach ($introLines as $line)
        {{ $line }}

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
    @foreach ($outroLines as $line)
        {{ $line }}

    @endforeach

    {{-- Salutation --}}
    @if (! empty($salutation))
        {{ $salutation }}
    @else
        {{ __('Regards') }},<br>
        {{ config('app.name') }}
    @endif

    {{-- Subcopy --}}
    @isset($actionText)
        @slot('subcopy')
            {{ __(
                "如果您无法点击 \":actionText\" 按钮, 请手动访问链接:\n",
                [
                    'actionText' => $actionText,
                ]
            ) }} <span class="break-all">[{{ $displayableActionUrl }}]({{ $actionUrl }})</span>
        @endslot
    @endisset
@endcomponent