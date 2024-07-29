<div class="panelV2">
    <section class="panelV2">
        <h2 class="panel__heading">{{ __('search.title') }}</h2>
        <form wire:submit.prevent="search">
            <div class="form__group">
                <input type="text" wire:model="query" class="form__input" placeholder="{{ __('search.placeholder') }}" required>
                <button type="submit" class="form__button">{{ __('search.button') }}</button>
            </div>
        </form>
    </section>

    @if($results)
        <div class="panelV2">
            <h2 class="panel__heading">{{ __('search.results') }}</h2>
            <div class="panel__body" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(140px, 1fr)); gap: 2rem;">
                <table class="data-table">
                    <thead>
                    <tr>
                        <th>{{ __('Cover') }}</th>
                        <th style="white-space: nowrap;">{{ __('Title') }}</th>
                        <th style="white-space: nowrap;">{{ __('Artist') }}</th>
                        <th>{{ __('Category') }}</th>
                        <th style="white-space: nowrap;">{{ __('Download') }}</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($results as $result)
                        <tr>
                            <td>
                                <img class="album-cover" data-fancybox="gallery" src="{{ $result['cover'] }}" alt="{{ $result['title'] }}" style="width: 100%; max-width: 200px; height: auto; cursor: pointer;" />
                            </td>
                            <td style="white-space: nowrap;">{{ $result['title'] }}</td>
                            <td style="white-space: nowrap;">{{ $result['artist'] }}</td>
                            <td style="white-space: nowrap;"> <p class="data">{{ $result['category'] }}</p></td>
                            <td style="white-space: nowrap;"> <button class="form__button" wire:click="setDownloadUrl('{{ $result['url'] }}')">{{ __('Request') }}</button></td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <div class="pagination">
            @if ($totalResults > $perPage)
                @php
                    $totalPages = ceil($totalResults / $perPage);
                @endphp
                <nav>
                    <ul class="pagination">
                        @for ($i = 1; $i <= $totalPages; $i++)
                            <li class="page-item {{ $i == $currentPage ? 'active' : '' }}">
                                <a class="page-link" wire:click.prevent="setPage({{ $i }})" href="#">{{ $i }}</a>
                            </li>
                        @endfor
                    </ul>
                </nav>
            @endif
        </div>
    @endif

    @if($downloadUrl)
        <section class="panelV2">
            <h2 class="panel__heading">{{ __('download.title') }}</h2>
            <div class="form__group">
                <input type="text" wire:model="downloadUrl" class="form__input" placeholder="{{ __('download.placeholder') }}" required>
                <button type="button" wire:click="download" class="form__button">{{ __('download.button') }}</button>
            </div>
        </section>
    @endif

    @if (session()->has('message'))
        <div class="alert alert-success">
            {{ session('message') }}
        </div>
    @endif
</div>
