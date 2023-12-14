@component('mail::message')

**{{ __('email.invite-message') }}:**  {{ $invite->custom }}
@component('mail::button', ['url' => route('register', ['code' => $invite->code]), 'color' => 'blue'])
{{ __('email.invite-signup') }}
@endcomponent
<p>{{ __('email.register-footer') }}</p>
<p style="word-wrap: break-word; overflow-wrap: break-word; word-break: break-word;">{{ route('register', $invite->code) }}</p>
@endcomponent
