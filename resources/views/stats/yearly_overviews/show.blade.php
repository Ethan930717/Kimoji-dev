@extends('layout.default')

@section('title')
    <title>{{ __('stat.stats') }} - {{ config('other.title') }}</title>
@endsection

@section('breadcrumbs')
    <li class="breadcrumbV2">
        <a href="{{ route('stats') }}" class="breadcrumb__link">
            {{ __('stat.stats') }}
        </a>
    </li>
    <li class="breadcrumbV2">
        <a href="{{ route('yearly_overviews.index') }}" class="breadcrumb__link">
            {{ __('common.overview') }}
        </a>
    </li>
    <li class="breadcrumb--active">{{ $year }}</li>
@endsection

@section('nav-tabs')
    @for ($i = $birthYear; $i < now()->year; $i++)
        <li class="{{ $i === $year ? 'nav-tab--active' : 'nav-tabV2' }}">
            <a
                class="{{ $i === $year ? 'nav-tab--active__link' : 'nav-tab__link' }}"
                href="{{ route('yearly_overviews.show', ['year' => $i]) }}"
            >
                {{ $i }}
            </a>
        </li>
    @endfor
@endsection

@section('page', 'page__stats--overview')

@section('main')
    <section class="panelV2">
        <h2 class="panel__heading">年度报告</h2>
        <div class="panel__body">
            <div class="overview__opening">
                <h1 class="overview__opening-heading">年终总结</h1>
                <h2 class="overview__opening-subheading">{{ $year }}</h2>
                <p class="overview__opening-text">
                    在过去的一年中，我们由衷的感谢每一位为KIMOJI做出贡献的好朋友们，下面，让我们来一起回顾过去一年中的点点滴滴吧
                </p>
            </div>
        </div>
    </section>
    <section class="panelV2">
        <h2 class="panel__heading">十大热门电影</h2>
        <div class="panel__body overview__poster-grid">
            @foreach ($topMovies as $work)
                <figure class="top10-poster overview__poster">
                    <x-movie.poster
                        :movie="$work->movie"
                        :categoryId="$work->category_id"
                        :tmdb="$work->tmdb"
                    />
                    <figcaption
                        class="top10-poster__download-count"
                        title="{{ __('torrent.completed-times') }}"
                    >
                        {{ $work->download_count }}
                    </figcaption>
                </figure>
            @endforeach
        </div>
    </section>
    <section class="panelV2">
        <h2 class="panel__heading">五部最不受欢迎电影</h2>
        <div class="panel__body overview__poster-grid">
            @foreach ($bottomMovies as $work)
                <figure class="top10-poster overview__poster">
                    <x-movie.poster
                        :movie="$work->movie"
                        :categoryId="$work->category_id"
                        :tmdb="$work->tmdb"
                    />
                    <figcaption
                        class="top10-poster__download-count"
                        title="{{ __('torrent.completed-times') }}"
                    >
                        {{ $work->download_count }}
                    </figcaption>
                </figure>
            @endforeach
        </div>
    </section>
    <section class="panelV2">
        <h2 class="panel__heading">十大热门剧集</h2>
        <div class="panel__body overview__poster-grid">
            @foreach ($topTv as $work)
                <figure class="top10-poster overview__poster">
                    <x-tv.poster
                        :tv="$work->tv"
                        :categoryId="$work->category_id"
                        :tmdb="$work->tmdb"
                    />
                    <figcaption
                        class="top10-poster__download-count"
                        title="{{ __('torrent.completed-times') }}"
                    >
                        {{ $work->download_count }}
                    </figcaption>
                </figure>
            @endforeach
        </div>
    </section>
    <section class="panelV2">
        <h2 class="panel__heading">五部最不受欢迎剧集</h2>
        <div class="panel__body overview__poster-grid">
            @foreach ($bottomTv as $work)
                <figure class="top10-poster overview__poster">
                    <x-tv.poster
                        :tv="$work->tv"
                        :categoryId="$work->category_id"
                        :tmdb="$work->tmdb"
                    />
                    <figcaption
                        class="top10-poster__download-count"
                        title="{{ __('torrent.completed-times') }}"
                    >
                        {{ $work->download_count }}
                    </figcaption>
                </figure>
            @endforeach
        </div>
    </section>
    <section class="panelV2">
        <h2 class="panel__heading">发种之星</h2>
        <div class="panel__body user-stat-card-container">
            @foreach ($uploaders as $uploader)
                <article class="user-stat-card">
                    <h3 class="user-stat-card__username">
                        <x-user_tag :user="$uploader->user" :anon="false" />
                    </h3>
                    <h4 class="user-stat-card__stat">
                        {{ $uploader->value }} {{ __('user.uploads') }}
                    </h4>
                    <img
                        class="user-stat-card__avatar"
                        alt="avatar"
                        src="{{ url($uploader->user->image === null ? 'img/profile.png' : 'files/img/' . $uploader->user->image) }}"
                    />
                </article>
            @endforeach
        </div>
    </section>
    <section class="panelV2">
        <h2 class="panel__heading">求种大王</h2>
        <div class="panel__body user-stat-card-container">
            @foreach ($requesters as $requester)
                <article class="user-stat-card">
                    <h3 class="user-stat-card__username">
                        <x-user_tag :user="$requester->user" :anon="false" />
                    </h3>
                    <h4 class="user-stat-card__stat">
                        {{ $requester->value }} {{ __('request.requests') }}
                    </h4>
                    <img
                        class="user-stat-card__avatar"
                        alt="avatar"
                        src="{{ url($requester->user->image === null ? 'img/profile.png' : 'files/img/' . $requester->user->image) }}"
                    />
                </article>
            @endforeach
        </div>
    </section>
    <section class="panelV2">
        <h2 class="panel__heading">悬赏达人</h2>
        <div class="panel__body user-stat-card-container">
            @foreach ($fillers as $filler)
                <article class="user-stat-card">
                    <h3 class="user-stat-card__username">
                        <x-user_tag :user="$filler->filler" :anon="false" />
                    </h3>
                    <h4 class="user-stat-card__stat">
                        {{ $filler->value }} {{ __('notification.request-fills') }}
                    </h4>
                    <img
                        class="user-stat-card__avatar"
                        alt="avatar"
                        src="{{ url($filler->filler->image === null ? 'img/profile.png' : 'files/img/' . $filler->filler->image) }}"
                    />
                </article>
            @endforeach
        </div>
    </section>
    <section class="panelV2">
        <h2 class="panel__heading">评论之星</h2>
        <div class="panel__body user-stat-card-container">
            @foreach ($commenters as $commenter)
                <article class="user-stat-card">
                    <h3 class="user-stat-card__username">
                        <x-user_tag :user="$commenter->user" :anon="false" />
                    </h3>
                    <h4 class="user-stat-card__stat">
                        {{ $commenter->value }} {{ __('user.comments') }}
                    </h4>
                    <img
                        class="user-stat-card__avatar"
                        alt="avatar"
                        src="{{ url($commenter->user->image === null ? 'img/profile.png' : 'files/img/' . $commenter->user->image) }}"
                    />
                </article>
            @endforeach
        </div>
    </section>
    <section class="panelV2">
        <h2 class="panel__heading">灌水大王</h2>
        <div class="panel__body user-stat-card-container">
            @foreach ($posters as $poster)
                <article class="user-stat-card">
                    <h3 class="user-stat-card__username">
                        <x-user_tag :user="$poster->user" :anon="false" />
                    </h3>
                    <h4 class="user-stat-card__stat">
                        {{ $poster->value }} {{ __('common.posts') }}
                    </h4>
                    <img
                        class="user-stat-card__avatar"
                        alt="avatar"
                        src="{{ url($poster->user->image === null ? 'img/profile.png' : 'files/img/' . $poster->user->image) }}"
                    />
                </article>
            @endforeach
        </div>
    </section>
    <section class="panelV2">
        <h2 class="panel__heading">最有礼貌的人</h2>
        <div class="panel__body user-stat-card-container">
            @foreach ($thankers as $thanker)
                <article class="user-stat-card">
                    <h3 class="user-stat-card__username">
                        <x-user_tag :user="$thanker->user" :anon="false" />
                    </h3>
                    <h4 class="user-stat-card__stat">
                        {{ $thanker->value }} {{ __('torrent.thanks') }}
                    </h4>
                    <img
                        class="user-stat-card__avatar"
                        alt="avatar"
                        src="{{ url($thanker->user->image === null ? 'img/profile.png' : 'files/img/' . $thanker->user->image) }}"
                    />
                </article>
            @endforeach
        </div>
    </section>
    <section class="panelV2">
        <h2 class="panel__heading">年度数据总览</h2>
        <dl class="key-value">
            <dt>今年新加入用户数</dt>
            <dd>{{ $newUsers }}</dd>
            <dt>今年上传的电影总数</dt>
            <dd>{{ $movieUploads }}</dd>
            <dt>今年上传的剧集总数</dt>
            <dd>{{ $tvUploads }}</dd>
            <dt>今年上传的种子总数</dt>
            <dd>{{ $totalUploads }}</dd>
            <dt>今年下载的种子总数</dt>
            <dd>{{ $totalDownloads }}</dd>
        </dl>
    </section>
    <section class="panelV2">
        <h2 class="panel__heading">结语</h2>
        <div class="panel__body overview__closing">
            <h3 class="overview__closing-heading">KIMOJI！</h3>
            <h4 class="overview__closing-subheading">
                感谢大家的参与，共同创造了精彩的 {{ $year }} 年
            </h4>
            <span class="overview__closing-thanks">来自KIMOJI全体组员的特别感谢：</span>
            @foreach ($staffers as $group)
                <ul class="overview__staff-list">
                    @foreach ($group->users as $user)
                        <li class="overview__staff-list-item">
                            <x-user_tag :user="$user" :anon="false" />
                        </li>
                    @endforeach
                </ul>
            @endforeach
        </div>
    </section>
@endsection
