@extends('layout.default')

@section('title')
    <title>{{ __('stat.stats') }} - {{ config('other.title') }}</title>
@endsection

@section('breadcrumbs')
    <li class="breadcrumb--active">
        {{ __('stat.stats') }}
    </li>
@endsection

@section('nav-tabs')
    <li class="nav-tabV2">
        <a class="nav-tab__link" href="{{ route('clients') }}">
            {{ __('page.blacklist-clients') }}
        </a>
    </li>
    <li class="nav-tabV2">
        <a class="nav-tab__link" href="{{ route('uploaded') }}">
            {{ __('common.users') }}
        </a>
    </li>
    <li class="nav-tabV2">
        <a class="nav-tab__link" href="{{ route('seeded') }}">
            {{ __('torrent.torrents') }}
        </a>
    </li>
    <li class="nav-tabV2">
        <a class="nav-tab__link" href="{{ route('bountied') }}">
            {{ __('request.requests') }}
        </a>
    </li>
    <li class="nav-tabV2">
        <a class="nav-tab__link" href="{{ route('groups') }}">
            {{ __('common.groups') }}
        </a>
    </li>
    <li class="nav-tabV2">
        <a class="nav-tab__link" href="{{ route('languages') }}">
            {{ __('common.languages') }}
        </a>
    </li>
    <li class="nav-tabV2">
        <a class="nav-tab__link" href="{{ route('themes') }}">
            主题
        </a>
    </li>
    <li class="nav-tabV2">
        <a class="nav-tab__link" href="{{ route('yearly_overviews.index') }}">年度报告</a>
    </li>
@endsection

@section('main')
    <section class="panelV2">
        <h2 class="panel__heading">{{ __('stat.nerd-stats') }}</h2>
        <div class="panel__body">{{ __('stat.updated') }}{{ __('stat.nerd-stats-desc') }} </div>
    </section>
    <div class="stats__panels">
        <section class="panelV2 panel--grid-item">
            <h2 class="panel__heading">{{ __('torrent.torrents') }}</h2>
            <dl class="key-value">
                @foreach ($categories as $category)
                    <dt>{{ $category->name }} / 官种</dt>
                    <dd>{{ $category->torrents_count }} / {{ $category->official_torrents_count }}</dd>
                @endforeach
                <dt>高清资源 / 官种</dt>
                <dd>{{ $num_hd }} / {{ $num_hd_official }}</dd>
                <dt>PG-12儿童资源 / 官种</dt>
                <dd>{{ $num_sd }} / {{ $num_sd_official }}</dd>
                <dt>{{ __('stat.total-torrents') }} / 官种</dt>
                <dd>{{ $num_torrent }} / {{ $num_torrent_official }}</dd>
                <dt>资源总体积 / 官种总体积</dt>
                <dd>{{ App\Helpers\StringHelper::formatBytes($torrent_size, 2) }} / {{ App\Helpers\StringHelper::formatBytes($official_torrent_size, 2) }}</dd>
            </dl>
        </section>
        <section class="panelV2 panel--grid-item">
            <h2 class="panel__heading">{{ __('common.users') }}</h2>
            <dl class="key-value">
                <dt>{{ __('stat.all') }}</dt>
                <dd>{{ $all_user }}</dd>
                <dt>{{ __('stat.active') }}</dt>
                <dd>{{ $active_user }}</dd>
                <dt>{{ __('stat.disabled') }}</dt>
                <dd>{{ $disabled_user }}</dd>
                <dt>{{ __('stat.pruned') }}</dt>
                <dd>{{ $pruned_user }}</dd>
                <dt>{{ __('stat.banned') }}</dt>
                <dd>{{ $banned_user }}</dd>
            </dl>
        </section>
        <section class="panelV2 panel--grid-item">
            <h2 class="panel__heading">{{ __('torrent.peers') }}</h2>
            <dl class="key-value">
                <dt>{{ __('torrent.seeders') }}</dt>
                <dd>{{ $num_seeders }}</dd>
                <dt>{{ __('torrent.leechers') }}</dt>
                <dd>{{ $num_leechers }}</dd>
                <dt>总数</dt>
                <dd>{{ $num_peers }}</dd>
            </dl>
        </section>
        <section class="panelV2 panel--grid-item">
            <h2 class="panel__heading">{{ __('stat.total-traffic') }}</h2>
            <dl class="key-value">
                <dt>{{ __('stat.real') }} {{ __('stat.total-upload') }}</dt>
                <dd>{{ \App\Helpers\StringHelper::formatBytes($actual_upload, 2) }}</dd>
                <dt>{{ __('stat.real') }} {{ __('stat.total-download') }}</dt>
                <dd>{{ \App\Helpers\StringHelper::formatBytes($actual_download, 2) }}</dd>
                <dt>{{ __('stat.real') }} {{ __('stat.total-traffic') }}</dt>
                <dd>{{ \App\Helpers\StringHelper::formatBytes($actual_up_down, 2) }}</dd>
                <dt>{{ __('stat.credited') }} {{ __('stat.total-upload') }}</dt>
                <dd>{{ \App\Helpers\StringHelper::formatBytes($credited_upload, 2) }}</dd>
                <dt>{{ __('stat.credited') }} {{ __('stat.total-download') }}</dt>
                <dd>{{ \App\Helpers\StringHelper::formatBytes($credited_download, 2) }}</dd>
                <dt>{{ __('stat.credited') }} {{ __('stat.total-traffic') }}</dt>
                <dd>{{ \App\Helpers\StringHelper::formatBytes($credited_up_down, 2) }}</dd>
            </dl>
        </section>
    </div>
@endsection
