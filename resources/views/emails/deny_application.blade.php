@component('mail::message')
# {{ config('other.title') }} 申请结果通知
您的入站申请由于以下原因被拒绝了：
{{ $deniedMessage }}
感谢您的申请
{{ config('other.title') }}
@endcomponent
