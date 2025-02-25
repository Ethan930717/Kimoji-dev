@extends('layout.default')

@section('title')
    <title>Cheated Torrents - {{ __('staff.staff-dashboard') }} - {{ config('other.title') }}</title>
@endsection

@section('meta')
    <meta name="description" content="Cheated Torrents - {{ __('staff.staff-dashboard') }}">
@endsection

@section('breadcrumbs')
    <li class="breadcrumbV2">
        <a href="{{ route('staff.dashboard.index') }}" class="breadcrumb__link">
            {{ __('staff.staff-dashboard') }}
        </a>
    </li>
    <li class="breadcrumb--active">
        作弊资源
    </li>
@endsection

@section('page', 'page__cheated-torrents--index')

@section('main')
    <section class="panelV2">
        <header class="panel__header">
            <h2 class="panel__heading">作弊资源</h2>
            <div class="panel__actions">
                <form
                    class="panel__action"
                    action="{{ route('staff.cheated_torrents.massDestroy') }}"
                    method="POST"
                    x-data
                >
                    @csrf
                    @method('DELETE')
                    <button
                        x-on:click.prevent="Swal.fire({
                            title: '请确认',
                            text: ' 您确定要重置所有种子的数据吗？这将允许您从头开始监控作弊的种子，但意味着以往作弊种子的数据将不再可用',
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
                        重置所有种子的数据
                    </button>
                </form>
            </div>
        </header>
        <div class="data-table-wrapper">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>{{ __('common.name') }}</th>
                        <th title="{{ __('torrent.seeders') }}">
                            <i class="fas fa-arrow-alt-circle-up"></i>
                        </th>
                        <th title="{{ __('torrent.leechers') }}">
                            <i class="fas fa-arrow-alt-circle-down"></i>
                        </th>
                        <th title="{{ __('torrent.times') }}">
                            <i class="fas fa-check-circle"></i>
                        </th>
                        <th>{{ __('torrent.size') }}</th>
                        <th>数据</th>
                        <th>作弊时间</th>
                        <th>全时段数据</th>
                        <th>{{ __('torrent.uploaded') }}</th>
                        <th>{{ __('common.actions') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($torrents as $torrent)
                        <tr>
                            <td>
                                <a href="{{ route('torrents.show', ['id' => $torrent->id]) }}">
                                    {{ $torrent->name }}
                                </a>
                            </td>
                            <td>
                                <a href="{{ route('peers', ['id' => $torrent->id]) }}">
                                    {{ $torrent->seeders }}
                                </a>
                            </td>
                            <td>
                                <a href="{{ route('peers', ['id' => $torrent->id]) }}">
                                    {{ $torrent->leechers }}
                                </a>
                            </td>
                            <td>
                                <a href="{{ route('history', ['id' => $torrent->id]) }}">
                                    {{ $torrent->times_completed }}
                                </a>
                            </td>
                            <td title="{{ $torrent->size }}&nbsp;B">
                                {{ \App\Helpers\StringHelper::formatBytes($torrent->size) }}
                            </td>
                            <td title="{{ $torrent->current_balance }}&nbsp;B">
                                {{ \App\Helpers\StringHelper::formatBytes($torrent->current_balance) }}
                            </td>
                            <td>{{ \round($torrent->times_cheated, 3) }}</td>
                            <td title="{{ $torrent->balance }}&nbsp;B">
                                {{ \App\Helpers\StringHelper::formatBytes($torrent->balance) }}
                            </td>
                            <td>
                                <time datetime="{{ $torrent->created_at }}" title="{{ $torrent->created_at }}">
                                    {{ $torrent->created_at ?? 'N/A' }}
                                </time>
                            </td>
                            <td>
                                <menu class="data-table__actions">
                                    <li class="data-table__action">
                                        <form
                                            action="{{ route('staff.cheated_torrents.destroy', ['cheatedTorrent' => $torrent]) }}"
                                            method="POST"
                                        >
                                            @csrf
                                            @method('DELETE')
                                            <button class="form__button form__button--text">
                                                重置数据
                                            </button>
                                        </form>
                                    </li>
                                </menu>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="10">No cheated torrents</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        {{ $torrents->links('partials.pagination') }}
    </section>
@endsection
