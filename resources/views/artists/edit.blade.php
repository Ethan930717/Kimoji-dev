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
    <h1>编辑艺术家</h1>
    <form method="POST" action="{{ route('artists.update', $artist->id) }}">
        @csrf
        @method('PUT')

        <div>
            <label for="name">名称</label>
            <input type="text" name="name" id="name" value="{{ $artist->name }}" placeholder="请输入艺术家名称" required>
        </div>

        <div>
            <label for="birthday">生日/成立时间</label>
            <input type="date" name="birthday" id="birthday" value="{{ $artist->birthday }}" placeholder="请按照yyyy-mm-dd的格式填写日期">
        </div>

        <div>
            <label for="deathday">忌日/解散时间</label>
            <input type="date" name="deathday" id="deathday" value="{{ $artist->deathday }}" placeholder="请按照yyyy-mm-dd的格式填写日期">
        </div>

        <div>
            <label for="member">组成员</label>
            <input type="text" name="member" id="member" value="{{ $artist->member }}" placeholder="每个名称之间请用'/'符号分隔">
        </div>

        <div>
            <label for="country">国家/地区</label>
            <input type="text" name="country" id="country" value="{{ $artist->country }}" placeholder="请输入国家/地区名称">
        </div>

        <div>
            <label for="label">厂牌</label>
            <input type="text" name="label" id="label" value="{{ $artist->label }}" placeholder="每个名称之间请用'/'符号分隔">
        </div>

        <div>
            <label for="genre">风格</label>
            <input type="text" name="genre" id="genre" value="{{ $artist->genre }}" placeholder="每个名称之间请用'/'符号分隔">
        </div>

        <div>
            <label for="biography">传记</label>
            <textarea name="biography" id="biography" placeholder="请输入艺术家传记">{{ $artist->biography }}</textarea>
        </div>

        <button type="submit">更新</button>
    </form>
@endsection

