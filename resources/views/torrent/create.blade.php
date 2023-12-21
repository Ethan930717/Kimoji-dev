@extends('layout.default')

@section('title')
    <title>Upload - {{ config('other.title') }}</title>
@endsection

@section('breadcrumbs')
    <li class="breadcrumbV2">
        <a href="{{ route('torrents.index') }}" class="breadcrumb__link">
            {{ __('torrent.torrents') }}
        </a>
    </li>
    <li class="breadcrumb--active">
        {{ __('common.upload') }}
    </li>
@endsection

@section('nav-tabs')
    <li class="nav-tabV2">
        <a class="nav-tab__link"
            href="{{ route('torrents.index', ['view' => match(auth()->user()->torrent_layout) {
                1       => 'card',
                2       => 'group',
                3       => 'poster',
                default => 'list'
            }]) }}"
        >
            {{ __('torrent.search') }}
        </a>
    </li>
    <li class="nav-tabV2">
        <a class="nav-tab__link" href="{{ route('top10.index') }}">
            {{ __('common.top-10') }}
        </a>
    </li>
    <li class="nav-tabV2">
        <a class="nav-tab__link" href="{{ route('rss.index') }}">
            {{ __('rss.rss') }}
        </a>
    </li>
    <li class="nav-tab--active">
        <a class="nav-tab--active__link" href="{{ route('torrents.create', ['category_id' => 1]) }}">
            {{ __('common.upload') }}
        </a>
    </li>
@endsection

