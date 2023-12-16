<section class="panelV2">
    <header class="panel__header">
        <h2 class="panel__heading">{{ __('两步验证') }}</h2>
    </header>
    <div class="panel__body">
        @if ($this->enabled)
            @if ($showingConfirmation)
                <span class="text-warning">{{ __('确认启用两步验证') }}</span>
            @else
                <span class="text-success">{{ __('您已启用两步验证') }}</span>
            @endif
        @else
            <span class="text-danger">{{ __('您尚未启用两步验证') }}</span>
        @endif

        <div>
            <span class="text-muted">
                {{ __('启用两步验证后，在认证期间您将被提示输入一个安全的、随机的令牌。您可以通过手机的Google Authenticator应用程序获取此令牌') }}
            </span>
        </div>

        @if ($this->enabled)
            @if ($showingQrCode)
                <div>
                    <p class="text-info">
                        @if ($showingConfirmation)
                            {{ __('为了完成启用两步验证，请使用手机的认证器应用扫描下面的二维码，或输入设置密钥并提供生成的OTP码') }}
                        @else
                            {{ __('两步验证现已启用。请使用手机的认证器应用扫描下面的二维码或输入设置密钥') }}
                        @endif
                    </p>
                </div>

                <div class="twoStep__qrCode">
                    {!! $this->user->twoFactorQrCodeSvg() !!}
                </div>

                <div>
                    <p>
                        {{ __('设置密钥') }}: {{ decrypt($this->user->two_factor_secret) }}
                    </p>
                </div>

                @if ($showingConfirmation)
                    <div>
                        <label for="code" value="{{ __('验证码') }}"></label>

                        <input id="code"
                               name="code"
                               class="form__text"
                               type="text"
                               inputmode="numeric"
                               autofocus
                               autocomplete="one-time-code"
                               wire:model="code"
                               wire:keydown.enter="confirmTwoFactorAuthentication" />

                        @error('code') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>
                @endif
            @endif

            @if ($showingRecoveryCodes)
                <div class="panel__body">
                    <span class="text-danger">
                        {{ __('请在安全的密码管理器中存储这些恢复码。如果您的两步验证设备丢失，它们可以用来恢复对您账户的访问') }}
                    </span>

                    <pre>
                        @foreach (json_decode(decrypt($this->user->two_factor_recovery_codes), true) as $code)
                            <div>{{ $code }}</div>
                      @endforeach
                    </pre>
                </div>
            @endif
        @endif

        <div>
            @if (! $this->enabled)
                <button class="form__button form__button--filled" wire:click="enableTwoFactorAuthentication" wire:loading.attr="disabled">
                    {{ __('启用') }}
                </button>
            @else
                @if ($showingRecoveryCodes)
                    <button class="form__button form__button--filled" wire:click="regenerateRecoveryCodes">
                        {{ __('重新生成恢复码') }}
                    </button>
                @elseif ($showingConfirmation)
                    <button class="form__button form__button--filled" type="button" wire:click="confirmTwoFactorAuthentication" wire:loading.attr="disabled">
                        {{ __('确认') }}
                    </button>
                @else
                    <button class="form__button form__button--filled" wire:click="showRecoveryCodes">
                        {{ __('显示恢复码') }}
                    </button>
                @endif

                @if ($showingConfirmation)
                    <button class="form__button form__button--filled" wire:click="disableTwoFactorAuthentication" wire:loading.attr="disabled">
                        {{ __('取消') }}
                    </button>
                @else
                    <button class="form__button form__button--filled" wire:click="disableTwoFactorAuthentication" wire:loading.attr="disabled">
                        {{ __('禁用') }}
                    </button>
                @endif
            @endif
        </div>

    </div>
</section>