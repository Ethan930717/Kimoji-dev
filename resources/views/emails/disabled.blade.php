@component('mail::message')
# {{ __('email.disabled-header') }}!
您的账户因长时间未达到最低保种要求，已被列入流放名单。
为了保留您的账户，请您在收到此电子邮件后的 {{ config('pruning.soft_delete') }} 天内达到100GB的最低保种要求（仅限音乐区官方资源）。
如逾期仍未达到要求，我们只能遗憾地请您离开 {{ config('other.title') }}。
@endcomponent