@section('main')
    @if ($user->can_upload == 0 || $user->group->can_upload == 0)
        <section class="panelV2">
            <h2 class="panel__heading">
                <i class="{{ config('other.font-awesome') }} fa-times text-danger"></i>
                {{ __('torrent.cant-upload') }}!
            </h2>
            <p class="panel__body">{{ __('torrent.cant-upload-desc') }}!</p>
        </section>
    @else
        <section
            class="upload panelV2"
            x-data="{
                cat: {{(int)$category_id}},
                cats: JSON.parse(atob('{!! base64_encode(json_encode($categories)) !!}'))
            }"
        >
            <h2 class="upload-title panel__heading">
                <i class="{{ config('other.font-awesome') }} fa-file"></i>
                {{ __('torrent.torrent') }}
            </h2>
            <div class="panel__body">
                <form
                    name="upload"
                    class="upload-form form"
                    id="upload-form"
                    method="POST"
                    action="{{ route('torrents.store') }}"
                    enctype="multipart/form-data"
                >
                    @csrf
                    <p class="form__group">
                        <label for="torrent" class="form__label">种子 {{ __('torrent.file') }}</label>
                        <input
                            class="upload-form-file form__file"
                            type="file"
                            accept=".torrent"
                            name="torrent"
                            id="torrent"
                            required
                            @change="uploadExtension.hook(); cat = $refs.catId.value"
                        >
                    </p>
                    <p class="form__group" x-show="cats[cat].type === 'movie' || cats[cat].type === 'tv'">
                        <label for="nfo" class="form__label">
                            NFO {{ __('torrent.file') }} ({{ __('torrent.optional') }})
                        </label>
                        <input id="nfo" class="upload-form-file form__file" type="file" accept=".nfo" name="nfo">
                    </p>
                    <p class="form__group" x-show="cats[cat].type === 'music'">
                        <label for="torrent-cover" class="form__label">
                            封面（必选）
                        </label>
                        <input id="torrent-cover" class="upload-form-file form__file" type="file" accept=".jpg, .jpeg, .png" name="torrent-cover">
                    </p>
                    <p class="form__group" x-show="cats[cat].type === 'music'">
                        <label for="torrent-banner" class="form__label">
                            海报 (必选)
                        </label>
                        <input id="torrent-banner" class="upload-form-file form__file" type="file" accept=".jpg, .jpeg, .png" name="torrent-banner">
                    </p>
                    <p class="form__group" x-show="cats[cat].type === 'music'">
                        <label for="single-music" class="form__label">
                            试听单曲
                        </label>
                        <input id="single-music" class="upload-form-file form__file" type="file" accept="audio/*" name="single-music">
                    <div id="progress-container" style="width: 100%; background: #eee;">
                        <div id="progress-bar" style="height: 20px; width: 0%; background: #b4d455;"></div>
                    </div>
                    </p>
                    <p class="form__group">
                        <input
                            type="text"
                            name="name"
                            id="title"
                            class="form__text"
                            value="{{ $title ?: old('name') }}"
                            required
                        >
                        <label class="form__label form__label--floating" for="title">{{ __('torrent.title') }}</label>
                    </p>
                    <p class="form__group">
                        <select
                            x-ref="catId"
                            name="category_id"
                            id="autocat"
                            class="form__select"
                            required
                            x-model="cat"
                            @change="cats[cat].type = cats[$event.target.value].type;"
                        >
                            <option hidden selected disabled value=""></option>
                            @foreach ($categories as $id => $category)
                                <option class="form__option" value="{{ $id }}">
                                    {{ $category['name'] }}
                                </option>
                            @endforeach
                        </select>
                        <label class="form__label form__label--floating" for="autocat">
                            {{ __('torrent.category') }}
                        </label>
                    </p>
                    <p class="form__group">
                        <select
                                name="type_id"
                                id="autotype_music"
                                class="form__select"
                                x-bind:required="cats[cat].type === 'music'"
                                x-show="cats[cat].type === 'music'">
                        >
                            <option hidden disabled selected value=""></option>
                            @foreach ($types as $index => $type)
                                @if ($index >= 7)
                                    <option value="{{ $type->id }}" @selected(old('type_id')==$type->id) x-show="cats[cat].type === 'music'">
                                        {{ $type->name }}
                                    </option>
                                @endif
                            @endforeach
                        </select>
                        <select
                                name="type_id"
                                id="autotype"
                                class="form__select"
                                x-model="type_id"
                                x-bind:required="cats[cat].type !== 'music'"
                                x-show="cats[cat].type !== 'music'">
                        >
                            <option hidden disabled selected value=""></option>
                            @foreach ($types as $index => $type)
                                @if ($index < 7)
                                    <option value="{{ $type->id }}" @selected(old('type_id')==$type->id) x-show="cats[cat].type !== 'no'">
                                        {{ $type->name }}
                                    </option>
                                @endif
                            @endforeach
                        </select>
                        <label class="form__label form__label--floating" for="autotype">
                            {{ __('torrent.type') }}
                        </label>
                    </p>
                    <p class="form__group" x-show="cats[cat].type === 'movie' || cats[cat].type === 'tv'">
                        <select
                            name="resolution_id"
                            id="autores"
                            class="form__select"
                            x-bind:required="cats[cat].type === 'movie' || cats[cat].type === 'tv'"
                        >
                            <option hidden disabled selected value=""></option>
                            @foreach ($resolutions as $resolution)
                                <option value="{{ $resolution->id }}" @selected(old('resolution_id')==$resolution->id)>
                                    {{ $resolution->name }}
                                </option>
                            @endforeach
                        </select>
                        <label class="form__label form__label--floating" for="autores">
                            {{ __('torrent.resolution') }}
                        </label>
                    </p>
                    <div class="form__group--horizontal" x-show="cats[cat].type === 'music'">
                        <p class="form__group" x-show="cat == 3">
                            <select
                                name="distributor_id"
                                id="autodis"
                                class="form__select"
                                x-data="{ distributor: '' }"
                                x-model="distributor"
                                x-bind:class="distributor === '' ? 'form__select--default' : ''"
                            >
                                <option selected value=""></option>
                                @foreach ($distributors as $distributor)
                                    <option value="{{ $distributor->id }}" @selected(old('distributor_id')==$distributor->id)>
                                        {{ $distributor->name }}
                                    </option>
                                @endforeach
                            </select>
                            <label class="form__label form__label--floating" for="autodis">
                                {{ __('torrent.distributor') }}
                            </label>
                        </p>
                        <p class="form__group" x-show="cat == 4">
                            <select
                                name="region_id"
                                id="autoreg"
                                class="form__select"
                                x-data="{ region: '' }"
                                x-model="region"
                                x-bind:class="region === '' ? 'form__select--default' : ''"
                            >
                                <option selected value=""></option>
                                @foreach ($regions as $region)
                                    <option value="{{ $region->id }}" @selected(old('region_id')==$region->id)>
                                        {{ $region->name }}
                                    </option>
                                @endforeach
                            </select>
                            <label class="form__label form__label--floating" for="autoreg">
                                {{ __('torrent.region') }}
                            </label>
                        </p>
                    </div>
                    <div class="form__group--horizontal" x-show="cats[cat].type === 'tv'">
                        <p class="form__group">
                            <input
                                type="text"
                                name="season_number"
                                id="season_number"
                                class="form__text"

                                inputmode="numeric"
                                pattern="[0-9]*"
                                value="{{ old('season_number') }}"
                                x-bind:required="cats[cat].type === 'tv'"
                            >
                            <label class="form__label form__label--floating" for="season_number">
                                {{ __('torrent.season-number') }}
                            </label>
                        </p>
                        <p class="form__group">
                            <input
                                type="text"
                                name="episode_number"
                                id="episode_number"
                                class="form__text"

                                inputmode="numeric"
                                pattern="[0-9]*"
                                value="{{ old('episode_number') }}"
                                x-bind:required="cats[cat].type === 'tv'"
                            >
                            <label class="form__label form__label--floating" for="episode_number">
                                {{ __('torrent.episode-number') }} (输入“0”代表整季)
                            </label>
                        </p>
                    </div>
                    <div class="form__group--horizontal" x-show="cats[cat].type === 'movie' || cats[cat].type === 'tv'">
                        <p class="form__group">
                            <input type="hidden" name="tmdb" value="0" />
                            <input
                                type="text"
                                name="tmdb"
                                id="autotmdb"
                                class="form__text"

                                inputmode="numeric"
                                pattern="[0-9]*"
                                x-bind:value="(cats[cat].type === 'movie' || cats[cat].type === 'tv') ? '{{ $tmdb ?: old('tmdb') }}' : '0'"
                                x-bind:required="cats[cat].type === 'movie' || cats[cat].type === 'tv'"
                            >
                            <label class="form__label form__label--floating" for="autotmdb">TMDB ID</label>
                            <output name="apimatch" id="apimatch" for="torrent"></output>
                        </p>
                        <p class="form__group">
                            <input type="hidden" name="imdb" value="0" />
                            <input
                                type="text"
                                name="imdb"
                                id="autoimdb"
                                class="form__text"

                                inputmode="numeric"
                                pattern="[0-9]*"
                                x-bind:value="(cats[cat].type === 'movie' || cats[cat].type === 'tv') ? '{{ $imdb ?: old('imdb') }}' : '0'"
                                x-bind:required="cats[cat].type === 'movie' || cats[cat].type === 'tv'"
                            >
                            <label class="form__label form__label--floating" for="autoimdb">IMDB ID</label>
                        </p>
                        <p class="form__group" style="display:none" x-show="cats[cat].type === 'tv'">
                            <input type="hidden" name="tvdb" value="0" />
