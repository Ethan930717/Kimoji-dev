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
    <li class="breadcrumb--active">
        客户端
    </li>
@endsection

@section('page', 'page__stats--clients')

@section('main')
    <section class="panelV2">
        <h2 class="panel__heading">客户端统计</h2>
        <div class="data-table-wrapper">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>客户端</th>
                        <th>{{ __('common.users') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($clients as $client => $count)
                        <tr>
                            <td>{{ $client }}</td>
                            <td>使用人数 {{ $count }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </section>
@endsection
