@extends('errors.layout')

@section('title', 'Error 403: Forbidden!')

@section('description')
    <p>{{ $exception->getMessage() ?: 'You do not have permission to perform this action!' }}</p>
    <div style="text-align: center;">
        <img src="{{ asset('/img/403error.webp') }}" alt="Error 403" style="max-width: 100%; height: auto;">
    </div>
@endsection
