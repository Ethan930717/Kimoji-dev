@extends('layout.default')

@section('breadcrumbs')
    <li class="breadcrumbV2">
        <a href="{{ route('pages.index') }}" class="breadcrumb__link">
            其他
        </a>
    </li>
    <li class="breadcrumb--active">
        赞助
    </li>
@endsection

@section('page', 'page__page--show')

@section('main')
    <section class="panelV2" style="text-align: center;">
        <h2 class="panel__heading" style="text-shadow: 0 0 8px #fff;">(๑ơ ₃ ơ)♥谢谢爸爸有意赞助</h2>
        <div class="panel__body">
            <p>目前人家的零花钱还够用哦～</p>
            <img src="{{ asset('/img/sponsor.png') }}" alt="sponser_img" style="max-width: 75%; height: auto; display: block; margin: 0 auto;">
        </div>
    </section>
@endsection



