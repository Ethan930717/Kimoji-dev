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
        <section class="panelV2">
            <h2 class="panel__heading">{{ __('search.results') }}</h2>
            <div>
                @foreach($results as $result)
                    <div class="search-result">
                        <div class="album-cover photo">
                            <img src="{{ $result['cover'] }}" alt="{{ $result['title'] }}">
                        </div>
                        <div class="details">
                            <h3 class="album-title">{{ $result['title'] }}</h3>
                            <h4 class="artist-name">{{ $result['artist'] }}</h4>
                            <p class="data">{{ $result['category'] }} - {{ $result['release_date'] }} | {{ $result['label'] }}</p>
                            <button class="form__button" wire:click="setDownloadUrl('{{ $result['url'] }}')">{{ __('download.album_button') }}</button>
                        </div>
                    </div>
                @endforeach
            </div>
        </section>
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
