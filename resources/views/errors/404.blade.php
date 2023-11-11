@extends('errors.layout')

@section('title', 'Error 404: 当前页面不存在')

@section('description')
    <p>{{ $exception->getMessage() ?: '你要找的页面不在这儿哦～请向KIMOJI的管理组报告把' }}</p>
    <div style="text-align: center;">
        <img src="{{ asset('/img/404error.png') }}" alt="Error 404" style="max-width: 100%; height: auto;">
    </div>
@endsection