<!--
                            <input
                                type="text"
                                name="tvdb"
                                id="autotvdb"
                                inputmode="numeric"
                                pattern="[0-9]*"
                                x-bind:value="cats[cat].type === 'tv' ? '{{ $tvdb ?: old('tvdb') }}' : '0'"
                                class="form__text"

                                x-bind:required="cats[cat].type === 'tv'"
                            >
-->
<!--
                            <label class="form__label form__label&#45;&#45;floating" for="autotvdb">TVDB ID</label>
-->
                        </p>
                        <p class="form__group" style="display:none">
                            <input type="hidden" name="mal" value="0" />
<!--
                            <input
                                type="text"
                                name="mal"
                                id="automal"
                                inputmode="numeric"
                                pattern="[0-9]*"
                                x-bind:value="(cats[cat].type === 'movie' || cats[cat].type === 'tv') ? '{{ $mal ?: old('mal') }}' : '0'"
                                x-bind:required="cats[cat].type === 'movie' || cats[cat].type === 'tv'"
                                class="form__text"

                                placeholder=" "
                            >
-->
<!--
                            <label class="form__label form__label&#45;&#45;floating" for="automal">MAL ID ({{ __('torrent.required-anime') }})</label>
-->
                        </p>
                    </div>
                    <p class="form__group" x-show="cats[cat].type === 'game'">
                        <input
                            type="text"
                            name="igdb"
                            id="autoigdb"
                            inputmode="numeric"
                            pattern="[0-9]*"
                            x-bind:value="cats[cat].type === 'game' ? '{{ $igdb ?: old('igdb') }}' : '0'"
                            class="form__text"

                            x-bind:required="cats[cat].type === 'game'"
                        >
                        <label class="form__label form__label--floating" for="autoigdb">IGDB ID <b>({{ __('torrent.required-games') }})</b></label>
                    </p>
                    <p class="form__group" style="display: none;" x-show="cats[cat].type === 'movie' || cats[cat].type === 'tv'">
                        <input
                            type="text"
                            name="keywords"
                            id="autokeywords"
                            class="form__text"
                            value="{{ old('keywords') }}"
                            placeholder=" "
                        >
                        <label class="form__label form__label--floating" for="autokeywords">
                            {{ __('torrent.keywords') }} (<i>{{ __('torrent.keywords-example') }}</i>)
                        </label>
                    </p>
                    @livewire('bbcode-input', ['name' => 'description', 'label' => __('common.description'), 'required' => true])
                    <p class="form__group" x-show="(cats[cat].type === 'movie' || cats[cat].type === 'tv') && (type_id != 1 && type_id != 2)">
                        <textarea
                            id="upload-form-mediainfo"
                            name="mediainfo"
                            class="form__textarea"
                            placeholder=" "
                        >{{ old('mediainfo') }}</textarea>
                        <label class="form__label form__label--floating" for="upload-form-mediainfo">
                            {{ __('torrent.media-info-parser') }}
                        </label>
                    </p>
                    <p class="form__group" x-show="(cats[cat].type === 'movie' || cats[cat].type === 'tv') && (type_id == 1 && type_id == 2)">
                        <textarea
                            id="upload-form-bdinfo"
                            name="bdinfo"
                            class="form__textarea"
                            placeholder=" "
                        >{{ old('bdinfo') }}</textarea>
                        <label class="form__label form__label--floating" for="upload-form-bdinfo">
                            BDInfo (Quick Summary)
                        </label>
                    </p>
                    <p class="form__group">
                        <input type="hidden" name="anon" value="0">
                        <input
                            type="checkbox"
                            class="form__checkbox"
                            id="anon"
                            name="anon"
                            value="1"
                            @checked(old('anon'))
                        >
                        <label class="form__label" for="anon">{{ __('common.anonymous') }} </label>
                    </p>
                    <p class="form__group" x-show="cats[cat].type === 'movie' || cats[cat].type === 'tv'">
                        <input type="hidden" name="stream" value="0">
                        <input
                            type="checkbox"
                            class="form__checkbox"
                            id="stream"
                            name="stream"
                            x-bind:value="(cats[cat].type === 'movie' || cats[cat].type === 'tv') ? '1' : '0'"
                            @checked(old('stream'))
                        >
                        <label class="form__label" for="stream">{{ __('torrent.stream-optimized') }} </label>
                    </p>
                    <p class="form__group" x-show="cats[cat].type === 'movie' || cats[cat].type === 'tv'">
                        <input type="hidden" name="sd" value="0">
                        <input
                            type="checkbox"
                            class="form__checkbox"
                            id="sd"
                            name="sd"
                            x-bind:value="(cats[cat].type === 'movie' || cats[cat].type === 'tv') ? '1' : '0'"
                            @checked(old('sd'))
                        >
                        <label class="form__label" for="sd">{{ __('torrent.sd-content') }} </label>
                    </p>
                    @if (auth()->user()->group->is_modo || auth()->user()->group->is_internal)
                        <p class="form__group">
                            <input type="hidden" name="internal" value="0">
                            <input
                                type="checkbox"
                                class="form__checkbox"
                                id="internal"
                                name="internal"
                                value="1"
                                @checked(old('internal'))
                            >
                            <label class="form__label" for="internal">{{ __('torrent.internal') }} </label>
                        </p>
                    @endif
                    <p class="form__group">
                        <input type="hidden" name="personal_release" value="0">
                        <input
                            type="checkbox"
                            class="form__checkbox"
                            id="personal_release"
                            name="personal_release"
                            value="1"
                            @checked(old('personal_release'))
                        >
                        <label class="form__label" for="personal_release">个人发布 </label>
                    </p>
                    @if ($user->group->is_trusted)
                        <p class="form__group">
                            <input type="hidden" name="mod_queue_opt_in" value="0">
                            <input
                                type="checkbox"
                                class="form__checkbox"
                                id="mod_queue_opt_in"
                                name="mod_queue_opt_in"
                                value="1"
                                @checked(old('mod_queue_opt_in'))
                            >
                            <label class="form__label" for="mod_queue_opt_in">提交审核 </label>
                        </p>
                    @endif
                    @if (auth()->user()->group->is_modo || auth()->user()->group->is_internal)
                        <p class="form__group">
                            <input type="hidden" name="refundable" value="0">
                            <input
                                    type="checkbox"
                                    class="form__checkbox"
                                    id="refundable"
                                    name="refundable"
                                    value="1"
                                    @checked(old('refundable'))
                            >
                            <label class="form__label" for="refundable">{{ __('torrent.refundable') }} </label>
                        </p>
                    @endif
                    @if (auth()->user()->group->is_modo || auth()->user()->group->is_internal)
                        <p class="form__group">
                            <select name="free" id="free" class="form__select">
                                <option value="0" @selected(old('free') === '0' || old('free') === null)>{{ __('common.no') }}</option>
                                <option value="25" @selected(old('free') === '25')>25%</option>
                                <option value="50" @selected(old('free') === '50')>50%</option>
                                <option value="75" @selected(old('free') === '75')>75%</option>
                                <option value="100" @selected(old('free') === '100')>100%</option>
                            </select>
                            <label class="form__label form__label--floating" for="free">
                                {{ __('torrent.freeleech') }}
                            </label>
                        </p>
                    @endif
                    <p class="form__group">
                        <button type="submit" class="form__button form__button--filled" name="post" value="true" id="post">
                            {{ __('common.submit') }}
                        </button>
                    </p>
                    <br>
                </form>
            </div>
        </section>
    @endif
