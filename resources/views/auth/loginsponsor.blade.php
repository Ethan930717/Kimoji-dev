<!DOCTYPE html>
<html lang="{{ config('app.locale') }}" style="background-image:url('/img/green.png')">

<head>
    <meta charset="UTF-8">
    <title>{{ __('auth.login') }} - {{ config('other.title') }}</title>
    @section('meta')
        <meta name="description"
              content="{{ __('auth.login-now-on') }} {{ config('other.title') }} . {{ __('auth.not-a-member') }}">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta property="og:title" content="{{ __('auth.login') }}">
        <meta property="og:site_name" content="{{ config('other.title') }}">
        <meta property="og:type" content="website">
        <meta property="og:image" content="{{ url('/img/og.png') }}">
        <meta property="og:description" content="{{ config('unit3d.powered-by') }}">
        <meta property="og:url" content="{{ url('/') }}">
        <meta property="og:locale" content="{{ config('app.locale') }}">
        <meta name="csrf-token" content="{{ csrf_token() }}">
    @show
    <link rel="shortcut icon" href="{{ url('/favicon.ico') }}" type="image/x-icon">
    <link rel="icon" href="{{ url('/favicon.ico') }}" type="image/x-icon">
    <link rel="stylesheet" href="{{ mix('css/main/login.css') }}" crossorigin="anonymous">
</head>
<body>
<main>
    <section class="auth-form">
        <div class="auth-form__donation-cards">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">复活卡</h5>
                    <p class="card-text">¥39.9 / $5.99</p>
                </div>
            </div>
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">入场券</h5>
                    <p class="card-text">¥99.9 / $13.99</p>
                </div>
            </div>
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">贵人卡</h5>
                    <p class="card-text">¥588 / $83.99</p>
                </div>
            </div>
        </div>
        <div class="auth-form__qr-codes">
            <img src="/path/to/alipay_qr_code.png" alt="Alipay">
            <img src="/path/to/paypal_qr_code.png" alt="PayPal">
        </div>
    </section>
</main>
</body>
</html>
