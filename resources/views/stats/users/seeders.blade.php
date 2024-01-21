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
        {{ __('common.users') }}
    </li>
@endsection

@section('nav-tabs')
    @include('partials.statsusermenu')
@endsection

@section('page', 'page__stats--seeders')

@section('main')
    <section class="panelV2">
        <h2 class="panel__heading">KIMOJI {{ __('stat.top-seeders') }}</h2>
        <div class="data-table-wrapper">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>{{ __('common.user') }}</th>
                        <th>总保种数</th>
                        <th>官种</th>
                        <th>音频类官种</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($seeders as $user)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>
                                <x-user_tag
                                    :user="$user->user"
                                    :anon="$user->user->private_profile"
                                />
                            </td>
                            <td>{{ $seeder->value }}</td>
                            <td>{{ $seeder->officialCount }}</td> <!-- 官种总数 -->
                            <td>{{ $seeder->audioOfficialCount }}</td> <!-- 音频资源官种总数 -->
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </section>
@endsection
