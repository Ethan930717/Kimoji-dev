@extends('layout.default')

@section('breadcrumbs')
    <li class="breadcrumbV2">
        <a href="{{ route('mediahub.index') }}" class="breadcrumb__link">
            {{ __('mediahub.title') }}
        </a>
    </li>
    <li class="breadcrumbV2">
        <a href="{{ route('artists.index') }}" class="breadcrumb__link">
            {{ __('mediahub.persons') }}
        </a>
    </li>
    <li class="breadcrumb--active">
        {{ $artist->name }}
    </li>
@endsection

@section('main')
    @livewire('person-credit', ['person' => $artist])
@endsection

@section('sidebar')
    <section class="panelV2">
        <h2 class="panel__heading">{{ $artist->name }}</h2>
        <img
            src="{{ isset($artist->image) ? $artist->image : 'https://via.placeholder.com/300x450' }}"
            alt="{{ $artist->name }}"
            style="max-width: 100%"
        />
        <dl class="key-value">
            <dt>{{ __('mediahub.born') }}</dt>
            <dd>{{ $artist->birthday ?? __('common.unknown') }}</dd>
            <dt>国家/地区</dt>
            <dd>{{ $artist->country ?? __('common.unknown') }}</dd>
        </dl>
        <div class="panel__body">{{ $artist->biography ?? '暂无介绍' }}</div>
    </section>
@endsection