@endsection

@if ($user->can_upload == 1 && $user->group->can_upload == 1)
    @section('sidebar')
        <section class="panelV2">
            <h2 class="panel__heading">
                <i class="{{ config('other.font-awesome') }} fa-info"></i>
                发布须知(首次发种请详细阅读）
            </h2>
            <div class="panel__body">
                <p class="text-success">{!! __('torrent.announce-url-desc-url', ['url' => config('other.upload-guide_url')]) !!}</p>
                <a href="/pages/3" style="font-size:18px; text-align:center; cursor:pointer;" id="generalRulesLink">
                    <i class="fas fa-gavel"></i> 发布总则
                </a>
                <br>
                <br>

                <a href="/pages/5" style="font-size:18px; text-align:center; cursor:pointer;" id="repostRulesLink">
                    <i class="fas fa-copy"></i> 转载规则
                </a>
                <br>
                <br>

                <a href="/gallery" style="font-size:18px; text-align:center; cursor:pointer;" id="originalDiskRulesLink">
                    <i class="fas fa-photo-film"></i> 友站插画
                </a>
                <br>
                <br>

                <a href="/pages/4" style="font-size:18px; text-align:center; cursor:pointer;" id="originalDiskRulesLink">
                    <i class="fas fa-compact-disc"></i> 原盘发布规则
                </a>
                <br>
                <br>

                <a href="/pages/7" style="font-size:18px; text-align:center; cursor:pointer;" id="repostRulesLink">
                    <i class="fas fa-music"></i> 音乐媒介说明
                </a>
                <br>
                <br>
                <a style="font-size:18px; text-align:center; cursor:pointer;" id="trackerLink" data-link="{{ route('announce', ['passkey' => $user->passkey]) }}" x-data x-on:click.stop="
    navigator.clipboard.writeText($el.dataset.link);
    Swal.fire({
          toast: true,
          position: 'top-end',
          showConfirmButton: false,
          timer: 3000,
          icon: 'success',
          title: '复制成功'
    })
">
                    <i class="fas fa-link"></i> 复制Tracker地址
                </a>

            </div>
        </section>
    @endsection
@endif

@section('javascripts')
    <script src="{{ mix('js/imgbb.js') }}" crossorigin="anonymous"></script>
@endsection
