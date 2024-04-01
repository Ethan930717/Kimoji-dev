@extends('layout.default')

@section('breadcrumbs')
    <li class="breadcrumb--active">
        <a class="breadcrumb__link" href="{{ route('artists.index') }}">
            {{ __('artists.title') }} <!-- Artists -->
        </a>
    </li>
    <li class="breadcrumb--active">
        <a class="breadcrumb__link" href="{{ route('artists.show', $artist->id) }}">
            {{ $artist->name }}
        </a>
    </li>
    <li class="breadcrumb--active">
        {{ __('common.edit') }} <!-- Edit -->
    </li>
@endsection

@section('main')
    <section class="panelV2">
        <h2 class="panel__heading">{{ __('common.edit') }}: {{ $artist->name }}</h2>
        <h2 class="panel__heading" style="font-size: 15px">All users can edit artist information, but malicious editing will be warned or accounts will be frozen</h2>
        <div class="panel__body">
            <form method="POST" action="{{ route('artists.update', $artist->id) }}">
                @csrf
                @method('PATCH')
                <p class="form__group">
                    <label for="birthday" class="form__label">Birthday / Establishment Date</label>
                    <input type="date" class="form__text" name="birthday" id="birthday" value="{{ $artist->birthday }}" placeholder="yyyy-mm-dd">
                </p>

                <p class="form__group">
                    <label for="deathday" class="form__label">Death Day / Dissolution Date</label>
                    <input type="date" class="form__text" name="deathday" id="deathday" value="{{ $artist->deathday }}" placeholder="yyyy-mm-dd">
                </p>

                <p class="form__group">
                    <label for="member" class="form__label">Band Members</label>
                    <input type="text" class="form__text" name="member" id="member" value="{{ $artist->member }}" placeholder="Please separate each name with '/' symbol, leave blank for solo artists">
                </p>

                <p class="form__group">
                    <label for="country" class="form__label">Country / Region</label>
                    <input type="text" class="form__text" name="country" id="country" value="{{ $artist->country }}" placeholder="">
                </p>

                <p class="form__group">
                    <label for="label" class="form__label">Record Label</label>
                    <input type="text" class="form__text" name="label" id="label" value="{{ $artist->label }}" placeholder="Please separate each label name with '/' symbol">
                </p>

                <p class="form__group">
                    <label for="genre" class="form__label">Genre</label>
                    <input type="text" class="form__text" name="genre" id="genre" value="{{ $artist->genre }}" placeholder="Please separate each genre with '/' symbol">
                </p>

                <p class="form__group">
                    <label for="biography" class="form__label">Biography</label>
                    <textarea name="biography" class="form__text" id="biography" placeholder="Please enter the artist's biography">{{ $artist->biography }}</textarea>
                </p>
                <p class="form__group">
                    <button class="form__button form__button--filled" type="submit">
                        {{ __('common.submit') }} <!-- Submit -->
                    </button>
                </p>
            </form>
        </div>
    </section>
@endsection
