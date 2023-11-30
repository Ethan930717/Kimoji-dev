@component('mail::message')
# Test Email
<p style="word-wrap: break-word; overflow-wrap: break-word; word-break: break-word;">Your test email has been successfully delivered! Looks like your mail configs are on point!
</p>
Thanks,
{{ __('email.footer-link') }}
@endcomponent
