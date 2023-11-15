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
        主题
    </li>
@endsection

@section('page', 'page__stats--themes')

@section('main')
    <section class="panelV2">
        <h2 class="panel__heading">站点主题</h2>
        <div class="data-table-wrapper">
            <table class="data-table">
                @forelse ($siteThemes as $siteTheme)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>
                            @switch($siteTheme->style)
                                @case ('0')
                                KIMOJIの旷野
                                @break
                                @case ('1')
                                Galactic Theme
                                @break
                                @case ('2')
                                Dark Blue Theme
                                @break
                                @case ('3')
                                Dark Green Theme
                                @break
                                @case ('4')
                                Dark Pink Theme
                                @break
                                @case ('5')
                                Dark Purple Theme
                                @break
                                @case ('6')
                                Dark Red Theme
                                @break
                                @case ('7')
                                Dark Teal Theme
                                @break
                                @case ('8')
                                Dark Yellow Theme
                                @break
                                @case ('9')
                                Cosmic Void Theme
                                @break
                            @endswitch
                        </td>
                        <td>使用人数 {{ $siteTheme->value }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="3">无使用者</td>
                    </tr>
                @endforelse
            </table>
        </div>
    </section>

    <section class="panelV2">
        <h2 class="panel__heading">外部CSS样式表</h2>
        <div class="data-table-wrapper">
            <table class="data-table">
                @forelse ($customThemes as $customTheme)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $customTheme->custom_css }}</td>
                        <td>使用人数 {{ $customTheme->value }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="3">无人使用</td>
                    </tr>
                @endforelse
            </table>
        </div>
    </section>

    <section class="panelV2">
        <h2 class="panel__heading">独立CSS样式表</h2>
        <div class="data-table-wrapper">
            <table class="data-table">
                @forelse ($standaloneThemes as $standaloneTheme)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $standaloneTheme->standalone_css }}</td>
                        <td>使用人数 {{ $standaloneTheme->value }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="3">无人使用</td>
                    </tr>
                @endforelse
            </table>
        </div>
    </section>
@endsection