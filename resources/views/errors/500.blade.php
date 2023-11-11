@extends('errors.layout')

@section('title', 'Error 500: 内部错误')

@section('description')
    <p>{{ $exception->getMessage() ?: 'KIMOJI正在检修中，请稍后再来' }}</p>
    <div style="text-align: center;">
        <img src="{{ asset('/img/500error.png') }}" alt="Error 500" style="max-width: 100%; height: auto;">
    </div>
@endsection
