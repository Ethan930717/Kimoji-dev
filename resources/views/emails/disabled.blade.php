@component('mail::message')
# {{ __('email.disabled-header') }}!
Your account has been placed on the exile list due to a prolonged failure to meet the minimum seedkeeping requirement.
To retain your account, please ensure you reach the minimum seedkeeping requirement of 100GB within 7 days of receiving this email.
Should you fail to meet this requirement within the stipulated timeframe, we will regrettably have to ask you to leave {{ config('other.title') }}.
@endcomponent
