<footer class="footer">
    <div class="footer__wrapper">
        <section class="footer__section" style="text-align: center; display: flex; justify-content: center; align-items: center; height: 100%;">
            <a href="/">
                <img src="/img/indexlogo.png" alt="Footer Logo" style="max-width: 100%; height: auto;margin-top: 30px">
            </a>
        </section>
        <section class="footer__section">
            <h2 class="footer__section-title">{{ __('common.account') }}</h2>
            <ul class="footer__section-list">
                <li>
                    <a href="{{ route('users.show', ['user' => auth()->user()]) }}">{{ __('user.my-profile') }}</a>
                </li>
                <li>
                    <form action="{{ route('logout') }}" method="POST" style="display: contents;">
                        @csrf
                        <button style="display: contents">
                            {{ __('common.logout') }}
                        </button>
                    </form>
                </li>
            </ul>
        </section>
        <section class="footer__section">
            <h2 class="footer__section-title">{{ __('common.community') }}</h2>
            <ul class="footer__section-list">
                <li>
                    <a href="{{ route('forums.index') }}">{{ __('forum.forums') }}</a>
                </li>
                <li>
                    <a href="{{ route('articles.index') }}">{{ __('common.news') }}</a>
                </li>
            </ul>
        </section>
        @if ($footer_pages)
            <section class="footer__section">
                <h2 class="footer__section-title">{{ __('common.pages') }}</h2>
                <ul class="footer__section-list">
                    @foreach ($footer_pages as $page)
                        <li>
                            <a href="{{ route('pages.show', ['page' => $page]) }}">
                                {{ $page->name }}
                            </a>
                        </li>
                    @endforeach
                </ul>
            </section>
        @endif
        <section class="footer__section">
            <h2 class="footer__section-title">{{ __('common.info') }}</h2>
            <ul class="footer__section-list">
                <li>
                    <a href="{{ route('staff') }}">{{ __('common.staff') }}</a>
                </li>
                <li>
                    <a href="{{ route('internal') }}">{{ __('common.internal') }}</a>
                </li>
                <li>
                    <a href="{{ route('client_blacklist') }}">{{ __('common.blacklist') }}</a>
                </li>
                <li>
                    <a href="{{ route('about') }}">{{ __('common.about') }}</a>
                </li>
                <li>
                    <a href="https://github.com/HDInnovations/UNIT3D-Community-Edition/wiki/Torrent-API-(UNIT3D-v7.0.0)">
                        API 文档
                    </a>
                </li>
            </ul>
        </section>
        <section class="footer__section">
            <h2 class="footer__section-title">{{ __('common.other') }}</h2>
            <ul class="footer__section-list">
                <li>
                    <a
                        href="{{ route('sponsor') }}"
                        class="form__button form__button--outlined"
                    >
                        {{ __('common.sponsor') }}
                    </a>
                </li>

            </ul>
        </section>
    </div>
    <p class="footer__stats">
        KIMOJI提供的所有资源均是在网上搜集且由用户上传，KIMOJI对资源的具体内容毫不知情，如有侵权，请及时告知！
    </p>
</footer>
