@extends('layout.default')

@section('breadcrumbs')
    <li class="breadcrumb--active">
        {{ __('artists.title') }}
    </li>
    <li class="breadcrumb--active">
        {{ $artist->name }}
    </li>
    <li class="breadcrumb--active">
        {{ __('common.edit') }}
    </li>
@endsection

@section('main')
    <section class="panelV2">
    <h2 class="panel__heading">{{ __('common.edit') }}: {{ $artist->name }}</h2>
    <h2 class="panel__heading" style="font-size: 15px">所有用户都可以编辑歌手信息，但是恶意的编辑行为会受到警告或冻结账号的处罚</h2>
    <div class="panel__body">
    <form method="POST" action="{{ route('artists.update', $artist->id) }}">
        @csrf
        @method('PATCH')
        <p class="form__group">
            <label for="birthday" class="form__label">生日/成立时间</label>
            <input type="date" class="form__text" name="birthday" id="birthday" value="{{ $artist->birthday }}" placeholder="请按照yyyy-mm-dd的格式填写日期">
        </p>

        <p class="form__group">
            <label for="deathday" class="form__label">忌日/解散时间</label>
            <input type="date" class="form__text" name="deathday" id="deathday" value="{{ $artist->deathday }}" placeholder="请按照yyyy-mm-dd的格式填写日期">
        </p>

        <p class="form__group">
            <label for="member" class="form__label">乐队成员</label>
            <input type="text" class="form__text" name="member" id="member" value="{{ $artist->member }}" placeholder="每个名称之间请用'/'符号分隔，非乐队组合请留空">
        </p>

        <p class="form__group">
            <label for="country" class="form__label">国家/地区</label>
            <input type="text" class="form__text" name="country" id="country" value="{{ $artist->country }}" placeholder="中国香港/中国台湾/中国澳门或其他具体国家名称">
        </p>

        <p class="form__group">
            <label for="label" class="form__label">厂牌</label>
            <input type="text" class="form__text" name="label" id="label" value="{{ $artist->label }}" placeholder="每个厂牌名之间请用'/'符号分隔">
        </p>

        <p class="form__group">
            <label for="genre" class="form__label">风格</label>
            <input type="text" class="form__text" name="genre" id="genre" value="{{ $artist->genre }}" placeholder="每种风格之间请用'/'符号分隔">
        </p>

        <p class="form__group">
            <label for="biography" class="form__label">传记</label>
            <textarea name="biography" class="form__text" id="biography" placeholder="请输入艺术家传记">{{ $artist->biography }}</textarea>
        </p>
        <p class="form__group">
            <button class="form__button form__button--filled" type="submit">
                {{ __('common.submit') }}
            </button>
        </p>
    </form>
    </div>
    </section>
@endsection

