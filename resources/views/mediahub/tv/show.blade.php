@extends('layout.default')

@section('title')
    <title>{{ $show->name }} - {{ config('other.title') }}</title>
@endsection

@section('meta')
    <meta name="description" content="{{ $show->name }}">
@endsection

@section('breadcrumbs')
    <li class="breadcrumbV2">
        <a href="{{ route('mediahub.index') }}" class="breadcrumb__link">
            {{ __('mediahub.title') }}
        </a>
    </li>
    <li class="breadcrumbV2">
        <a href="{{ route('mediahub.shows.index') }}" class="breadcrumb__link">
            剧集
        </a>
    </li>
    <li class="breadcrumb--active">
        {{ $show->name }}
    </li>
@endsection

@section('main')
    <section class="panelV2">
        <h2 class="panel__heading">{{ __('mediahub.seasons') }}</h2>
        <div class="panel__body">
            @foreach($show->seasons as $season)
                <div class="row">
                    <div class="col-md-12">
                        <div class="card is-torrent"
                                style=" height: auto; margin-top: 0; margin-bottom: 20px;">
                            <div class="card_head">
                <span class="badge-user text-bold" style="float:right;">
                    共{{ $season->episodes->count() }}集
                </span>
                                <span class="badge-user text-bold" style="float:right;">
                    第{{ $season->season_number }}季
                </span>
                            </div>
                            <div class="card_body" style="height: 190px;">
                                <div class="body_poster">
                                    <img src="{{ isset($season->poster) ? tmdb_image('poster_mid', $season->poster) : 'https://via.placeholder.com/200x300' }}"
                                            class="show-poster" style="height: 190px;">
                                </div>
                                <div class="body_description" style=" height: 190px;">
                                    <h3 class="description_title">
                                        <a href="{{ route('mediahub.season.show', ['id' => $season->id]) }}">{{ $season->name }}
                                            @if($season->air_date)
                                                <span class="text-bold text-pink"> ({{ substr($season->air_date, 0, 4) }})</span>
                                            @endif
                                        </a>
                                    </h3>
                                    <p class="description_plot">
                                        {{ $season->overview }}
                                    </p>
                                </div>
                            </div>
                            <div class="card_footer text-center">
                                <a data-toggle="collapse" data-target="#{{ $season->season_number }}">
                                    <i class="fas fa-chevron-double-down"></i> <span
                                            class="badge-user text-bold"> 匹配到{{ $season->torrents->where('season_number', '=', $season->season_number)->count() }}个资源</span>
                                    <i class="fas fa-chevron-double-down"></i>
                                </a>
                            </div>
                            <div id="{{ $season->season_number }}" class="collapse">
                                <div class="card_footer" style="height: auto;">
                                    <div class="table-responsive">
                                        <table class="table table-condensed table-bordered table-striped table-hover">
                                            <thead>
                                            <tr>
                                                <th>{{ __('common.name') }}</th>
                                                <th>{{ __('torrent.size') }}</th>
                                                <th>做种</th>
                                                <th>下载</th>
                                                <th>完成</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            @foreach($season->torrents->where('season_number', '=', $season->season_number)->sortByDesc('created_at') as $torrent)
                                                <tr>
                                                    <td>
                                                        <a href="{{ route('torrents.show', ['id' => $torrent->id]) }}"
                                                            style="color: #ffffff;">{{ $torrent->name }}</a>
                                                    </td>
                                                    <td>{{ $torrent->getSize() }}</td>
                                                    <td>{{ $torrent->seeders }}</td>
                                                    <td>{{ $torrent->leechers }}</td>
                                                    <td>{{ $torrent->times_completed }}</td>
                                                </tr>
                                            @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </section>
@endsection

@section('sidebar')
    <section class="panelV2">
        <h2 class="panel__heading">{{ $show->name }}</h2>
        <img
            src="{{ isset($show->poster) ? tmdb_image('cast_big', $show->poster) : 'https://via.placeholder.com/300x450' }}"
            alt="{{ $show->name }}"
        >
        <dl class="key-value">
            <dt>季数</dt>
            <dd>{{ $show->number_of_seasons }}</dd>
            <dt>完结状态</dt>
            <dd>{{ $show->status }}</dd>
            <dt>流媒体</dt>
            <dd>
                @foreach($show->networks as $network)
                    <a href="{{ route('torrents.index', ['view' => 'group', 'networkId' => $network->id]) }}">{{ $network->name }}</a>
                    @if (! $loop->last), @endif
                @endforeach
            </dd>
            <dt>发行</dt>
            <dd>
                @foreach($show->companies as $company)
                    <a href="{{ route('torrents.index', ['view' => 'group', 'companyId' => $company->id]) }}">{{ $company->name }}</a>
                    @if (! $loop->last), @endif
                @endforeach
            </dd>
            <dt>时长</dt>
            <dd>{{ $show->episode_run_time }}</dd>
            <dt>种子</dt>
            <dd>{{ $show->torrents_count }}</dd>
            <dt>类型</dt>
            <dd>
                @foreach($show->genres as $genre)
                    <a href="{{ route('torrents.index', ['view' => 'group', 'genres' => $genre->id]) }}">{{ $genre->name }}</a>
                    @if (! $loop->last), @endif
                @endforeach
            </dd>
        </dl>
    </section>
@endsection
