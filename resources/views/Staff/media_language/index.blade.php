@extends('layout.default')


@section('breadcrumbs')
    <li class="breadcrumbV2">
        <a href="{{ route('staff.dashboard.index') }}" class="breadcrumb__link">
            {{ __('staff.staff-dashboard') }}
        </a>
    </li>
    <li class="breadcrumb--active">
        {{ __('common.media-languages') }}
    </li>
@endsection

@section('page', 'page__media-language--index')

@section('main')
    <section class="panelV2">
        <header class="panel__header">
            <h2 class="panel__heading">
                {{ __('common.media-languages') }} {{ __('staff.media-languages-desc') }}
            </h2>
            <div class="panel__actions">
                <a
                    class="panel__action form__button form__button--text"
                    href="{{ route('staff.media_languages.create') }}"
                >
                    {{ __('common.add') }}
                </a>
            </div>
        </header>
        <div class="data-table-wrapper">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>{{ __('common.name') }}</th>
                        <th>{{ __('common.code') }}</th>
                        <th>{{ __('common.action') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($media_languages as $media_language)
                        <tr>
                            <td>
                                <a href="{{ route('staff.media_languages.edit', ['mediaLanguage' => $media_language]) }}">
                                    {{ $media_language->name }}
                                </a>
                            </td>
                            <td>{{ $media_language->code }}</td>
                            <td>
                                <menu class="data-table__actions">
                                    <li class="data-table__action">
                                        <a
                                            class="form__button form__button--text"
                                            href="{{ route('staff.media_languages.edit', ['mediaLanguage' => $media_language]) }}"
                                        >
                                            {{ __('common.edit') }}
                                        </a>
                                    </li>
                                    <li class="data-table__action">
                                        <form
                                            method="POST"
                                            action="{{ route('staff.media_languages.destroy', ['mediaLanguage' => $media_language]) }}"
                                            x-data
                                        >
                                            @csrf
                                            @method('DELETE')
                                            <button
                                                x-on:click.prevent="Swal.fire({
                                                    title: '请确认',
                                                    text: `是否确认删除该语言: ${decodeURIComponent(atob('{{ base64_encode(rawurlencode($media_language->name)) }}'))}`,
                                                    icon: 'warning',
                                                    showConfirmButton: true,
                                                    showCancelButton: true,
                                                }).then((result) => {
                                                    if (result.isConfirmed) {
                                                        $root.submit();
                                                    }
                                                })"
                                                class="form__button form__button--text"
                                            >
                                                {{ __('common.delete') }}
                                            </button>
                                        </form>
                                    </li>
                                </menu>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </section>
@endsection
