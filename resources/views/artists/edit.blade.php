@extends('layout.default')

@section('main')
    <h1>编辑艺术家</h1>
    <form method="POST" action="{{ route('artists.update', $artist->id) }}">
        @csrf
        @method('PUT')

        <div>
            <label for="name">名称</label>
            <input type="text" name="name" id="name" value="{{ $artist->name }}" required>
        </div>

        <div>
            <label for="birthday">生日</label>
            <input type="date" name="birthday" id="birthday" value="{{ $artist->birthday }}">
        </div>

        <div>
            <label for="deathday">忌日</label>
            <input type="date" name="deathday" id="deathday" value="{{ $artist->deathday }}">
        </div>

        <div>
            <label for="member">组成员</label>
            <input type="text" name="member" id="member" value="{{ $artist->member }}">
        </div>

        <div>
            <label for="country">国家</label>
            <input type="text" name="country" id="country" value="{{ $artist->country }}">
        </div>

        <div>
            <label for="label">唱片公司</label>
            <input type="text" name="label" id="label" value="{{ $artist->label }}">
        </div>

        <div>
            <label for="genre">风格</label>
            <input type="text" name="genre" id="genre" value="{{ $artist->genre }}">
        </div>

        <div>
            <label for="biography">传记</label>
            <textarea name="biography" id="biography">{{ $artist->biography }}</textarea>
        </div>

        <button type="submit">更新</button>
    </form>
@endsection
