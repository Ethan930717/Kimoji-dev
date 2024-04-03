@extends('errors.layout')

@section('title', 'Error 500: Internal Server Error')

@section('description')
    <p>{{ $exception->getMessage() ?: 'Our server encountered an internal error. Sorry For The Inconvenience' }}</p>
    <div style="text-align: center;">
        <img src="{{ asset('/img/500error.png') }}" alt="Error 500" style="max-width: 100%; height: auto;">
    </div>
@endsection
