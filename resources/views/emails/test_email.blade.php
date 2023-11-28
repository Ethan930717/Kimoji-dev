@component('mail::message')
    # {{ __('email.invite-header') }} {{ config('other.title') }} !

    <div style="background-color:#f8f9fa; padding:10px; border-radius:5px;">
        <p style="color:#333; font-size:16px;">
            <strong>{{ __('email.invite-message') }}:</strong> {{ __('email.invite-invited') }}
        </p>
    </div>

    <!-- 示例图片 -->
    <img src="https://kimoji.club/img/indexlogo.png" alt="logo" style="width:100%; max-width:600px; height:auto; margin-top:20px;">
    

    <p>{{ __('email.register-footer') }}</p>
    <p style="word-wrap: break-word; overflow-wrap: break-word; word-break: break-word;">
        {{ route('register', $invite->code) }}
    </p>
@endcomponent
