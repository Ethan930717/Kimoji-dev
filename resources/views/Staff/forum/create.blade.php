@extends('layout.default')

@section('title')
    <title>添加板块 - {{ __('staff.staff-dashboard') }} - {{ config('other.title') }}</title>
@endsection

@section('meta')
    <meta name="description" content="Add Forums - {{ __('staff.staff-dashboard') }}">
@endsection

@section('breadcrumbs')
    <li class="breadcrumbV2">
        <a href="{{ route('staff.dashboard.index') }}" class="breadcrumb__link">
            {{ __('staff.staff-dashboard') }}
        </a>
    </li>
    <li class="breadcrumbV2">
        <a href="{{ route('staff.forums.index') }}" class="breadcrumb__link">
            {{ __('staff.forums') }}
        </a>
    </li>
    <li class="breadcrumb--active">
        {{ __('common.new-adj') }}
    </li>
@endsection

@section('page', 'page__forums-admin--create')

@section('main')
    <section class="panelV2">
        <h2 class="panel__heading">添加新板块</h2>
        <div class="panel__body">
            <form class="form" method="POST" action="{{ route('staff.forums.store') }}">
                @csrf
                <p class="form__group">
                    <select id ="forum_type" class="form__select use-select2" name="forum_type" required>
                        <option class="form__option" value="category">分类</option>
                        <option class="form__option" value="forum">板块</option>
                    </select>
                    <label class="form__label form__label--floating" for="forum_type">板块分类</label>
                </p>
                <p class="form__group">
                    <input id="name" class="form__text" type="text" name="name" required>
                    <label class="form__label form__label--floating" for="name">标题</label>
                </p>
                <p class="form__group">
                    <textarea id="description" class="form__textarea" name="description" placeholder=" "></textarea>
                    <label class="form__label form__label--floating" for="description">描述</label>
                </p>
                <p class="form__group">
                    <select id="parent_id" class="form__select use-select2" name="parent_id">
                        <option value="">新的分类</option>
                        @foreach ($categories as $category)
                            <option class="form__option" value="{{ $category->id }}">
                                {{ $category->name }} 分类下的新板块
                            </option>
                        @endforeach
                    </select>
                    <label class="form__label form__label--floating" for="parent_id">主板块</label>
                </p>
                <p class="form__group">
                    <input
                        id="position"
                        class="form__text"
                        inputmode="numeric"
                        name="position"
                        pattern="[0-9]*"
                        placeholder=" "
                        type="text"
                    >
                    <label class="form__label form__label--floating" for="position">{{ __('common.position') }}</label>
                </p>
                <p class="form__group">
                    <h3>权限</h3>
                    <div class="data-table-wrapper">
                        <table class="data-table">
                            <thead>
                            <tr>
                                <th>群组</th>
                                <th>查看论坛</th>
                                <th>阅读主题</th>
                                <th>添加新主题</th>
                                <th>回复主题</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach ($groups as $group)
                                <tr>
                                    <th>{{ $group->name }}</th>
                                    <td><label>
                                            <input type="checkbox" name="permissions[{{ $group->id }}][show_forum]" value="1" checked>
                                        </label></td>
                                    <td><label>
                                            <input type="checkbox" name="permissions[{{ $group->id }}][read_topic]" value="1" checked>
                                        </label></td>
                                    <td><label>
                                            <input type="checkbox" name="permissions[{{ $group->id }}][start_topic]" value="1" checked>
                                        </label></td>
                                    <td><label>
                                            <input type="checkbox" name="permissions[{{ $group->id }}][reply_topic]" value="1" checked>
                                        </label></td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </p>
                <p class="form__group">
                    <button class="form__button form__button--filled">保存</button>
                </p>
            </form>
        </div>
    </section>
@endsection
