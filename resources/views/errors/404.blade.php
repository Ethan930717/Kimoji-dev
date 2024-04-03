@extends('errors.layout')

@section('title', 'Error 404: Page Not Found')

@section('description')
    <p>{{ $exception->getMessage() ?: 'The requested page cannot be found! Not sure what you\'re looking for but check the address and try again!' }}</p>
    <div style="text-align: center;">
        <img src="{{ asset('/img/404error.png') }}" alt="Error 404" style="max-width: 100%; height: auto;">
    </div>
@endsection
