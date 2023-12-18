@extends('layout.default')

@section('title')
    <title>{{ __('staff.staff-dashboard') }} - {{ config('other.title') }}</title>
@endsection

@section('meta')
    <meta name="description" content="{{ __('staff.staff-dashboard') }}">
@endsection

@section('breadcrumbs')
    <li class="breadcrumb--active">
        {{ __('staff.staff-dashboard') }}
    </li>
@endsection

@section('page', 'page__dashboard')

@section('main')
    <div class="dashboard__menus">
        <section class="panelV2 panel--grid-item">
            <h2 class="panel__heading">
                <i class="{{ config('other.font-awesome') }} fa-link"></i>
                {{ __('staff.links') }}
            </h2>
            <div class="panel__body">
                <p class="form__group form__group--horizontal">
                    <a class="form__button form__button--text" href="{{ route('home.index') }}">
                        <i class="{{ config('other.font-awesome') }} fa-columns"></i>
                        {{ __('staff.frontend') }}
                    </a>
                </p>
                <p class="form__group form__group--horizontal">
                    <a class="form__button form__button--text" href="{{ route('staff.dashboard.index') }}">
                        <i class="{{ config('other.font-awesome') }} fa-columns"></i>
                        {{ __('staff.staff-dashboard') }}
                    </a>
                </p>
                @if (auth()->user()->group->is_owner)
                    <p class="form__group form__group--horizontal">
                        <a class="form__button form__button--text" href="{{ route('staff.backups.index') }}">
                            <i class="{{ config('other.font-awesome') }} fa-hdd"></i>
                            {{ __('backup.backup') }}
                            {{ __('backup.manager') }}
                        </a>
                    </p>
                    <p class="form__group form__group--horizontal">
                        <a class="form__button form__button--text" href="{{ route('staff.commands.index') }}">
                            <i class="fab fa-laravel"></i> Commands
                        </a>
                    </p>
                @endif
            </div>
        </section>
        <section class="panelV2 panel--grid-item">
            <h2 class="panel__heading">
                <i class="{{ config('other.font-awesome') }} fa-wrench"></i>
                {{ __('staff.chat-tools') }}
            </h2>
            <div class="panel__body">
                <p class="form__group form__group--horizontal">
                    <a class="form__button form__button--text" href="{{ route('staff.statuses.index') }}">
                        <i class="{{ config('other.font-awesome') }} fa-comment-dots"></i>
                        {{ __('staff.statuses') }}
                    </a>
                </p>
                <p class="form__group form__group--horizontal">
                    <a class="form__button form__button--text" href="{{ route('staff.chatrooms.index') }}">
                        <i class="{{ config('other.font-awesome') }} fa-comment-dots"></i>
                        {{ __('staff.rooms') }}
                    </a>
                </p>
                <p class="form__group form__group--horizontal">
                    <a class="form__button form__button--text" href="{{ route('staff.bots.index') }}">
                        <i class="{{ config('other.font-awesome') }} fa-robot"></i>
                        {{ __('staff.bots') }}
                    </a>
                </p>
                <div class="form__group form__group--horizontal">
                    <form method="POST" action="{{ route('staff.flush.chat') }}" x-data>
                        @csrf
                        <button
                            x-on:click.prevent="Swal.fire({
                                title: '请确认',
                                text: '是否确认删除所有聊天室及信息',
                                icon: 'warning',
                                showConfirmButton: true,
                                showCancelButton: true,
                            }).then((result) => {
                                if (result.isConfirmed) {
                                    $root.submit();
                                }
                            })"
                            class="form__button form__button--text"
                        >
                            <i class="{{ config('other.font-awesome') }} fa-broom"></i>
                            {{ __('staff.flush-chat') }}
                        </button>
                    </form>
                </div>
            </div>
        </section>
        <section class="panelV2 panel--grid-item">
            <h2 class="panel__heading">
                <i class="{{ config('other.font-awesome') }} fa-wrench"></i>
                {{ __('staff.general-tools') }}
            </h2>
            <div class="panel__body">
                <p class="form__group form__group--horizontal">
                    <a class="form__button form__button--text" href="{{ route('staff.articles.index') }}">
                        <i class="{{ config('other.font-awesome') }} fa-newspaper"></i>
                        {{ __('staff.articles') }}
                    </a>
                </p>
                @if (auth()->user()->group->is_admin)
                    <p class="form__group form__group--horizontal">
                        <a class="form__button form__button--text" href="{{ route('staff.forums.index') }}">
                            <i class="fab fa-wpforms"></i>
                            {{ __('staff.forums') }}
                        </a>
                    </p>
                @endif
                <p class="form__group form__group--horizontal">
                    <a class="form__button form__button--text" href="{{ route('staff.pages.index') }}">
                        <i class="{{ config('other.font-awesome') }} fa-file"></i>
                        {{ __('staff.pages') }}
                    </a>
                </p>
                <p class="form__group form__group--horizontal">
                    <a class="form__button form__button--text" href="{{ route('staff.polls.index') }}">
                        <i class="{{ config('other.font-awesome') }} fa-chart-pie"></i>
                        {{ __('staff.polls') }}
                    </a>
                </p>
                <p class="form__group form__group--horizontal">
                    <a class="form__button form__button--text" href="{{ route('staff.bon_exchanges.index') }}">
                        <i class="{{ config('other.font-awesome') }} fa-coins"></i>
                        {{ __('staff.bon-exchange') }}
                    </a>
                </p>
                <p class="form__group form__group--horizontal">
                    <a class="form__button form__button--text" href="{{ route('staff.blacklisted_clients.index') }}">
                        <i class="{{ config('other.font-awesome') }} fa-ban"></i>
                        {{ __('common.blacklist') }}
                    </a>
                </p>
                <p class="form__group form__group--horizontal">
                    <a class="form__button form__button--text" href="{{ route('staff.blocked_ips.index') }}">
                        <i class="{{ config('other.font-awesome') }} fa-ban"></i>
                        {{ __('staff.blocked-ips') }}
                    </a>
                </p>
            </div>
        </section>
        <section class="panelV2 panel--grid-item">
            <h2 class="panel__heading">
                <i class="{{ config('other.font-awesome') }} fa-wrench"></i>
                {{ __('staff.torrent-tools') }}
            </h2>
            <div class="panel__body">
                <p class="form__group form__group--horizontal">
                    <a class="form__button form__button--text" href="{{ route('staff.moderation.index') }}">
                        <i class="{{ config('other.font-awesome') }} fa-columns"></i>
                        {{ __('staff.torrent-moderation') }}
                    </a>
                </p>
                <p class="form__group form__group--horizontal">
                    <a class="form__button form__button--text" href="{{ route('staff.categories.index') }}">
                        <i class="{{ config('other.font-awesome') }} fa-columns"></i>
                        {{ __('staff.torrent-categories') }}
                    </a>
                </p>
                <p class="form__group form__group--horizontal">
                    <a class="form__button form__button--text" href="{{ route('staff.types.index') }}">
                        <i class="{{ config('other.font-awesome') }} fa-columns"></i>
                        {{ __('staff.torrent-types') }}
                    </a>
                </p>
                <p class="form__group form__group--horizontal">
                    <a class="form__button form__button--text" href="{{ route('staff.resolutions.index') }}">
                        <i class="{{ config('other.font-awesome') }} fa-columns"></i>
                        {{ __('staff.torrent-resolutions') }}
                    </a>
                </p>
                <p class="form__group form__group--horizontal">
                    <a class="form__button form__button--text" href="{{ route('staff.regions.index') }}">
                        <i class="{{ config('other.font-awesome') }} fa-columns"></i>
                        产地
                    </a>
                </p>
                <p class="form__group form__group--horizontal">
                    <a class="form__button form__button--text" href="{{ route('staff.distributors.index') }}">
                        <i class="{{ config('other.font-awesome') }} fa-columns"></i>
                        发行商
                    </a>
                </p>
                <p class="form__group form__group--horizontal">
                    <a class="form__button form__button--text" href="{{ route('staff.peers.index') }}">
                        <i class="{{ config('other.font-awesome') }} fa-columns"></i>
                        连接数
                    </a>
                </p>
                <p class="form__group form__group--horizontal">
                    <a class="form__button form__button--text" href="{{ route('staff.histories.index') }}">
                        <i class="{{ config('other.font-awesome') }} fa-columns"></i>
                        历史
                    </a>
                </p>
                <p class="form__group form__group--horizontal">
                    <a class="form__button form__button--text" href="{{ route('staff.rss.index') }}">
                        <i class="{{ config('other.font-awesome') }} fa-rss"></i>
                        {{ __('staff.rss') }}
                    </a>
                </p>
                <p class="form__group form__group--horizontal">
                    <a class="form__button form__button--text" href="{{ route('staff.media_languages.index') }}">
                        <i class="{{ config('other.font-awesome') }} fa-columns"></i>
                        {{ __('common.media-languages') }}
                    </a>
                </p>
                <p class="form__group form__group--horizontal">
                    <a class="form__button form__button--text" href="{{ route('staff.cheated_torrents.index') }}">
                        <i class="{{ config('other.font-awesome') }} fa-question"></i>
                        作弊资源
                    </a>
                </p>
                @if (config('announce.log_announces'))
                    <p class="form__group form__group--horizontal">
                        <a class="form__button form__button--text" href="{{ route('staff.announces.index') }}">
                            <i class="{{ config('other.font-awesome') }} fa-chart-bar"></i>
                            Announces
                        </a>
                    </p>
                @endif
                <div class="form__group form__group--horizontal">
                    <form method="POST" action="{{ route('staff.flush.peers') }}" x-data>
                        @csrf
                        <button
                            x-on:click.prevent="Swal.fire({
                                title: '请确认',
                                text: '是否确认清空所有幽灵种子',
                                icon: 'warning',
                                showConfirmButton: true,
                                showCancelButton: true,
                            }).then((result) => {
                                if (result.isConfirmed) {
                                    $root.submit();
                                }
                            })"
                            class="form__button form__button--text"
                        >
                            <i class="{{ config('other.font-awesome') }} fa-ghost"></i>
                            {{ __('staff.flush-ghost-peers') }}
                        </button>
                    </form>
                </div>
            </div>
        </section>
        <section class="panelV2 panel--grid-item">
            <h2 class="panel__heading">
                <i class="{{ config('other.font-awesome') }} fa-wrench"></i>
                {{ __('staff.user-tools') }}
            </h2>
            <div class="panel__body">
                <p class="form__group form__group--horizontal">
                    <a class="form__button form__button--text" href="{{ route('staff.applications.index') }}">
                        <i class="{{ config('other.font-awesome') }} fa-list"></i>
                        {{ __('staff.applications') }} ({{ $pendingApplicationsCount }})
                        @if ($pendingApplicationsCount > 0)
                            <x-animation.notification />
                        @endif
                    </a>
                </p>
                <p class="form__group form__group--horizontal">
                    <a class="form__button form__button--text" href="{{ route('staff.users.index') }}">
                        <i class="{{ config('other.font-awesome') }} fa-users"></i>
                        {{ __('staff.user-search') }}
                    </a>
                </p>
                <p class="form__group form__group--horizontal">
                    <a class="form__button form__button--text" href="{{ route('staff.apikeys.index') }}">
                        <i class="{{ config('other.font-awesome') }} fa-key"></i>
                        {{ __('user.apikeys') }}
                    </a>
                </p>
                <p class="form__group form__group--horizontal">
                    <a class="form__button form__button--text" href="{{ route('staff.passkeys.index') }}">
                        <i class="{{ config('other.font-awesome') }} fa-key"></i>
                        {{ __('staff.passkeys') }}
                    </a>
                </p>
                <p class="form__group form__group--horizontal">
                    <a class="form__button form__button--text" href="{{ route('staff.rsskeys.index') }}">
                        <i class="{{ config('other.font-awesome') }} fa-key"></i>
                        {{ __('user.rsskeys') }}
                    </a>
                </p>
                <p class="form__group form__group--horizontal">
                    <a class="form__button form__button--text" href="{{ route('staff.watchlist.index') }}">
                        <i class="{{ config('other.font-awesome') }} fa-eye"></i>
                        关注列表
                    </a>
                </p>
                <p class="form__group form__group--horizontal">
                    <a class="form__button form__button--text" href="{{ route('staff.mass-pm.create') }}">
                        <i class="{{ config('other.font-awesome') }} fa-envelope-square"></i>
                        {{ __('staff.mass-pm') }}
                    </a>
                </p>
                <div class="form__group form__group--horizontal">
                    <form method="GET" action="{{ route('staff.mass-actions.validate') }}" x-data>
                        @csrf
                        <button
                            x-on:click.prevent="Swal.fire({
                                title: '你确定吗?',
                                text: '请确认是否真的需要批量激活所有账号',
                                icon: 'warning',
                                showConfirmButton: true,
                                showCancelButton: true,
                            }).then((result) => {
                                if (result.isConfirmed) {
                                    $root.submit();
                                }
                            })"
                            class="form__button form__button--text"
                        >
                            <i class="{{ config('other.font-awesome') }} fa-history"></i>
                            {{ __('staff.mass-validate-users') }}
                        </button>
                    </form>
                </div>
                <p class="form__group form__group--horizontal">
                    <a class="form__button form__button--text" href="{{ route('staff.cheaters.index') }}">
                        <i class="{{ config('other.font-awesome') }} fa-question"></i>
                        {{ __('staff.possible-leech-cheaters') }}
                    </a>
                </p>
                <p class="form__group form__group--horizontal">
                    <a class="form__button form__button--text" href="{{ route('staff.seedboxes.index') }}">
                        <i class="{{ config('other.font-awesome') }} fa-server"></i>
                        {{ __('staff.seedboxes') }}
                    </a>
                </p>
                <p class="form__group form__group--horizontal">
                    <a class="form__button form__button--text" href="{{ route('staff.internals.index') }}">
                        <i class="{{ config('other.font-awesome') }} fa-magic"></i>
                        工作组
                    </a>
                </p>
                @if (auth()->user()->group->is_admin)
                    <p class="form__group form__group--horizontal">
                        <a class="form__button form__button--text" href="{{ route('staff.groups.index') }}">
                            <i class="{{ config('other.font-awesome') }} fa-users"></i>
                            {{ __('staff.groups') }}
                        </a>
                    </p>
                @endif
            </div>
        </section>
        <section class="panelV2 panel--grid-item">
            <h2 class="panel__heading">
                <i class="{{ config('other.font-awesome') }} fa-file"></i>
                {{ __('staff.logs') }}
            </h2>
            <div class="panel__body">
                <p class="form__group form__group--horizontal">
                    <a class="form__button form__button--text" href="{{ route('staff.audits.index') }}">
                        <i class="{{ config('other.font-awesome') }} fa-file"></i>
                        {{ __('staff.audit-log') }}
                    </a>
                </p>
                <p class="form__group form__group--horizontal">
                    <a class="form__button form__button--text" href="{{ route('staff.bans.index') }}">
                        <i class="{{ config('other.font-awesome') }} fa-file"></i>
                        {{ __('staff.bans-log') }}
                    </a>
                </p>
                <p class="form__group form__group--horizontal">
                    <a class="form__button form__button--text" href="{{ route('staff.authentications.index') }}">
                        <i class="{{ config('other.font-awesome') }} fa-file"></i>
                        {{ __('staff.failed-login-log') }}
                    </a>
                </p>
                <p class="form__group form__group--horizontal">
                    <a class="form__button form__button--text" href="{{ route('staff.gifts.index') }}">
                        <i class="{{ config('other.font-awesome') }} fa-file"></i>
                        {{ __('staff.gifts-log') }}
                    </a>
                </p>
                <p class="form__group form__group--horizontal">
                    <a class="form__button form__button--text" href="{{ route('staff.invites.index') }}">
                        <i class="{{ config('other.font-awesome') }} fa-file"></i>
                        {{ __('staff.invites-log') }}
                    </a>
                </p>
                <p class="form__group form__group--horizontal">
                    <a class="form__button form__button--text" href="{{ route('staff.notes.index') }}">
                        <i class="{{ config('other.font-awesome') }} fa-file"></i>
                        {{ __('staff.user-notes') }}
                    </a>
                </p>
                @if (auth()->user()->group->is_owner)
                <p class="form__group form__group--horizontal">
                    <a class="form__button form__button--text" href="{{ route('staff.laravellog.index') }}">
                        <i class="fa fa-file"></i> {{ __('staff.laravel-log') }}
                    </a>
                </p>
                @endif
                <p class="form__group form__group--horizontal">
                    <a class="form__button form__button--text" href="{{ route('staff.reports.index') }}">
                        <i class="{{ config('other.font-awesome') }} fa-file"></i>
                        {{ __('staff.reports-log') }} ({{ $unsolvedReportsCount }})
                        @if ($unsolvedReportsCount > 0)
                            <x-animation.notification />
                        @endif
                    </a>
                </p>
                <p class="form__group form__group--horizontal">
                    <a class="form__button form__button--text" href="{{ route('staff.warnings.index') }}">
                        <i class="{{ config('other.font-awesome') }} fa-file"></i>
                        {{ __('staff.warnings-log') }}
                    </a>
                </p>
            </div>
        </section>
    </div>
