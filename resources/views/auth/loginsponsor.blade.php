<!DOCTYPE html>
<html lang="{{ config('app.locale') }}" style="background-image:url('/img/pink.png')">

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
<!-- Dont Not Change! For Jackett Support -->
<div class="Jackett" style="display:none;">{{ config('unit3d.powered-by') }}</div>
<!-- Dont Not Change! For Jackett Support -->

@if ($errors->any())
    <div id="ERROR_COPY" style="display: none;">
        @foreach ($errors->all() as $error)
            {{ $error }}<br>
        @endforeach
    </div>
@endif
<div class="wrapper fadeInDown">
    <div id="formContent" style="max-width:650px">
        <svg viewBox="0 0 800 100" class="sitebanner" style="height:55px">
            <symbol id="s-text">
                <text text-anchor="middle" x="50%" y="50%" dy=".35em">
                    {{ config('other.title') }}
                </text>
            </symbol>
            <use xlink:href="#s-text" class="text"></use>
            <use xlink:href="#s-text" class="text"></use>
            <use xlink:href="#s-text" class="text" style="stroke:#e17474"></use>
            <use xlink:href="#s-text" class="text" style="stroke:#e17474"></use>
            <use xlink:href="#s-text" class="text"></use>
        </svg>
        <h2 class="panel__heading" style="text-shadow: 0 0 8px #fff;color:#e47a7a;font-size:21px;margin-bottom:10px">谢谢爸爸有意赞助(๑ơ ₃ ơ)♥目前人家的零花钱还够用哦～</h2>
    <section class="panelV2" style="text-align: center;text-shadow: 0 0 8px #fff;color:#cf1c1c;font-size:21px;">
        <div class="panel__body">
            <p style="margin-bottom:16px">～迫切需要的是技术帮助(｡ŏ_ŏ)如果你是laravel开发、逆向或其他方向的技术大佬</p>
            <p style="margin-bottom:8px">或者你对美工、外宣、发种等岗位有浓厚的兴趣或丰富的经验，请务必向我</p>
            <a href="https://t.me/Kimoji_office" target="_blank" style="display:inline-block;margin-bottom:10px;padding:10px 20px;background-color:#cf1c1c;color:#ffffff;text-decoration:none;border-radius:5px;font-size:18px;align-items:center;justify-content:center;gap:5px;">
                <svg xmlns="http://www.w3.org/2000/svg" height="1em" fill="#ffffff" viewBox="0 0 496 512" style="vertical-align: middle;"><path d="M248,8C111.033,8,0,119.033,0,256S111.033,504,248,504,496,392.967,496,256,384.967,8,248,8ZM362.952,176.66c-3.732,39.215-19.881,134.378-28.1,178.3-3.476,18.584-10.322,24.816-16.948,25.425-14.4,1.326-25.338-9.517-39.287-18.661-21.827-14.308-34.158-23.215-55.346-37.177-24.485-16.135-8.612-25,5.342-39.5,3.652-3.793,67.107-61.51,68.335-66.746.153-.655.3-3.1-1.154-4.384s-3.59-.849-5.135-.5q-3.283.746-104.608,69.142-14.845,10.194-26.894,9.934c-8.855-.191-25.888-5.006-38.551-9.123-15.531-5.048-27.875-7.717-26.8-16.291q.84-6.7,18.45-13.7,108.446-47.248,144.628-62.3c68.872-28.647,83.183-33.623,92.511-33.789,2.052-.034,6.639.474,9.61,2.885a10.452,10.452,0,0,1,3.53,6.716A43.765,43.765,0,0,1,362.952,176.66Z"/></svg>
                <span>伸出援手</span>
            </a>

            <img src="{{ asset('/img/sponsor.png') }}" alt="sponser_img" style="max-width: 100%; height: auto; display: block; margin: 0 auto;">
        </div>
    </section>





