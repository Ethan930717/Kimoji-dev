@extends('layout.default')

@section('title')
    <title>{{ $user->username }} - Settings - {{ __('common.members') }} - {{ config('other.title') }}</title>
@endsection

@section('breadcrumbs')
    <li class="breadcrumbV2">
        <a href="{{ route('users.show', ['user' => $user]) }}" class="breadcrumb__link">
            {{ $user->username }}
        </a>
    </li>
    <li class="breadcrumb--active">
        {{ __('user.settings') }}
    </li>
@endsection

@section('nav-tabs')
    @include('user.buttons.user')
@endsection

@section('content')
    <section class="panelV2">
        <h2 class="panel__heading">{{ __('user.settings') }}</h2>
        <div class="panel__body">
            <form
                class="form"
                method="POST"
                action="{{ route('users.general_settings.update', ['user' => $user]) }}"
                enctype="multipart/form-data"
            >
                @csrf
                @method('PATCH')
                <p class="form__group">
                    <select id="locale" class="form__select" name="locale" required>
                        @foreach (App\Models\Language::allowed() as $code => $name)
                            <option class="form__option" value="{{ $code }}" @selected($user->locale === $code)>
                                {{ $name }}
                            </option>
                        @endforeach
                    </select>
                    <label class="form__label form__label--floating" for="locale">
                        语言
                    </label>
                </p>
                <fieldset class="form form__fieldset">
                    <legend class="form__legend">风格</legend>
                    <p class="form__group">
                        <select id="style" class="form__select" name="style" required>
                            <option class="form__option" value="0" @selected($user->style === 0)>KIMOJIの旷野</option>

                        </select>
                        <label class="form__label form__label--floating" for="style">
                            主题
                        </label>
                    </p>
                    <p class="form__group">
                        <input
                            id="custom_css"
                            class="class="form__text"
"
                            name="custom_css"
                            placeholder=" "
                            type="url"
                            value="{{ $user->custom_css }}"
                        >
                        <label class="form__label form__label--floating" for="custom_css">
                            外部CSS样式表（叠加在主题之上）
                        </label>
                    </p>
                    <p class="form__group">
                        <input
                            id="standalone_css"
                            class="class="form__text"
"
                            name="standalone_css"
                            placeholder=" "
                            type="url"
                            value="{{ $user->standalone_css }}"
                        >
                        <label class="form__label form__label--floating" for="standalone_css">
                            独立CSS样式表（不使用网站主题）
                        </label>
                    </p>
                </fieldset>
                <fieldset class="form__fieldset">
                    <legend class="form__legend">聊天室</legend>
                    <p class="form__group">
                        <label class="form__label">
                            <input type="hidden" name="censor" value="0">
                            <input
                                class="form__checkbox"
                                type="checkbox"
                                name="censor"
                                value="1"
                                @checked($user->censor)
                            />
                            语言审查（带有敏感词的内容将被过滤）
                        </label>
                    </p>
                    <p class="form__group" style="visibility: hidden">
                        <label class="form__label">
                            <input type="hidden" name="chat_hidden" value="0">
                            <input
                                    class="form__checkbox"
                                    type="checkbox"
                                    name="chat_hidden"
                                    value="0"
                                    @checked($user->chat_hidden)
                            />
                            Hide Chat
                        </label>
                    </p>
                </fieldset>
                <fieldset class="form form__fieldset">
                    <legend class="form__legend">资源相关</legend>
                    <p class="form__group">
                        <select id="torrent_layout" class="form__select" name="torrent_layout" required>
                            <option class="form__option" value="0" @selected($user->torrent_layout === 0)>列表模式</option>
                            <option class="form__option" value="1" @selected($user->torrent_layout === 1)>卡片模式</option>
                            <option class="form__option" value="2" @selected($user->torrent_layout === 2)>分组模式</option>
                            <option class="form__option" value="3" @selected($user->torrent_layout === 3)>海报墙</option>

                        </select>
                        <label class="form__label form__label--floating" for="torrent_layout">
                            默认资源布局
                        </label>
                    </p>
                    <p class="form__group">
                        <select id="ratings" class="form__select" name="ratings" required>
                            <option class="form__option" value="0" @selected($user->ratings === 0)>TMDB</option>
                            <option class="form__option" value="1" @selected($user->ratings === 1)>IMDB</option>
                        </select>
                        <label class="form__label form__label--floating" for="ratings">
                            评分标准
                        </label>
                    </p>
                    <p class="form__group">
                        <label class="form__label">
                            <input type="hidden" name="show_poster" value="1">
                            <input
                                class="form__checkbox"
                                type="checkbox"
                                name="show_poster"
                                value="1"
                                @checked($user->show_poster)
                            />
                            在种子列表视图中显示海报
                        </label>
                    </p>
                </fieldset>
                <p class="form__group">
                    <button class="form__button form__button--filled">
                        {{ __('common.save') }}
                    </button>
                </p>
            </form>
        </div>
    </section>
@endsection
