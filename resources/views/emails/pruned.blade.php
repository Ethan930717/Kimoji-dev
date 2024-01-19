@component('mail::message')
# {{ __('email.pruned-header') }}!
由于未在规定时间内达到站点最低保种要求，您的账户已从 {{ config('other.title') }} 永久删除！
@endcomponent
