@extends('layout.default')

@section('title')
    <title>Application - {{ __('staff.staff-dashboard') }} - {{ config('other.title') }}</title>
@endsection

@section('meta')
    <meta name="description" content="Application - {{ __('staff.staff-dashboard') }}">
@endsection

@section('breadcrumbs')
    <li class="breadcrumbV2">
        <a href="{{ route('staff.dashboard.index') }}" class="breadcrumb__link">
            {{ __('staff.staff-dashboard') }}
        </a>
    </li>
    <li class="breadcrumbV2">
        <a href="{{ route('staff.applications.index') }}" class="breadcrumb__link">
            {{ __('staff.applications') }}
        </a>
    </li>
    <li class="breadcrumb--active">
        {{ $application->id }}
    </li>
@endsection

@section('page', 'page__application--show')

@section('main')
    <section class="panelV2">
        <h2 class="panel__heading">{{ __('common.image') }}</h2>
        <div class="panel__body">
            <ul>
                @foreach($application->imageProofs as $img_proof)
                    <li>
                        <a href="{{ $img_proof->image }}" target="_blank">
                            {{ $img_proof->image }}
                        </a>
                    </li>
                @endforeach
            </ul>
        </div>
    </section>
    <section class="panelV2">
        <h2 class="panel__heading">
            {{ __('user.profile') }} {{ __('staff.links') }}
        </h2>
        <div class="panel__body">
            <ul>
                @foreach($application->urlProofs as $url_proof)
                    <li>
                        <a href="{{ $url_proof->url }}" target="_blank">
                            {{ $url_proof->url }}
                        </a>
                    </li>
                @endforeach
            </ul>
        </div>
    </section>
    <section class="panelV2">
        <h2 class="panel__heading">
            {{ __('staff.application-referrer') }}
        </h2>
        <div class="panel__body">{{ $application->referrer }}</div>
    </section>
@endsection

@section('sidebar')
    <section class="panelV2">
        <h2 class="panel__heading">{{ __('common.info') }}</h2>
        <dl class="key-value">
            <dt>{{ __('common.email') }}</dt>
            <dd>{{ $application->email }}</dd>
            <dt>{{ __('staff.application-type') }}</dt>
            <dd>{{ $application->type }}</dd>
            <dt>{{ __('common.created_at') }}</dt>
            <dd>
                <time datetime="{{ $application->created_at }}" title="{{ $application->created_at }}">
                    {{ $application->created_at->diffForHumans() }}
                </time>
            </dd>
            <dt>{{ __('common.status') }}</dt>
            <dd>
                @switch($application->status)
                    @case(\App\Models\Application::PENDING)
                        <span class="application--pending">Pending</span>
                        @break
                    @case(\App\Models\Application::APPROVED)
                        <span class="application--approved">Approved</span>
                        @break
                    @case(\App\Models\Application::REJECTED)
                        <span class="application--rejected">Rejected</span>
                        @break
                    @default
                        <span class="application--unknown">Unknown</span>
                @endswitch
            </dd>
        </dl>
    </section>
    <section class="panelV2">
        @if($application->status !== \App\Models\Application::PENDING)
            <h2 class="panel__heading">{{ __('common.moderated-by') }}</h2>
            <div class="panel__body">
                <x-user_tag :anon="false" :user="$application->moderated" />
            </div>
        @else
            <h2 class="panel__heading">{{ __('common.action') }}</h2>
            <div class="panel__body">
                    <div x-data>
                        <p class="form__group form__group--horizontal">
                            <button class="form__button form__button--filled form__button--centered" x-on:click.stop="$refs.dialog.showModal()">
                                <i class="{{ config('other.font-awesome') }} fa-check"></i>
                                {{ __('request.approve') }}
                            </button>
                        </p>
                        <dialog class="dialog" x-ref="dialog">
                            <h3 class="dialog__heading">
                                {{ __('request.approve') }}
                                {{ __('common.this') }}
                                {{ __('staff.application') }}
                            </h3>
                            <form
                                class="dialog__form"
                                method="POST"
                                action="{{ route('staff.applications.approve', ['id' => $application->id]) }}"
                                x-on:click.outside="$refs.dialog.close()"
                            >
                                @csrf
                                <input
                                    id="email"
                                    name="email"
                                    type="hidden"
                                    value="{{ $application->email }}"
                                >
                                <p class="form__group">
                                    <textarea id="approve" class="form__textarea" name="approve" placeholder=" ">您的申请已通过，欢迎您进入KIMOJI的大家庭</textarea>
                                    <label class="form__label form__label--floating" for="approve">Invitation Message</label>
                                </p>
                                <p class="form__group">
                                    <button class="form__button form__button--filled">
                                        {{ __('request.approve') }}
                                    </button>
                                    <button formmethod="dialog" formnovalidate class="form__button form__button--outlined">
                                        {{ __('common.cancel') }}
                                    </button>
                                </p>
                            </form>
                        </dialog>
                    </div>
                    <div x-data>
                        <p class="form__group form__group--horizontal">
                            <button class="form__button form__button--filled form__button--centered" x-on:click.stop="$refs.dialog.showModal()">
                                <i class="{{ config('other.font-awesome') }} fa-times"></i>
                                {{ __('request.reject') }}
                            </button>
                        </p>
                        <dialog class="dialog" x-ref="dialog">
                            <h3 class="dialog__heading">
                                {{ __('request.reject') }}
                                {{ __('common.this') }}
                                {{ __('staff.application') }}
                            </h3>
                            <form
                                class="dialog__form"
                                method="POST"
                                action="{{ route('staff.applications.reject', ['id' => $application->id]) }}"
                                x-on:click.outside="$refs.dialog.close()"
                            >
                                @csrf
                                <input
                                    id="email"
                                    name="email"
                                    type="hidden"
                                    value="{{ $application->email }}"
                                >
                                <p class="form__group">
                                    <textarea
                                        id="message"
                                        class="form__textarea"
                                        name="deny"
                                        required
                                    >尊敬的申请者，感谢您对加入KIMOJI所表现出的兴趣和热情。经过仔细审核您提交的入站申请，我们遗憾地通知您，您的申请未能通过审核。</textarea>
                                    <label class="form__label form__label--floating" for="message">Rejection Message</label>

                                </p>
                                <p class="form__group">
                                    <button class="form__button form__button--filled">
                                        {{ __('request.reject') }}
                                    </button>
                                    <button formmethod="dialog" formnovalidate class="form__button form__button--outlined">
                                        {{ __('common.cancel') }}
                                    </button>
                                </p>
                            </form>
                        </dialog>
                    </div>
            </div>
        @endif
    </section>
@endsection
