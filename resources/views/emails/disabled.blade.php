@component('mail::message')
# {{ __('email.disabled-header') }}!
您的账户因长时间不活跃已被标记为禁用，并已被置于禁用组。
为了保留您的账户，您必须在收到此电子邮件后的 {{ config('pruning.soft_delete') }} 天内登录。
如果未能这样做，您的账户将被永久从 {{ config('other.title') }} 中删除！
为了避免这种情况，请至少每隔 {{ config('pruning.last_login') }} 天登录一次。

@endcomponent