@endsection

@section('sidebar')
    <section class="panelV2">
        <h2 class="panel__heading">SSL证书</h2>
        <dl class="key-value">
            <dt>网址</dt>
            <dd>{{ config('app.url') }}</dd>
            @if (request()->secure())
                <dt>连接性</dt>
                <dd>安全</dd>
                <dt>颁发者</dt>
                <dd>{{ (!is_string($certificate)) ? $certificate->getIssuer() : "No Certificate Info Found" }}</dd>
                <dt>过期时间</dt>
                <dd>{{ (!is_string($certificate)) ? $certificate->expirationDate()->diffForHumans() : "No Certificate Info Found" }}</dd>
            @else
                <dt>连接性</dt>
                <dd>
                    <strong>不安全</strong>
                </dd>
                <dt>颁发者</dt>
                <dd>N/A</dd>
                <dt>过期时间</dt>
                <dd>N/A</dd>
            @endif
        </dl>
    </section>
    <section class="panelV2">
        <h2 class="panel__heading">服务器</h2>
        <dl class="key-value">
            <dt>操作系统</dt>
            <dd>{{ $basic['os'] }}</dd>
            <dt>PHP版本</dt>
            <dd>{{ $basic['php'] }}</dd>
            <dt>数据库版本</dt>
            <dd>{{ $basic['database'] }}</dd>
            <dt>Laravel版本</dt>
            <dd>{{ $basic['laravel'] }}</dd>
            <dt>{{ config('unit3d.codebase') }}</dt>
            <dd>{{ config('unit3d.version') }}</dd>
        </dl>
    </section>
    <div class="dashboard__stats">
        <section class="panelV2 panel--grid-item">
            <h2 class="panel__heading">种子信息</h2>
            <dl class="key-value">
                <dt>总计</dt>
                <dd>{{ $torrents->total }}</dd>
                <dt>待审核</dt>
                <dd>{{ $torrents->pending }}</dd>
                <dt>已批准</dt>
                <dd>{{ $torrents->approved }}</dd>
                <dt>已推迟</dt>
                <dd>{{ $torrents->postponed }}</dd>
                <dt>已拒绝</dt>
                <dd>{{ $torrents->rejected }}</dd>
            </dl>
        </section>
        <section class="panelV2 panel--grid-item">
            <h2 class="panel__heading">连接数</h2>
            <dl class="key-value">
                <dt>总计</dt>
                <dd>{{ $peers->total }}</dd>
                <dt>活跃</dt>
                <dd>{{ $peers->active }}</dd>
                <dt>不活跃</dt>
                <dd>{{ $peers->inactive }}</dd>
                <dt>做种</dt>
                <dd>{{ $peers->seeders }}</dd>
                <dt>下载</dt>
                <dd>{{ $peers->leechers }}</dd>
            </dl>
        </section>
        <section class="panelV2 panel--grid-item">
            <h2 class="panel__heading">用户</h2>
            <dl class="key-value">
                <dt>总数</dt>
                <dd>{{ $users->total }}</dd>
                <dt>待验证</dt>
                <dd>{{ $users->validating }}</dd>
                <dt>流放</dt>
                <dd>{{ $users->banned }}</dd>
            </dl>
        </section>
        <section class="panelV2 panel--grid-item">
            <h2 class="panel__heading">内存</h2>
            <dl class="key-value">
                <dt>总计</dt>
                <dd>{{ $ram['total'] }}</dd>
                <dt>已使用</dt>
                <dd>{{ $ram['used'] }}</dd>
                <dt>空闲</dt>
                <dd>{{ $ram['available'] }}</dd>
            </dl>
        </section>
        <section class="panelV2 panel--grid-item">
            <h2 class="panel__heading">硬盘</h2>
            <dl class="key-value">
                <dt>总计</dt>
                <dd>{{ $disk['total'] }}</dd>
                <dt>已使用</dt>
                <dd>{{ $disk['used'] }}</dd>
                <dt>空闲</dt>
                <dd>{{ $disk['free'] }}</dd>
            </dl>
        </section>
        <section class="panelV2 panel--grid-item">
            <h2 class="panel__heading">平均负载</h2>
            <dl class="key-value">
                <dt>1分钟</dt>
                <dd>{{ $avg['1-minute'] }}</dd>
                <dt>5分钟</dt>
                <dd>{{ $avg['5-minute'] }}</dd>
                <dt>15分钟</dt>
                <dd>{{ $avg['15-minute'] }}</dd>
            </dl>
        </section>
    </div>
    <section class="panelV2">
        <h2 class="panel__heading">权限</h2>
        <div class="data-table-wrapper">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>目录</th>
                        <th>当前</th>
                        <th><abbr title="Recommended">建议</abbr></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($file_permissions as $permission)
                        <tr>
                            <td>{{ $permission['directory'] }}</td>
                            <td>
                                @if ($permission['permission'] === $permission['recommended'])
                                    <i class="{{ config('other.font-awesome') }} fa-check-circle"></i>
                                    {{ $permission['permission'] }}
                                @else
                                    <i class="{{ config('other.font-awesome') }} fa-times-circle"></i>
                                    {{ $permission['permission'] }}
                                @endif
                            </td>
                            <td>{{ $permission['recommended'] }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </section>
@endsection
